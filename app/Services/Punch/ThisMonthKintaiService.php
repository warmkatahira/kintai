<?php

namespace App\Services\Punch;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;

class ThisMonthKintaiService
{
    public function getMonthKintai($start_of_month, $end_of_month)
    {
        // 自拠点従業員の当月の勤怠を集計
        $this_month_kintais = Employee::join('kintais', 'kintais.employee_id', 'employees.employee_id')
                                ->where('base_id', Auth::user()->base_id)
                                ->whereDate('work_day', '>=', $start_of_month)->whereDate('work_day', '<=', $end_of_month)
                                ->select(DB::raw("sum(working_time) as total_working_time, sum(over_time) as total_over_time, employees.employee_id, employees.employee_no, DATE_FORMAT(work_day, '%Y-%m') as date"))
                                ->groupBy('employee_id', 'date');
        // 集計した勤怠を従業員テーブルと結合
        $month_kintais = Employee::
            leftJoinSub($this_month_kintais, 'KINTAIS', function ($join) {
                $join->on('employees.employee_id', '=', 'KINTAIS.employee_id');
            })
            ->where('employees.base_id', Auth::user()->base_id)
            ->join('bases', 'employees.base_id', 'bases.base_id')
            ->select('employees.employee_id', 'employees.employee_no', 'employee_last_name', 'employee_first_name', 'employee_category_id', 'KINTAIS.total_working_time', 'KINTAIS.total_over_time', 'base_name', 'over_time_start', 'is_available')
            ->where('is_available', 1)
            ->orderBy('employees.employee_category_id', 'asc')
            ->orderBy('employees.employee_no', 'asc')
            ->get();
        return $month_kintais;
    }
}