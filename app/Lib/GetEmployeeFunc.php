<?php

namespace App\Lib;

use App\Models\EmployeeCategory;

class GetEmployeeFunc
{
    // 従業員区分IDから従業員名を取得
    public static function CategoryName($employee_category_id)
    {
        $employee_category = EmployeeCategory::where('employee_category_id', $employee_category_id)->first();
        return $employee_category->employee_category_name;
    }
}