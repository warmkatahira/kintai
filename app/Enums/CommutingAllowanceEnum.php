<?php

namespace App\Enums;

class CommutingAllowanceEnum
{
    // 交通費の支給形態
    const NONE          = 0;    // なし
    const MONTHLY_FIXED = 1;    // 1ヶ月固定
    const WORKING_DAYS  = 2;    // 出勤日数×単価
    const COMMENT_DAYS  = 3;    // コメントに交通費の記載がある日数×単価
    const TYPE_LIST = [
        self::NONE          => 'なし',
        self::MONTHLY_FIXED => '1ヶ月固定',
        self::WORKING_DAYS  => '出勤日数×単価',
        self::COMMENT_DAYS  => 'コメント記載日数×単価',
    ];
    // コメント記載日数×単価の場合に、勤怠コメントから探すキーワード(いずれかを含む日を数える)
    const COMMENT_KEYWORDS = ['交通費', '自走'];

    // DBの設定値から文字列を取得
    public static function get_jp($key): string
    {
        return self::TYPE_LIST[$key] ?? '';
    }
}
