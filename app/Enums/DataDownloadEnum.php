<?php

namespace App\Enums;

class DataDownloadEnum
{
    // ダウンロード項目を定義
    const WORKING_TIME_BY_CUSTOMER = 'WORKING_TIME_BY_CUSTOMER';
    const WORKING_TIME_BY_CUSTOMER_JP = '稼働時間(荷主別)';
    const WORKING_TIME_BY_EMPLOYEE = 'WORKING_TIME_BY_EMPLOYEE';
    const WORKING_TIME_BY_EMPLOYEE_JP = '稼働時間(従業員別)';
    // ダウンロード項目を連想配列化
    const DOWNLOAD_LIST = [
        [
            'key' => self::WORKING_TIME_BY_CUSTOMER,
            'value' => self::WORKING_TIME_BY_CUSTOMER_JP,
        ],
        [
            'key' => self::WORKING_TIME_BY_EMPLOYEE,
            'value' => self::WORKING_TIME_BY_EMPLOYEE_JP,
        ],
    ];
    // 定義してある項目であるかKeyで確認
    public static function checkKeyExists($key): bool
    {
        $keys = array_column(self::DOWNLOAD_LIST, 'key');
        return in_array($key, $keys);
    }
}
