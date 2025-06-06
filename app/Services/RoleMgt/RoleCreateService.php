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
            'is_comment_operation_available' => $request->is_kintai_mgt_func_available == 0 ? 0 : $request->is_comment_operation_available,
            'is_employee_mgt_func_available' => $request->is_employee_mgt_func_available,
            'is_employee_operation_available' => $request->is_employee_mgt_func_available == 0 ? 0 : $request->is_employee_operation_available,
            'is_base_mgt_func_available' => $request->is_base_mgt_func_available,
            'is_manual_punch_available' => $request->is_base_mgt_func_available == 0 ? 0 : $request->is_manual_punch_available,
            'is_customer_mgt_func_available' => $request->is_base_mgt_func_available == 0 ? 0 : $request->is_customer_mgt_func_available,
            'is_kintai_close_available' => $request->is_base_mgt_func_available == 0 ? 0 : $request->is_kintai_close_available,
            'is_download_func_available' => $request->is_download_func_available,
            'is_kintai_report_download_available' => $request->is_download_func_available == 0 ? 0 : $request->is_kintai_report_download_available,
            'is_data_download_available' => $request->is_download_func_available == 0 ? 0 : $request->is_data_download_available,
            'is_other_func_available' => $request->is_other_func_available,
            'is_accounting_mgt_func_available' => $request->is_accounting_mgt_func_available,
            'is_system_mgt_func_available' => $request->is_system_mgt_func_available,
            'is_user_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_user_mgt_available,
            'is_role_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_role_mgt_available,
            'is_holiday_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_holiday_mgt_available,
            'is_access_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_access_mgt_available,
            'is_base_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_base_mgt_available,
            'is_lock_kintai_operation_available' => $request->is_lock_kintai_operation_available,
            'is_all_kintai_operation_available' => $request->is_all_kintai_operation_available,
            'is_short_time_info_available' => $request->is_short_time_info_available,
            'is_all_base_operation_available' => $request->is_all_base_operation_available,
            'is_add_rest_time_disp_available' => $request->is_add_rest_time_disp_available,
            'is_temporary_company_mgt_available' => $request->is_system_mgt_func_available == 0 ? 0 : $request->is_temporary_company_mgt_available,
            'is_special_woriking_time_disp_available' => $request->is_special_woriking_time_disp_available == 0 ? 0 : $request->is_special_woriking_time_disp_available,
        ]);
        return;
    }
}