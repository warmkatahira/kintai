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
    // 勤怠表示範囲
    const KINTAI_DISP_SCOPE_SELF = 1;
    const KINTAI_DISP_SCOPE_BASE = 2;
    const KINTAI_DISP_SCOPE_ALL = 3;
    const KINTAI_DISP_SCOPE_SELF_JP = '自分のみ';
    const KINTAI_DISP_SCOPE_BASE_JP = '自拠点';
    const KINTAI_DISP_SCOPE_ALL_JP = '全拠点';
    const KINTAI_DISP_SCOPE_LIST = [
        self::KINTAI_DISP_SCOPE_SELF => self::KINTAI_DISP_SCOPE_SELF_JP,
        self::KINTAI_DISP_SCOPE_BASE => self::KINTAI_DISP_SCOPE_BASE_JP,
        self::KINTAI_DISP_SCOPE_ALL => self::KINTAI_DISP_SCOPE_ALL_JP,
    ];
    const KINTAI_DISP_SCOPE_CLASS_LIST = [
        self::KINTAI_DISP_SCOPE_SELF => 'bg-gray-200',
        self::KINTAI_DISP_SCOPE_BASE => 'bg-amber-100',
        self::KINTAI_DISP_SCOPE_ALL => 'bg-sky-200',
    ];
    const KINTAI_DISP_SCOPE_PULLDOWN_LIST = [
        [
            'role_value' => self::KINTAI_DISP_SCOPE_SELF,
            'role_text' => self::KINTAI_DISP_SCOPE_SELF_JP,
        ],
        [
            'role_value' => self::KINTAI_DISP_SCOPE_BASE,
            'role_text' => self::KINTAI_DISP_SCOPE_BASE_JP,
        ],
        [
            'role_value' => self::KINTAI_DISP_SCOPE_ALL,
            'role_text' => self::KINTAI_DISP_SCOPE_ALL_JP,
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
    // DBの設定値から勤怠表示範囲の文字列を取得
    public static function get_kintai_disp_scope_jp($key): string
    {
        return self::KINTAI_DISP_SCOPE_LIST[$key];
    }
    // DBの設定値から勤怠表示範囲のクラス名を取得
    public static function get_kintai_disp_scope_class($key): string
    {
        return self::KINTAI_DISP_SCOPE_CLASS_LIST[$key];
    }
}
