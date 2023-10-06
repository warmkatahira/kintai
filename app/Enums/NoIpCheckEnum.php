<?php

namespace App\Enums;

class NoIpCheckEnum
{
    const DEACTIVE = 0;
    const ACTIVE = 1;
    const DEACTIVE_JP = '無効';
    const ACTIVE_JP = '有効';
    const STATUS_LIST = [
        self::DEACTIVE => self::DEACTIVE_JP,
        self::ACTIVE => self::ACTIVE_JP,
    ];
    const PULLDOWN_LIST = [
        [
            'no_ip_check' => self::DEACTIVE,
            'status_name' => self::DEACTIVE_JP,
        ],
        [
            'no_ip_check' => self::ACTIVE,
            'status_name' => self::ACTIVE_JP,
        ],
    ];
    // DBの設定値から文字列を取得
    public static function get_jp($key): string
    {
        return self::STATUS_LIST[$key];
    }
}
