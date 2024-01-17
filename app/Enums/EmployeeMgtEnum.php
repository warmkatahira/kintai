<?php

namespace App\Enums;

class EmployeeMgtEnum
{
    // 並び順条件の値を定義
    const SORT_ORDER_BASE = 'SORT_ORDER_BASE';
    const SORT_ORDER_EMPLOYEE_NO = 'SORT_ORDER_EMPLOYEE_NO';
    const SORT_ORDER_CONDITIONS = [
        self::SORT_ORDER_BASE => '拠点順',
        self::SORT_ORDER_EMPLOYEE_NO => '従業員番号順',
    ];
}
