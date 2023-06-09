<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use App\Models\Base;
use App\Models\Role;
use App\Models\Employee;
use App\Models\EmployeeCategory;

class CommonService
{
    // パラメータの月初・月末の日付を取得
    public function getStartEndOfMonth($date)
    {
        // パラメータをインスタンス化
        $date = new CarbonImmutable($date);
        // 月初と月末の日付を取得
        $start = $date->startOfMonth()->toDateString();
        $end = $date->endOfMonth()->toDateString();
        return compact('start', 'end');
    }

    // basesテーブルの情報+パラメータによって「全社」も取得する
    public function getBases($zensha_enabled, $system_common_enabled)
    {
        // 拠点情報を取得
        $bases = Base::all();
        // 配列をセット
        $base_info = [];
        // zensha_enabledがtrueなら全社をセット
        if($zensha_enabled == true){
            $base_info[0] = '全社';
        }
        // baseテーブルのレコードをセット
        foreach($bases as $base){
            // $system_common_enabledがtrueの時だけ「システム共通」を格納する
            if($system_common_enabled == true || $system_common_enabled == false && $base->base_id != 'system_common'){
                $base_info[$base->base_id] = $base->base_name;
            }
        }
        return $base_info;
    }

    // 従業員区分を取得
    public function getEmployeeCategories()
    {
        // 従業員区分を取得
        $employee_categories = EmployeeCategory::all();
        return $employee_categories;
    }

    // 指定された従業員番号の情報を取得
    public function getEmployee($employee_no)
    {
        // 選択された従業員の情報を取得
        $employee = Employee::where('employee_no', $employee_no)->first();
        $employees = Employee::where('employee_no', $employee_no)->get();
        return compact('employee', 'employees');
    }
}