<?php

namespace App\Services\EmployeeMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;


class EmployeeCreateService
{
    public function createEmployee($request)
    {
        // パート専用項目(時給単価・有給時稼働時間・交通費)は従業員CSV管理が有効な場合のみ登録する(無効の場合はDBの初期値0)
        $part_time_param = Gate::allows('isEmployeeCsvMgtAvailable') ? (new EmployeeMgtService)->getPartTimeParam($request) : [];
        Employee::create(array_merge([
            'base_id' => $request->base_id,
            'employee_category_id' => $request->employee_category_id,
            'employee_no' => $request->employee_no,
            'employee_last_name' => $request->employee_last_name,
            'employee_first_name' => $request->employee_first_name,
            'monthly_workable_time' => $request->monthly_workable_time,
            'over_time_start' => is_null($request->over_time_start) ? 0 : $request->over_time_start,
        ], $part_time_param));
        return;
    }
}
