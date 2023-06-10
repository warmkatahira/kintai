<?php

namespace App\Enums;

class RoleEnum
{
    const DEACTIVE = 0;
    const ACTIVE = 1;
    const DEACTIVE_JP = '無効';
    const ACTIVE_JP = '有効';
    const STATUS_LIST = [
        self::DEACTIVE => self::DEACTIVE_JP,
        self::ACTIVE => self::ACTIVE_JP,
    ];
    const CLASS_LIST = [
        self::DEACTIVE => 'bg-gray-200',
        self::ACTIVE => 'bg-sky-200',
    ];
    const PULLDOWN_LIST = [
        [
            'role_value' => self::DEACTIVE,
            'role_text' => self::DEACTIVE_JP,
        ],
        [
            'role_value' => self::ACTIVE,
            'role_text' => self::ACTIVE_JP,
        ],
    ];
    // DBの設定値から文字列を取得
    public static function get_jp($key): string
    {
        return self::STATUS_LIST[$key];
    }
    // DBの設定値からクラス名を取得
    public static function get_class($key): string
    {
        return self::CLASS_LIST[$key];
    }
}
