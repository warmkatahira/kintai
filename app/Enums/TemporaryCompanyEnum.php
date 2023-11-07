<?php

namespace App\Enums;

class TemporaryCompanyEnum
{
    const WORKING_TIME = 'working_time';
    const PEOPLE = 'people';
    const WORKING_TIME_JP = '稼働時間';
    const PEOPLE_JP = '人数';
    const AMOUNT_CALC_ITEM_LIST = [
        self::WORKING_TIME => self::WORKING_TIME_JP,
        self::PEOPLE => self::PEOPLE_JP,
    ];
    // DBの設定値から文字列を取得
    public static function get_jp($key): string
    {
        return self::AMOUNT_CALC_ITEM_LIST[$key];
    }
}
