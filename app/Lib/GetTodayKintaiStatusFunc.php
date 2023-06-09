<?php

namespace App\Lib;

use App\Models\Kintai;
use Carbon\CarbonImmutable;

class GetTodayKintaiStatusFunc
{
    public static function Status($employee_id)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 社員番号と今日の日付を条件に勤怠レコードを取得
        $kintai = Kintai::where('employee_id', $employee_id)
                    ->where('work_day', $nowDate->format('Y-m-d'))
                    ->first();
        // レコードがない場合は未出勤
        if(empty($kintai)){
            $status = '未出勤';
        }
        // レコードがある場合は詳細を確認
        if(!empty($kintai)){
            $status = '出勤中';
            // 退勤時刻がNot Nullであれば退勤
            if(!empty($kintai->finish_time)){
                $status = '退勤';
            }
            // 退勤時刻がNullかつ外出時刻がNot Nullかつ戻り時刻がNullであれば外出中
            if(empty($kintai->finish_time) && !empty($kintai->out_time) && empty($kintai->return_time)){
                $status = '外出中';
            }
        }
        return $status;
    }

    public static function Color($status)
    {
        // 勤怠状況に合わせてカラーをセット
        if($status == '未出勤'){
            $color = 'bg-gray-300';
        }
        if($status == '出勤中'){
            $color = 'bg-amber-300';
        }
        if($status == '外出中'){
            $color = 'bg-rose-300';
        }
        if($status == '退勤'){
            $color = 'bg-violet-300';
        }
        return $color;
    }
}