<?php

namespace App\Enums;

class BaseEnum
{
    // 休憩関連選択モードの値を定義
    const REST          = 'rest';
    const NO_REST       = 'no_rest';
    const REST_JP       = '休憩取得';
    const NO_REST_JP    = '休憩未取得';
    // 休憩関連選択モードの値を配列にセット
    const REST_RELATED_SELECT_MODE_LIST = [
        self::REST => self::REST_JP,
        self::NO_REST => self::NO_REST_JP,
    ];
}
