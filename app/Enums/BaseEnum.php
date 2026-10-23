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
    // IP制限時の連絡で選択する場所を拠点ごとに定義
    const IP_SHARE_LOCATION_LIST = [
        '01_1st' => ['A棟', 'B棟'],
        '02_2nd' => ['現場', '事務所'],
    ];
}
