<?php

namespace App\Services\Punch;

use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Kintai;
use App\Models\Base;
use App\Models\KintaiDetail;
use App\Models\Holiday;
use App\Models\Employee;
use App\Enums\EmployeeCategoryEnum;

class PunchFinishEnterService
{
    // 残業時間を算出・取得
    public function getOverTime($kintai, $working_time)
    {
        // この稼働時間を超えたら残業時間がつき始めるという値と稼働時間から引く時間を取得
        $setting = $this->getOverTimeStart($kintai->employee->employee_category->employee_category_id, $kintai->employee->over_time_start);
        // 初期値として0を設定
        $over_time = 0;
        // 稼働時間が残業開始時間を超えている場合
        if($working_time >= $setting['over_time_start']){
            // 残業時間を取得
            $over_time = $working_time - $setting['over_time_calc'];
        }
        return $over_time;
    }

    // この稼働時間を超えたら残業時間がつき始めるという値と稼働時間から引く時間を取得
    // over_time_start  => この時間を超えたら残業がつき始める値
    // over_time_calc   => 稼働時間から引いて残業時間を算出する際に使用する値
    public function getOverTimeStart($employee_category_id, $over_time_start)
    {
        // 残業開始時間設定が0より大きければ、設定を優先する
        if($over_time_start > 0){
            $over_time_start = $over_time_start;
            $over_time_calc = $over_time_start - 0.25;
        }
        if($over_time_start == 0){
            // 社員の場合
            if($employee_category_id <= EmployeeCategoryEnum::CONTRACT_EMPLOYEE){
                $over_time_start = 8.0;
                $over_time_calc = 7.5;
            }
            // パートの場合
            if($employee_category_id == EmployeeCategoryEnum::PART_TIME_EMPLOYEE){
                $over_time_start = 8.0;
                $over_time_calc = 8.0;
            }
        }
        return compact('over_time_start', 'over_time_calc');
    }

    // 深夜関連の時間を算出・取得
    public function getLateNight($finish_time_adj, $working_time, $over_time)
    {
        // 初期値として0を設定
        $late_night_over_time = 0;
        $late_night_working_time = 0;
        // 退勤時間が22:15以降の場合
        if($finish_time_adj >= '22:15'){
            // 残業が発生している場合
            if($over_time > 0){
                // 深夜残業時間を取得(22:00から退勤時間までの差分が深夜残業時間になる)
                $late_night_over_time = CarbonImmutable::createFromFormat('H:i:s', '22:00:00')->diffInMinutes(CarbonImmutable::createFromFormat('H:i:s', $finish_time_adj)) / 60;
            }
            // 残業が発生していない場合
            if($over_time == 0){
                // 深夜稼働時間を取得(22:00から退勤時間までの差分が深夜稼働時間になる)
                $late_night_working_time = CarbonImmutable::createFromFormat('H:i:s', '22:00:00')->diffInMinutes(CarbonImmutable::createFromFormat('H:i:s', $finish_time_adj)) / 60;
            }
        }
        return compact('late_night_over_time', 'late_night_working_time');
    }

    // 退勤情報を勤怠テーブルに更新
    public function updatePunchFinishForKintai($request, $over_time, $late_night)
    {
        Kintai::where('kintai_id', $request->kintai_id)->update([
            'finish_time' => $request->finish_time,
            'finish_time_adj' => $request->finish_time_adj,
            'rest_time' => $request->rest_time,
            'no_rest_time' => $request->has('no_rest_time') ? $request->no_rest_time : $request->org_rest_time - $request->rest_time_select,
            'add_rest_time' => isset($request->add_rest_time) ? $request->add_rest_time : 0,
            'working_time' => $request->working_time * 60, // 0.25単位から分単位に変換
            'over_time' => $over_time * 60, // 0.25単位から分単位に変換
            'late_night_over_time' => $late_night['late_night_over_time'] * 60,
            'late_night_working_time' => $late_night['late_night_working_time'] * 60,
            'is_chief_approvaled' => $request->is_chief_approvaled,
            'is_law_violated' => $request->is_law_violated,
        ]);
        return;
    }

    // 荷主稼働時間の情報を勤怠詳細テーブルに追加
    public function createPunchFinishForKintaiDetail($kintai_id, $working_time_input)
    {
        // 荷主稼働時間の要素分だけループ処理
        foreach($working_time_input as $key => $value){
            KintaiDetail::create([
                'kintai_detail_id' => $kintai_id.'-'.$key,
                'kintai_id' => $kintai_id,
                'customer_id' => $key,
                'customer_working_time' => $value * 60, // 0.25単位から分単位に変換
                'is_supported' => Base::getSpecify($key)->exists(), // basesテーブルにあるか確認して1か0をセット
            ]);
        }
        return;
    }

    public function getWorkableTimes($kintai)
    {
        // 月間稼働可能時間設定を取得
        $employee = Employee::getSpecify($kintai->employee_id)->first();
        $monthly_workable_time = $employee->monthly_workable_time;
        // 月初の日付を取得
        $start_day = CarbonImmutable::now()->startOfMonth()->toDateString();
        // 月末の日付を取得
        $end_day = CarbonImmutable::now()->endOfMonth()->toDateString();
        // 当月の稼働時間を算出(0.25単位に変換している)
        $total_month_working_time = (Kintai::where('employee_id', $kintai->employee_id)
                                    ->whereBetween('work_day', [$start_day, $end_day])
                                    ->sum('working_time')) / 60;
        // 当月の残業時間を算出(0.25単位に変換している)
        $total_over_time = (Kintai::where('employee_id', $kintai->employee_id)
                                    ->whereBetween('work_day', [$start_day, $end_day])
                                    ->sum('over_time')) / 60;
        // 定総労働時間 - 当月の稼働時間で労働可能時間を算出
        $workable_times = $monthly_workable_time - $total_month_working_time;
        return compact('monthly_workable_time', 'workable_times', 'total_month_working_time', 'total_over_time');
    }
}