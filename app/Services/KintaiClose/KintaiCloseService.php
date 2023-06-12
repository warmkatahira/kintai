<?php

namespace App\Services\KintaiClose;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kintai;
use App\Models\KintaiClose;

class KintaiCloseService
{
    // ロックがかかっていない自拠点の勤怠の年月を取得
    public function getNotCloseKintai()
    {
        return Kintai::whereNull('locked_at')
                        ->whereHas('employee.base', function ($query) {
                            $query->where('base_id', Auth::user()->base_id);
                        })
                        ->select(DB::raw("DATE_FORMAT(work_day, '%Y-%m') as date"))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
    }

    // 指定した年月の勤怠で拠点確認が未実施の勤怠数を取得
    public function checkKintai($start_end_of_month)
    {
        return Kintai::whereDate('work_day', '>=', $start_end_of_month['start'])
                    ->whereDate('work_day', '<=', $start_end_of_month['end'])
                    ->whereHas('employee.base', function ($query) {
                        $query->where('base_id', Auth::user()->base_id);
                    })
                    ->whereNull('base_checked_at')
                    ->count();
    }

    // 勤怠提出テーブルを追加
    public function createKintaiClose($close_date)
    {
        KintaiClose::create([
            'close_date' => $close_date,
            'base_id' => Auth::user()->base_id,
        ]);
        return;
    }

    // locked_atを更新
    public function updateLockedAt($nowDate, $start_end_of_month)
    {
        Kintai::whereDate('work_day', '>=', $start_end_of_month['start'])
                    ->whereDate('work_day', '<=', $start_end_of_month['end'])
                    ->whereHas('employee.base', function ($query) {
                        $query->where('base_id', Auth::user()->base_id);
                    })
                    ->update([
                        'locked_at' => $nowDate,
                    ]
        );
        return;
    }
}