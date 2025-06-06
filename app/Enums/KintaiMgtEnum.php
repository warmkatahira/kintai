<?php

namespace App\Enums;

class KintaiMgtEnum
{
    // 対象条件の値を定義
    const TARGET_NO_FINISH = 'TARGET_NO_FINISH';
    const TARGET_FINISHED = 'TARGET_FINISHED';
    const TARGET_CONDITIONS = [
        self::TARGET_NO_FINISH => '未退勤',
        self::TARGET_FINISHED => '退勤済',
    ];
    // 拠点確認条件の値を定義
    const BASE_CHECK_NO_CHECK = 'BASE_CHECK_NO_CHECK';
    const BASE_CHECK_CHECKED = 'BASE_CHECK_CHECKED';
    const BASE_CHECK_CONDITIONS = [
        self::BASE_CHECK_NO_CHECK => '未確認',
        self::BASE_CHECK_CHECKED => '確認済',
    ];
    // 所長承認条件の値を定義
    const CHIEF_APPROVALED_TRUE = 'CHIEF_APPROVALED_TRUE';
    const CHIEF_APPROVALED_CONDITIONS = [
        self::CHIEF_APPROVALED_TRUE => '○',
    ];
    // 所長承認条件の値を定義
    const LAW_VIOLATED_TRUE = 'LAW_VIOLATED_TRUE';
    const LAW_VIOLATED_CONDITIONS = [
        self::LAW_VIOLATED_TRUE => '○',
    ];
}
