<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Employee;
use App\Models\Kintai;

class OverTimeRankService
{
    public function setDefaultCondition()
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 初期条件をセット
        session(['search_month' => $nowDate->format('Y-m')]);
        return;
    }

    public function getSearchCondition($search_month)
    {
        // 検索条件をセット
        session(['search_month' => $search_month]);
        return;
    }

    // 正社員の残業時間情報を取得
    public function getOverTimeData()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 月初と月末の日付を取得
        $start_day = new CarbonImmutable(session('search_month'));
        $end_day = new CarbonImmutable(session('search_month'));
        // 正社員の残業時間を集計
        $kintais = Kintai::join('employees', 'employees.employee_no', 'kintais.employee_no')
                                ->where('employee_category_id', 1)
                                ->whereBetween('work_day', [$start_day->startOfMonth()->toDateString(), $end_day->endOfMonth()->toDateString()])
                                ->select(DB::raw("sum(over_time) as total_over_time, kintais.employee_no, DATE_FORMAT(work_day, '%Y-%m') as date"))
                                ->groupBy('employee_no', 'date');
        // 残業時間が多い順に並び変える
        $employees = Employee::
            leftJoinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('employees.employee_no', '=', 'KINTAIS.employee_no');
            })
            ->where('employee_category_id', 1)
            ->select('employees.employee_no', 'employees.employee_name', 'employees.employee_category_id', 'KINTAIS.total_over_time', 'employees.base_id')
            ->orderBy('total_over_time', 'desc')
            ->orderBy('employee_no')
            ->get();
        return $employees;
    }
}