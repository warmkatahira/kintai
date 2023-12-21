<?php

namespace App\Services;

use Carbon\CarbonImmutable;

class CommonService
{
    // パラメータの月初・月末の日付を取得
    public function getStartEndOfMonth($date)
    {
        // パラメータをインスタンス化
        $date = new CarbonImmutable($date);
        // 月初と月末の日付を取得
        $start = $date->startOfMonth()->toDateString();
        $end = $date->endOfMonth()->toDateString();
        return compact('start', 'end');
    }

    // パラメータの月初の日付を取得
    public function getStartOfMonth($date)
    {
        // パラメータをインスタンス化
        $date = new CarbonImmutable($date);
        // 月初の日付を取得
        return $date->startOfMonth()->toDateString();
    }

    // パラメータの月末の日付を取得
    public function getEndOfMonth($date)
    {
        // パラメータをインスタンス化
        $date = new CarbonImmutable($date);
        // 月末の日付を取得
        return $date->endOfMonth()->toDateString();
    }
}