<?php

namespace App\Services\EmployeeMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;


class EmployeeCreateService
{
    public function createEmployee($request)
    {
        Employee::create([
            'base_id' => $request->base_id,
            'employee_category_id' => $request->employee_category_id,
            'employee_no' => $request->employee_no,
            'employee_last_name' => $request->employee_last_name,
            'employee_first_name' => $request->employee_first_name,
            'monthly_workable_time' => $request->monthly_workable_time,
            'over_time_start' => $request->over_time_start,
        ]);
        return;
    }
}