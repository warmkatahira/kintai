<?php

namespace App\Services\Punch;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Kintai;
use App\Models\Employee;

class PunchOutService
{
    // 外出打刻対象者を取得
    public function getPunchOutTargetEmployee($nowDate)
    {
        // 当日の自拠点の退勤していないかつ外出していない勤怠を取得
        $kintais = Kintai::where('work_day', $nowDate->format('Y-m-d'))
                    ->whereNull('finish_time')
                    ->whereNull('out_time')
                    ->whereHas('employee.base', function ($query) {
                        $query->where('base_id', Auth::user()->base_id);
                    });
        // 外出打刻対象者を取得
        $employees = Employee::joinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('employees.employee_id', '=', 'KINTAIS.employee_id');
            })
            ->select('employees.employee_no', 'employees.employee_last_name', 'employees.employee_first_name', 'KINTAIS.kintai_id')
            ->orderBy('employee_no', 'asc')
            ->get();
        return $employees;
    }

    // 勤怠テーブルに外出情報を更新
    public function updatePunchOutForKintai($kintai_id, $nowDate)
    {
        // 外出時間調整を取得
        $out_time_adj = $this->getOutTimeAdj($nowDate);
        // 勤怠レコードを更新
        Kintai::where('kintai_id', $kintai_id)->update([
            'out_time' => $nowDate->format('H:i:00'),
            'out_time_adj' => $out_time_adj,
            'out_enabled' => 1,
        ]);
        return;
    }

    // 外出時間調整を算出・取得
    public function getOutTimeAdj($nowDate)
    {
        // 日時をインスタンス化
        $out_time_adj = new CarbonImmutable($nowDate);
        // 15分単位で切り捨て
        $out_time_adj = $out_time_adj->subMinutes($out_time_adj->minute % 15);
        $out_time_adj = $out_time_adj->format('H:i:00');
        return $out_time_adj;
    }
}