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

class DataDownloadService
{
    public function getDownloadData($request, $start_day, $end_day)
    {
        // 拠点情報を取得
        $base = Base::getSpecify($request->base_id)->first();
        // ダウンロード項目で処理を分岐
        // 稼働時間(荷主別)
        if($request->download_item == DataDownloadEnum::WORKING_TIME_BY_CUSTOMER){
            $download_data = $this->getWorkingTimeByCustomer($base, $request->base_id, $start_day, $end_day);
            $file_name = '【'.$base->base_name.'】【'.CarbonImmutable::parse($start_day)->isoFormat('YYYY年MM月').'】稼働時間(荷主別)_'.CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv';
        }
        // 稼働時間(従業員別)
        if($request->download_item == DataDownloadEnum::WORKING_TIME_BY_EMPLOYEE){
            $download_data = $this->getWorkingTimeByEmployee($base, $request->base_id, $start_day, $end_day);
            $file_name = '【'.$base->base_name.'】【'.CarbonImmutable::parse($start_day)->isoFormat('YYYY年MM月').'】稼働時間(従業員別)_'.CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv';
        }
        return compact('download_data', 'file_name');
    }

    // 稼働時間(荷主別)
    public function getWorkingTimeByCustomer($base, $base_id, $start_day, $end_day)
    {
        // 指定された年月・拠点の荷主毎の稼働時間を取得
        $subquery1 = Kintai::whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->whereHas('employee.base', function ($query) use ($base_id) {
                            $query->where('base_id', $base_id);
                        })
                        ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                        ->select(DB::raw("kintai_details.customer_id, sum(customer_working_time) as total_customer_working_time, DATE_FORMAT(work_day, '%Y-%m') as date"))
                        ->groupBy('kintai_details.customer_id', 'date');
        // 荷主マスタと拠点マスタをユニオン
        $subquery2 = Customer::where('base_id', $base_id)
                            ->select('customers.customer_id', 'customer_name', DB::raw("'' as support"))
                            ->union(Base::select('base_id', 'base_name', DB::raw("'○' as support")));
        // subqueryを結合
        $customers = DB::table(DB::raw("({$subquery1->toSql()}) as subquery1"))
                    ->mergeBindings($subquery1->getQuery())
                    ->rightJoin(DB::raw("({$subquery2->toSql()}) as subquery2"), function ($join) {
                        $join->on('subquery1.customer_id', '=', 'subquery2.customer_id');
                    })
                    ->mergeBindings($subquery2->getQuery())
                    ->select('subquery2.customer_id', 'customer_name', 'support', 'total_customer_working_time', 'date')
                    ->orderBy('support', 'asc')
                    ->orderBy('subquery2.customer_id', 'asc')
                    ->get();
        // データを格納する配列をセット
        $download_data = [];
        // 対象の分だけループ処理
        foreach($customers as $customer){
            $param = [
                '年月' => CarbonImmutable::parse($customer->date)->isoFormat('YYYY年MM月'),
                '拠点' => $base->base_name,
                '荷主名' => $customer->customer_name,
                '応援' => $customer->support,
                '稼働時間(分単位)' => is_null($customer->total_customer_working_time) ? 0 : $customer->total_customer_working_time,
                '稼働時間(0.25単位)' => $customer->total_customer_working_time / 60,
            ];
            $download_data[] = $param;
        }
        return $download_data;
    }

    // 稼働時間(従業員別)
    public function getWorkingTimeByEmployee($base, $base_id, $start_day, $end_day)
    {
        // 指定された年月・拠点の荷主毎の稼働時間を取得
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
                            })
                            ->select(DB::raw("DATE_FORMAT(work_day, '%Y-%m') as date, employee_category_name, employee_no, employee_last_name, employee_first_name, sum(working_time) as total_working_time"))
                            ->groupBy('date', 'employee_no')
                            ->orderBy('employee_no')
                            ->get();
        // データを格納する配列をセット
        $download_data = [];
        // 対象の分だけループ処理
        foreach($employees as $employee){
            $param = [
                '年月' => CarbonImmutable::parse($employee->date)->isoFormat('YYYY年MM月'),
                '拠点' => $base->base_name,
                '従業員区分' => $employee->employee_category_name,
                '従業員番号' => $employee->employee_no,
                '従業員名' => $employee->employee_last_name.' '.$employee->employee_first_name,
                '稼働時間(分単位)' => is_null($employee->total_working_time) ? 0 : $employee->total_working_time,
                '稼働時間(0.25単位)' => $employee->total_working_time / 60,
            ];
            $download_data[] = $param;
        }
        return $download_data;
    }
}