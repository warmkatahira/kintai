<?php

namespace App\Services\EmployeeMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;


class EmployeeUpdateService
{
    public function updateEmployee($request)
    {
        // 更新内容を配列にセット
        $param = [
            'base_id' => $request->base_id,
            'employee_category_id' => $request->employee_category_id,
            'employee_no' => $request->employee_no,
            'employee_last_name' => $request->employee_last_name,
            'employee_first_name' => $request->employee_first_name,
            'monthly_workable_time' => $request->monthly_workable_time,
        ];
        // 時短情報が有効な場合のみ更新する
        if(Gate::allows('isShortTimeInfoAvailable')){
            $param['over_time_start'] = is_null($request->over_time_start) ? 0 : $request->over_time_start;
        }
        // 更新処理
        Employee::getSpecify($request->employee_id)->update($param);
        return;
    }
}