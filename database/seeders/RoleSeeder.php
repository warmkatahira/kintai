<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'role_name' => 'システム管理者',
            'is_kintai_list_func_available' => 1,
            'is_employee_list_func_available' => 1,
            'is_other_func_available' => 1,
            'is_data_export_func_available' => 1,
            'is_management_func_available' => 1,
            'is_system_mgt_func_available' => 1,
            'is_accounting_func_available' => 1,
            'is_kintai_check_available' => 1,
            'is_kintai_delete_available' => 1,
            'is_kintai_modify_available' => 1,
            'is_all_kintai_operation_available' => 1,
            'is_employee_operation_available' => 1,
        ]);
        Role::create([
            'role_name' => '打刻用',
            'is_kintai_list_func_available' => 0,
            'is_employee_list_func_available' => 0,
            'is_other_func_available' => 0,
            'is_data_export_func_available' => 0,
            'is_management_func_available' => 0,
            'is_system_mgt_func_available' => 0,
            'is_accounting_func_available' => 0,
            'is_kintai_check_available' => 0,
            'is_kintai_delete_available' => 0,
            'is_kintai_modify_available' => 0,
            'is_all_kintai_operation_available' => 0,
            'is_employee_operation_available' => 0,
        ]);
    }
}
