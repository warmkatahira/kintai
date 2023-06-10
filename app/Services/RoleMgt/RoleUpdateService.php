<?php

namespace App\Services\RoleMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleUpdateService
{
    public function updateRole($request)
    {
        Role::getSpecify($request->role_id)->update([
            'role_name' => $request->role_name,
            'is_kintai_list_func_available' => $request->is_kintai_list_func_available,
            'is_employee_list_func_available' => $request->is_employee_list_func_available,
            'is_other_func_available' => $request->is_other_func_available,
            'is_data_export_func_available' => $request->is_data_export_func_available,
            'is_management_func_available' => $request->is_management_func_available,
            'is_system_mgt_func_available' => $request->is_system_mgt_func_available,
            'is_accounting_func_available' => $request->is_accounting_func_available,
            'is_kintai_check_available' => $request->is_kintai_check_available,
            'is_kintai_delete_available' => $request->is_kintai_delete_available,
            'is_kintai_modify_available' => $request->is_kintai_modify_available,
            'is_all_kintai_operation_available' => $request->is_all_kintai_operation_available,
            'is_employee_operation_available' => $request->is_employee_operation_available,
        ]);
        return;
    }
}