<?php

namespace App\Services\Punch;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Kintai;
use App\Models\Base;
use App\Models\Employee;

class PunchFinishInputService
{
    // 退勤打刻対象者を取得
    public function getPunchFinishTargetEmployee($nowDate)
    {
        // 当日の自拠点の退勤時刻がNullかつ外出中フラグが0の勤怠を取得
        $kintais = Kintai::where('work_day', $nowDate->format('Y-m-d'))
                        ->whereNull('finish_time')
                        ->where('out_enabled', 0)
                        ->whereHas('employee.base', function ($query) {
                            $query->where('base_id', Auth::user()->base_id);
                        });
        // 退勤打刻対象者を取得
        $employees = Employee::joinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('employees.employee_id', '=', 'KINTAIS.employee_id');
            })
            ->select('employees.employee_no', 'employees.employee_last_name', 'employees.employee_first_name', 'KINTAIS.kintai_id')
            ->orderBy('employee_category_id', 'asc')
            ->orderBy('employee_no', 'asc')
            ->get();
        return $employees;
    }

    // 退勤時間をフォーマット
    public function formatFinishTime($nowDate)
    {
        // 退勤時間をフォーマット
        return $nowDate->format('H:i:00');
    }

    // 退勤時間調整を算出・取得
    public function getFinishTimeAdj($nowDate)
    {
        // 現在の日時をインスタンス化
        $finish_time_adj = new CarbonImmutable($nowDate);
        // 15分単位で切り捨て
        $finish_time_adj = $finish_time_adj->subMinutes($finish_time_adj->minute % 15);
        $finish_time_adj = $finish_time_adj->format('H:i:00');
        return $finish_time_adj;
    }

    // 出退勤時間から取得可能な休憩時間を算出
    public function getRestTimeForBeginFinish($begin_time_adj, $finish_time_adj)
    {
        // 最大休憩取得時間をセット
        $rest_time = 90;
        // 出勤時間調整が10:45よりも遅ければ午前の休憩は取れないので、休憩時間から15分マイナスする
        if($begin_time_adj >= "10:45:00"){
            $rest_time -= 15;
        }
        // 退勤時間調整が10:30よりも早ければ午前の休憩は取れないので、休憩時間から15分マイナスする
        if($finish_time_adj <= "10:30:00"){
            $rest_time -= 15;
        }
        // 出勤時間調整が12:15よりも遅ければお昼の休憩は取れないので、休憩時間から60分マイナスする
        if($begin_time_adj >= "12:15:00"){
            $rest_time -= 60;
        }
        // 退勤時間調整が12:00よりも早ければお昼の休憩は取れないので、休憩時間から60分マイナスする
        if($finish_time_adj <= "12:00:00"){
            $rest_time -= 60;
        }
        // 出勤時間調整が15:15よりも遅ければ午後の休憩は取れないので、休憩時間から15分マイナスする
        if($begin_time_adj >= "15:15:00"){
            $rest_time -= 15;
        }
        // 退勤時間調整が15:00よりも早ければ午後の休憩は取れないので、休憩時間から15分マイナスする
        if($finish_time_adj <= "15:00:00"){
            $rest_time -= 15;
        }
        // 退勤時間調整がお昼休憩中なら、休憩時間を取らないように変更。
        if($finish_time_adj >= "12:15:00" && $finish_time_adj <= "12:45:00"){
            $rest_time -= 60;
        }
        return $rest_time;
    }

    // デフォルト休憩取得時間の計算で使用する調整時間を取得（2025/06/01からの件で追加）
    public function getDefaultRestTimeAdjustmentTime($begin_time_adj, $finish_time_adj)
    {
        // デフォルト休憩取得時間調整用時間を初期化
        $default_rest_time_adjustment_time = 0;
        // 出勤時間調整が10:00～10:30の間かつ、退勤時間調整が10:45よりも遅い場合であれば午前の休憩を取らないように、15分プラスする
        if($begin_time_adj >= "10:00:00" && $begin_time_adj <= "10:30:00" && $finish_time_adj >= "10:45:00"){
            $default_rest_time_adjustment_time += 15;
        }
        // 退勤時間調整が13:00であればお昼の休憩は取らないように、60分プラスする
        if($finish_time_adj == "13:00:00"){
            $default_rest_time_adjustment_time += 60;
        }
        return $default_rest_time_adjustment_time;
    }

    // 外出戻り時間から、取得可能な休憩時間を算出
    public function getRestTimeForOutReturn($rest_time, $out_time_adj, $return_time_adj)
    {
        // 外出時間調整が10:30よりも早く、戻り時間が10:45より遅ければ午前の休憩は取れないので、休憩時間から15分マイナスする
        if($out_time_adj <= "10:30:00" && $return_time_adj >= "10:45:00"){
            $rest_time -= 15;
        }
        // 外出時間調整が15:00よりも早く、戻り時間が15:15より遅ければ午後の休憩は取れないので、休憩時間から15分マイナスする
        if($out_time_adj <= "15:00:00" && $return_time_adj >= "15:15:00"){
            $rest_time -= 15;
        }
        // 戻り時間が12:15より遅ければ昼の休憩は取れないので、休憩時間から60分マイナスする
        if($return_time_adj >= "12:15:00"){
            $rest_time -= 60;
        }
        return $rest_time;
    }

    // 休憩未取得回数の情報を取得
    public function getNoRestTime($employee_id, $rest_time)
    {
        // 従業員情報を取得
        $employee = Employee::getSpecify($employee_id)->first();
        // 変数をセット
        $no_rest_times = [];
        array_push($no_rest_times, ['minute' => 0, 'text1' => 'なし']);
        // 休憩未取得が有効であれば処理を継続
        if($employee->employee_category->is_no_rest_available == 1){
            // 休憩時間に合わせて選択肢を変動
            if($rest_time == 15){
                array_push($no_rest_times, ['minute' => 15, 'text1' => '15分']);
            }
            if($rest_time == 30){
                array_push($no_rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($no_rest_times, ['minute' => 30, 'text1' => '30分']);
            }
            if($rest_time == 60){
                array_push($no_rest_times, ['minute' => 60, 'text1' => '60分']);
            }
            if($rest_time == 75){
                array_push($no_rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($no_rest_times, ['minute' => 60, 'text1' => '60分']);
                array_push($no_rest_times, ['minute' => 75, 'text1' => '75分']);
            }
            if($rest_time == 90){
                array_push($no_rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($no_rest_times, ['minute' => 30, 'text1' => '30分']);
                array_push($no_rest_times, ['minute' => 60, 'text1' => '60分']);
                array_push($no_rest_times, ['minute' => 75, 'text1' => '75分']);
                array_push($no_rest_times, ['minute' => 90, 'text1' => '90分']);
            }
        }
        return $no_rest_times;        
    }

    // 休憩取得回数の情報を取得
    public function getRestTime($employee_id, $rest_time)
    {
        // 従業員情報を取得
        $employee = Employee::getSpecify($employee_id)->first();
        // 変数をセット
        $rest_times = [];
        array_push($rest_times, ['minute' => $rest_time, 'text1' => $rest_time . '分']);
        // 休憩未取得が有効であれば処理を継続
        if($employee->employee_category->is_no_rest_available == 1){
            // 休憩時間に合わせて選択肢を変動
            if($rest_time == 15){
                array_push($rest_times, ['minute' => 0, 'text1' => '0分']);
            }
            if($rest_time == 30){
                array_push($rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($rest_times, ['minute' => 0, 'text1' => '0分']);
            }
            if($rest_time == 45){
                array_push($rest_times, ['minute' => 30, 'text1' => '30分']);
                array_push($rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($rest_times, ['minute' => 0, 'text1' => '0分']);
            }
            if($rest_time == 60){
                array_push($rest_times, ['minute' => 45, 'text1' => '45分']);
                array_push($rest_times, ['minute' => 30, 'text1' => '30分']);
                array_push($rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($rest_times, ['minute' => 0, 'text1' => '0分']);
            }
            if($rest_time == 75){
                array_push($rest_times, ['minute' => 60, 'text1' => '60分']);
                array_push($rest_times, ['minute' => 45, 'text1' => '45分']);
                array_push($rest_times, ['minute' => 30, 'text1' => '30分']);
                array_push($rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($rest_times, ['minute' => 0, 'text1' => '0分']);
            }
            if($rest_time == 90){
                array_push($rest_times, ['minute' => 75, 'text1' => '75分']);
                array_push($rest_times, ['minute' => 60, 'text1' => '60分']);
                array_push($rest_times, ['minute' => 45, 'text1' => '45分']);
                array_push($rest_times, ['minute' => 30, 'text1' => '30分']);
                array_push($rest_times, ['minute' => 15, 'text1' => '15分']);
                array_push($rest_times, ['minute' => 0, 'text1' => '0分']);
            }
        }
        return $rest_times;        
    }

    // 稼働時間を算出
    public function getWorkingTime($begin_time_adj, $finish_time_adj, $out_return_time, $add_rest_time)
    {
        // 退勤時間調整を分数に変換
        $finish_time_adj_split = explode(":", $finish_time_adj);
        $finish_time_adj_minute = $finish_time_adj_split[0] * 60;
        $finish_time_adj_minute = $finish_time_adj_minute + $finish_time_adj_split[1];
        // 出勤時間調整を分数に変換
        $begin_time_adj_split = explode(":", $begin_time_adj);
        $begin_time_adj_minute = $begin_time_adj_split[0] * 60;
        $begin_time_adj_minute = $begin_time_adj_minute + $begin_time_adj_split[1];
        // 追加休憩時間がnullであれば、0をセット
        $add_rest_time = is_null($add_rest_time) ? 0 : $add_rest_time;
        // 労働時間を算出(退勤時間調整 - 出勤時間調整 - 外出戻り時間)
        $working_time = $finish_time_adj_minute - $begin_time_adj_minute - $out_return_time;
        return $working_time;
    }

    public function getSupportedBases()
    {
        // 拠点情報を取得
        return Base::getAll()->get();
    }

    // 追加休憩取得時間を取得
    public function getAddRestTime($employee_id)
    {
        // 従業員情報を取得
        $employee = Employee::getSpecify($employee_id)->first();
        // 変数をセット
        $add_rest_times = [];
        // 休憩未取得が有効であれば処理を継続
        if($employee->employee_category->is_add_rest_available == 1){
            array_push($add_rest_times, ['minute' => 0, 'text1' => 'なし']);
            array_push($add_rest_times, ['minute' => 15, 'text1' => '15分']);
            array_push($add_rest_times, ['minute' => 30, 'text1' => '30分']);
            array_push($add_rest_times, ['minute' => 45, 'text1' => '45分']);
            array_push($add_rest_times, ['minute' => 60, 'text1' => '60分']);
            array_push($add_rest_times, ['minute' => 75, 'text1' => '75分']);
        }
        return $add_rest_times;
    }

    // 追加休憩取得時間が有効か判定
    public function checkAddRestAvailable()
    {
        return Auth::user()->base->is_add_rest_available == 1 ? true : false;
    }

    // 稼働時間から法令で取得するべき休憩時間を取得
    public function getLawRestTime($working_time)
    {
        // A = 6.25(375分)～8.00(480分)時間の間 =>  45分
        // B = 8.25(495分)時間以上              =>  60分

        // 法令休憩取得時間を初期化
        $law_rest_time = 0;
        // 稼働時間がAに該当する場合
        if($working_time >= 375 && $working_time <= 480){
            $law_rest_time = 45;
        }
        // 稼働時間がBに該当する場合
        if($working_time >= 495){
            $law_rest_time = 60;
        }
        return $law_rest_time;
    }

}