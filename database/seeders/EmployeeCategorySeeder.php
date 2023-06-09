<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmployeeCategory;

class EmployeeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeCategory::create([
            'employee_category_name' => '正社員',
            'is_no_rest_available' => 0,
            'is_add_rest_available' => 0,
        ]);
        EmployeeCategory::create([
            'employee_category_name' => 'パート',
            'is_no_rest_available' => 1,
            'is_add_rest_available' => 1,
        ]);
    }
}
