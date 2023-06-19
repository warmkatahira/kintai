<?php

namespace App\Services\Download;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Base;
use App\Models\Customer;
use App\Models\Kintai;
use App\Models\Employee;
use Carbon\CarbonImmutable;
use App\Enums\DataDownloadEnum;
use App\Enums\EmployeeCategoryEnum;

class DataDownloadService
{
    public function getDownloadData($request, $start_day, $end_day)
    {
        // 拠点情報を取得
        $base = Base::getSpecify($request->base_id)->first();
        // ダウンロード項目で処理を分岐
        // 稼働時間(荷主別)
        if($request->download_item == DataDownloadEnum::WORKING_TIME_BY_CUSTOMER){
            $download_data = $this->getWorkingTimeByCustomer($base, $request->base_id, $start_day, $end_day, $request->aggregate_unit);
            $file_name = '【'.$base->base_name.'】【'.$request->aggregate_unit.'】稼働時間(荷主別)_'.CarbonImmutable::parse($start_day)->isoFormat('YYYY年MM月DD日').'-'.CarbonImmutable::parse($end_day)->isoFormat('YYYY年MM月DD日').'.csv';
        }
        // 稼働時間(従業員別)
        if($request->download_item == DataDownloadEnum::WORKING_TIME_BY_EMPLOYEE){
            $download_data = $this->getWorkingTimeByEmployee($base, $request->base_id, $start_day, $end_day, $request->aggregate_unit);
            $file_name = '【'.$base->base_name.'】【'.$request->aggregate_unit.'】稼働時間(従業員別)_'.CarbonImmutable::parse($start_day)->isoFormat('YYYY年MM月DD日').'-'.CarbonImmutable::parse($end_day)->isoFormat('YYYY年MM月DD日').'.csv';
        }
        return compact('download_data', 'file_name');
    }

    // 稼働時間(荷主別)
    public function getWorkingTimeByCustomer($base, $base_id, $start_day, $end_day, $aggregate_unit)
    {
        // 指定された年月・拠点の荷主毎の稼働時間を取得(合計)
        $total = $this->getKintaiTotal($start_day, $end_day, $base_id, $aggregate_unit);
        // 指定された年月・拠点の荷主毎の稼働時間を取得(社員)
        $shain = $this->getKintaiByCategory($start_day, $end_day, $base_id, EmployeeCategoryEnum::CONTRACT_EMPLOYEE, '<=', $aggregate_unit);
        // 指定された年月・拠点の荷主毎の稼働時間を取得(パート)
        $part = $this->getKintaiByCategory($start_day, $end_day, $base_id, EmployeeCategoryEnum::PART_TIME_EMPLOYEE, '=', $aggregate_unit);
        // 荷主マスタと拠点マスタをユニオン
        $customers = Customer::where('base_id', $base_id)
                            ->select('customers.customer_id', 'customer_name', DB::raw("'' as support"))
                            ->union(Base::select('base_id', 'base_name', DB::raw("'○' as support")));
        // サブクエリを結合
        $customers = DB::table(DB::raw("({$total->toSql()}) as total"))
                    ->mergeBindings($total->getQuery())
                    ->leftJoin(DB::raw("({$shain->toSql()}) as shain"), function ($join) {
                        $join->on('total.customer_id', '=', 'shain.customer_id')
                            ->on('total.date', '=', 'shain.date');
                    })
                    ->mergeBindings($shain->getQuery())
                    ->leftJoin(DB::raw("({$part->toSql()}) as part"), function ($join) {
                        $join->on('total.customer_id', '=', 'part.customer_id')
                            ->on('total.date', '=', 'part.date');
                    })
                    ->mergeBindings($part->getQuery())
                    ->leftJoin(DB::raw("({$customers->toSql()}) as customers"), function ($join) {
                        $join->on('total.customer_id', '=', 'customers.customer_id');
                    })
                    ->mergeBindings($customers->getQuery())
                    ->select('customers.customer_id', 'customer_name', 'support', 'total.total_customer_working_time AS total', 'shain.total_customer_working_time AS shain', 'part.total_customer_working_time AS part', 'total.date')
                    ->orderBy('date', 'asc')
                    ->orderBy('support', 'asc')
                    ->orderBy('customers.customer_id', 'asc')
                    ->get();
        // データを格納する配列をセット
        $download_data = [];
        // 対象の分だけループ処理
        foreach($customers as $customer){
            $param = [
                '日付' => $aggregate_unit == '日単位' ? CarbonImmutable::parse($customer->date)->isoFormat('YYYY年MM月DD日') : CarbonImmutable::parse($customer->date)->isoFormat('YYYY年MM月'),
                '拠点' => $base->base_name,
                '荷主名' => $customer->customer_name,
                '応援' => $customer->support,
                '稼働時間(社員)' => $customer->shain / 60,
                '稼働時間(パート)' => $customer->part / 60,
                '稼働時間(合計)' => $customer->total / 60,
            ];
            $download_data[] = $param;
        }
        return $download_data;
    }

    // 指定された年月・拠点の荷主毎の稼働時間を取得(合計)
    public function getKintaiTotal($start_day, $end_day, $base_id, $aggregate_unit)
    {
        $query = Kintai::whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->whereHas('employee.base', function ($query) use ($base_id) {
                            $query->where('base_id', $base_id);
                        })
                        ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                        ->join('employees', 'employees.employee_id', 'kintais.employee_id');
        // 日単位か月単位かで日付をフォーマットするか可変
        if($aggregate_unit == '日単位'){
            $query->select(DB::raw("customer_id, sum(customer_working_time) as total_customer_working_time, work_day as date"));
        }else{
            $query->select(DB::raw("customer_id, sum(customer_working_time) as total_customer_working_time, DATE_FORMAT(work_day, '%Y-%m') as date"));
        }
        return $query->groupBy('customer_id', 'date');
    }

    // 指定された年月・拠点の荷主毎の稼働時間を取得(従業員区分別)
    public function getKintaiByCategory($start_day, $end_day, $base_id, $employee_category_id, $sign, $aggregate_unit)
    {
        $query = Kintai::whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->whereHas('employee.base', function ($query) use ($base_id) {
                            $query->where('base_id', $base_id);
                        })
                        ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                        ->join('employees', 'employees.employee_id', 'kintais.employee_id')
                        ->where('employee_category_id', $sign, $employee_category_id);
        // 日単位か月単位かで日付をフォーマットするか可変
        if($aggregate_unit == '日単位'){
            $query->select(DB::raw("customer_id, sum(customer_working_time) as total_customer_working_time, work_day as date, GROUP_CONCAT(employees.employee_category_id) as employee_category_ids"));
        }else{
            $query->select(DB::raw("customer_id, sum(customer_working_time) as total_customer_working_time, DATE_FORMAT(work_day, '%Y-%m') as date, GROUP_CONCAT(employees.employee_category_id) as employee_category_ids"));
        }
        return $query->groupBy('customer_id', 'date');
    }

    // 稼働時間(従業員別)
    public function getWorkingTimeByEmployee($base, $base_id, $start_day, $end_day, $aggregate_unit)
    {
        // 指定された年月・拠点の勤怠を取得
        $subquery = Kintai::whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->whereHas('employee.base', function ($query) use ($base_id) {
                            $query->where('base_id', $base_id);
                        });
        // 拠点の従業員情報を取得
        $employees = Employee::where('base_id', $base_id)
                            ->join('employee_categories', 'employee_categories.employee_category_id', 'employees.employee_category_id')
                            ->leftJoinSub($subquery, 'SUB', function ($join) {
                                $join->on('employees.employee_id', '=', 'SUB.employee_id');
                            });
        // 日単位か月単位かで日付をフォーマットするか可変
        if($aggregate_unit == '日単位'){
            $employees->select(DB::raw("work_day as date, employee_category_name, employee_no, employee_last_name, employee_first_name, sum(working_time) as total_working_time"));
        }else{
            $employees->select(DB::raw("DATE_FORMAT(work_day, '%Y-%m') as date, employee_category_name, employee_no, employee_last_name, employee_first_name, sum(working_time) as total_working_time"));
        }
        $employees = $employees->groupBy('date', 'employee_no')
                    ->orderBy('employee_no')
                    ->get();
        // データを格納する配列をセット
        $download_data = [];
        // 対象の分だけループ処理
        foreach($employees as $employee){
            $param = [
                '日付' => $aggregate_unit == '日単位' ? CarbonImmutable::parse($employee->date)->isoFormat('YYYY年MM月DD日') : CarbonImmutable::parse($employee->date)->isoFormat('YYYY年MM月'),
                '拠点' => $base->base_name,
                '従業員区分' => $employee->employee_category_name,
                '従業員番号' => $employee->employee_no,
                '従業員名' => $employee->employee_last_name.' '.$employee->employee_first_name,
                '稼働時間' => $employee->total_working_time / 60,
            ];
            $download_data[] = $param;
        }
        return $download_data;
    }
}