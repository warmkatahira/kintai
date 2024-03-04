<?php

namespace App\Services\HolidayMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Holiday;

class HolidayDownloadService
{
    public function getDownloadHoliday()
    {
        // ダウンロードする休日マスタを取得
        $holidays = Holiday::getAll()->get();
        // データが空ならヘッダーだけを出力
        if ($holidays->isEmpty()) {
            $download_data[] = [
                '休日' => '',
                '備考' => '',
                '国民の祝日' => '',
            ];
            return $download_data;
        }
        $download_data = [];
        // 対象の分だけループ処理
        foreach($holidays as $holiday){
            $param = [
                '休日' => $holiday->holiday,
                '備考' => $holiday->holiday_note,
                '国民の祝日' => $holiday->is_national_holiday,
            ];
            $download_data[] = $param;
        }
        return $download_data;
    }
}