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
        ]);
        EmployeeCategory::create([
            'employee_category_name' => 'パート',
        ]);
    }
}
