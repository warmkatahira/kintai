<?php

namespace App\Services\Punch;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kintai;
use Carbon\CarbonImmutable;

class PunchManualBeginOnlyService
{
    // 勤怠テーブルにレコードを追加
    public function createKintai($request)
    {
        // 早出フラグを取得(リクエストパラメータがある = 早出となる) ※早出は1
        $is_early_worked = isset($request->punch_begin_type) ? 1 : 0;
        // 出勤時間をインスタンス化
        $begin_time = new CarbonImmutable($request->begin_time);
        // 出勤時間調整を算出・取得
        $begin_time_adj = $this->getBeginTimeAdj($begin_time, $is_early_worked);
        // 出勤日をインスタンス化
        $work_day = new CarbonImmutable($request->work_day);
        // レコードを追加
        $kintai = Kintai::create([
            'kintai_id' => $request->punch_id.'-'.$work_day->format('Ymd'),
            'employee_id' => $request->employee_id,
            'work_day' => $work_day->format('Ymd'),
            'begin_time' => $begin_time->format('H:i:00'), // 秒は00に調整している
            'begin_time_adj' => $begin_time_adj,
            'is_early_worked' => $is_early_worked,
            'is_manual_punched' => 1,
        ]);
        return $kintai;
    }

    // 出勤時間調整を算出・取得
    public function getBeginTimeAdj($begin_time, $is_early_worked)
    {
        // パラメータの日時をインスタンス化
        $begin_time_adj = new CarbonImmutable($begin_time);
        // 9時前かつ早出ではない($is_early_worked = 0)場合、09:00:00に調整する
        if($begin_time_adj->format('H:i:00') <= "09:00:00" && $is_early_worked == 0){
            $begin_time_adj = "09:00:00";
        }else{
            // 15分単位で切り上げ
            $begin_time_adj = $begin_time_adj->addMinutes(15 - $begin_time_adj->minute % 15);
            $begin_time_adj = $begin_time_adj->format('H:i:00');
        }
        return $begin_time_adj;
    }
}