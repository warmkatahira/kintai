<?php

namespace App\Services\EmployeeMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Base;
use App\Models\EmployeeCategory;
use App\Models\Kintai;
use App\Models\KintaiDetail;
use App\Services\CommonService;

class EmployeeMgtService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_base_id',
            'search_employee_category_id',
            'search_employee_name'
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 初期条件をセット
        session(['search_base_id' => Auth::user()->base_id]);
        return;
    }

    public function setSearchCondition($request)
    {
        // セッションに検索条件をセット
        session(['search_base_id' => $request->search_base_id]);
        session(['search_employee_category_id' => $request->search_employee_category_id]);
        session(['search_employee_name' => $request->search_employee_name]);
        return;
    }

    public function getEmployeeSearch()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // インスタンス化
        $CommonService = new CommonService;
        // 当月の月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth(CarbonImmutable::today());
        // 当月の時間情報を集計
        $this_month_time = Kintai::whereDate('work_day', '>=', $start_end_of_month['start'])
                                    ->whereDate('work_day', '<=', $start_end_of_month['end'])
                                    ->select(DB::raw("sum(over_time) as total_over_time, sum(working_time) as total_working_time, employee_id"))
                                    ->groupBy('employee_id');
        // 集計した勤怠を従業員テーブルと結合
        $employees = Employee::
            leftJoinSub($this_month_time, 'TIME', function ($join) {
                $join->on('employees.employee_id', '=', 'TIME.employee_id');
            })
            ->select('employees.*', 'TIME.total_over_time', 'TIME.total_working_time');
        // 拠点条件がある場合
        if (session('search_base_id') != 0) {
            $employees->where('base_id', session('search_base_id'));
        }
        // 従業員名条件がある場合
        if (!empty(session('search_employee_name'))) {
            $employees->where('employee_last_name', 'LIKE', '%'.session('search_employee_name').'%')
                    ->orWhere('employee_first_name', 'LIKE', '%'.session('search_employee_name').'%');
        }
        // 区分条件がある場合
        if (!empty(session('search_employee_category_id'))) {
            $employees->where('employee_category_id', session('search_employee_category_id'));
        }
        // 従業員番号で並び替え
        $employees = $employees->orderBy('employee_no')->paginate(50);
        return $employees;
    }

    public function getThisMonthData($nowDate, $employee_id){
        // 当月の合計時間・稼働日数を取得
        return Kintai::where('employee_id', $employee_id)
                        ->whereDate('work_day', '>=', $nowDate->startOfMonth()->toDateString())
                        ->whereDate('work_day', '<=', $nowDate->endOfMonth()->toDateString())
                        ->select(DB::raw("sum(working_time) as total_working_time, sum(over_time) as total_over_time, count(work_day) as working_days, DATE_FORMAT(work_day, '%Y-%m') as date"))
                        ->groupBy('employee_id', 'date')
                        ->first();
    }

    public function getCustomerWorkingTime($nowDate, $employee_id)
    {
        // 荷主マスタと拠点マスタをユニオン
        $subquery = Customer::select('customer_id', 'customer_name')
                ->union(Base::select('base_id', 'base_name'));
        // 対象従業員の当月の勤怠を取得
        $kintais = Kintai::where('employee_id', $employee_id)
                    ->whereDate('work_day', '>=', $nowDate->startOfMonth()->toDateString())
                    ->whereDate('work_day', '<=', $nowDate->endOfMonth()->toDateString());
        // 取得した勤怠を勤怠詳細テーブルと結合して、荷主毎の稼働時間を集計
        $customer_working_time = KintaiDetail::
            joinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('kintai_details.kintai_id', '=', 'KINTAIS.kintai_id');
            })
            ->joinSub($subquery, 'SUBQUERY', function ($join) {
                $join->on('kintai_details.customer_id', '=', 'SUBQUERY.customer_id');
            })
            ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, DATE_FORMAT(work_day, '%Y-%m') as date, kintai_details.customer_id, SUBQUERY.customer_name"))
            ->groupBy('date', 'kintai_details.customer_id', 'SUBQUERY.customer_name')
            ->orderBy('total_customer_working_time', 'desc')
            ->take(3)
            ->get();
        return $customer_working_time;
    }
}