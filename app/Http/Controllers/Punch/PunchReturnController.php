<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kintai;
use App\Services\Punch\PunchReturnService;

class PunchReturnController extends Controller
{
    public function index()
    {
        // インスタンス化
        $PunchReturnService = new PunchReturnService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 戻り打刻対象者を取得
        $employees = $PunchReturnService->getPunchReturnTargetEmployee($nowDate);
        // 時間が12:00 - 12:44の間であれば、メッセージを格納
        $message = $PunchReturnService->setMessage($nowDate);
        return view('punch.return.index')->with([
            'employees' => $employees,
            'message' => $message,
        ]);
    }

    public function enter(Request $request)
    {
        // インスタンス化
        $PunchReturnService = new PunchReturnService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 戻り時間がお昼休憩に重なっている場合の処理かつ、no(お昼休憩をとる)の場合
        if(isset($request->lunch_break_check_select) && $request->lunch_break_check_select == 'no'){
            // 時間が12:00:00〜12:44:00の間かどうかを判定
            $start = $nowDate->copy()->setTime(12, 00, 0);
            $end = $nowDate->copy()->setTime(12, 44, 0);
            // 今の時間で確認した時に重なっている場合
            if($nowDate->between($start, $end)){
                // 戻り時間を13:00に調整(12:59にしないと13:00にならない)
                $nowDate = $nowDate->copy()->setTime(12, 59, 0);
            }
        }
        // 勤怠情報を取得
        $kintai = Kintai::getSpecify($request->punch_id)->first();
        // 勤怠テーブルに戻り情報を更新
        $PunchReturnService->updatePunchReturnForKintai($kintai->kintai_id, $nowDate, $kintai->out_time_adj);
        session()->flash('punch_type', '戻り');
        session()->flash('employee_name', $kintai->employee->employee_last_name.$kintai->employee->employee_first_name);
        session()->flash('message', '');
        return redirect()->route('punch.index');
    }
}
