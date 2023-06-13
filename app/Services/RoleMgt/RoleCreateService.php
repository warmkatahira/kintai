<?php

namespace App\Services\RoleMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleCreateService
{
    public function createRole($request)
    {
        Role::create([
            'role_name' => $request->role_name,
            'is_kintai_mgt_func_available' => $request->is_kintai_mgt_func_available,
            'is_base_check_available' => $request->is_kintai_mgt_func_available == 0 ? 0 : $request->is_base_check_available,
            'is_kintai_operation_available' => $request->is_kintai_mgt_func_available == 0 ? 0 : $request->is_kintai_operation_available,
            'is_employee_mgt_func_available' => $request->is_employee_mgt_func_available,
            'is_employee_operation_available' => $request->is_employee_mgt_func_available == 0 ? 0 : $request->is_employee_operation_available,
            'is_base_mgt_func_available' => $request->is_base_mgt_func_available,
            'is_manual_punch_available' => $request->is_base_mgt_func_available == 0 ? 0 : $request->is_manual_punch_available,
            'is_customer_mgt_func_available' => $request->is_base_mgt_func_available == 0 ? 0 : $request->is_customer_mgt_func_available,
            'is_kintai_close_available' => $request->is_base_mgt_func_available == 0 ? 0 : $request->is_kintai_close_available,
            'is_download_func_available' => $request->is_download_func_available,
            'is_accounting_mgt_func_available' => $request->is_accounting_mgt_func_available,
            'is_system_mgt_func_available' => $request->is_system_mgt_func_available,
            'is_user_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_user_mgt_available,
            'is_role_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_role_mgt_available,
            'is_holiday_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_holiday_mgt_available,
            'is_access_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_access_mgt_available,
            'is_lock_kintai_operation_available' => $request->is_lock_kintai_operation_available,
            'is_all_kintai_operation_available' => $request->is_all_kintai_operation_available,
        ]);
        return;
    }
}