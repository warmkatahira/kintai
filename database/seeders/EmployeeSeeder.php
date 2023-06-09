<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'employee_no' => '0001',
            'base_id' => '00_Honsha',
            'employee_last_name' => '社員',
            'employee_first_name' => 'A',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0002',
            'base_id' => '00_Honsha',
            'employee_last_name' => '社員',
            'employee_first_name' => 'B',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0003',
            'base_id' => '00_Honsha',
            'employee_last_name' => '社員',
            'employee_first_name' => 'C',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1111',
            'base_id' => '00_Honsha',
            'employee_last_name' => 'パート',
            'employee_first_name' => 'A',
            'employee_category_id' => 10,
            'monthly_workable_time' => 100,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1112',
            'base_id' => '00_Honsha',
            'employee_last_name' => 'パート',
            'employee_first_name' => 'B',
            'employee_category_id' => 10,
            'monthly_workable_time' => 80,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1113',
            'base_id' => '00_Honsha',
            'employee_last_name' => 'パート',
            'employee_first_name' => 'C',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '1114',
            'base_id' => '00_Honsha',
            'employee_last_name' => 'パート',
            'employee_first_name' => 'D',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0009',
            'base_id' => '01_1st',
            'employee_last_name' => '社員',
            'employee_first_name' => 'T',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0012',
            'base_id' => '01_1st',
            'employee_last_name' => '社員',
            'employee_first_name' => 'N',
            'employee_category_id' => 1,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '0016',
            'base_id' => '01_1st',
            'employee_last_name' => '契約社員',
            'employee_first_name' => 'P',
            'employee_category_id' => 2,
            'monthly_workable_time' => 0,
            'over_time_start' => 0,
        ]);
        Employee::create([
            'employee_no' => '3333',
            'base_id' => '01_1st',
            'employee_last_name' => 'パート',
            'employee_first_name' => 'Y',
            'employee_category_id' => 10,
            'monthly_workable_time' => 0,
            'over_time_start' => 5.00,
        ]);
    }
}
