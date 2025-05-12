<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kintai;
use App\Services\Punch\PunchOutService;

class PunchOutController extends Controller
{
    public function index()
    {
        // サービスクラスを定義
        $PunchOutService = new PunchOutService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 外出打刻対象者を取得
        $employees = $PunchOutService->getPunchOutTargetEmployee($nowDate);
        // 時間が12:15 - 12:59の間であれば、メッセージを格納
        $message = $PunchOutService->setMessage($nowDate);
        return view('punch.out.index')->with([
            'employees' => $employees,
            'message' => $message,
        ]);
    }

    public function enter(Request $request)
    {
        // サービスクラスを定義
        $PunchOutService = new PunchOutService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 外出時間がお昼休憩に重なっている場合の処理かつ、no(働いていない)の場合
        if(isset($request->lunch_break_check_select) && $request->lunch_break_check_select == 'no'){
            // 時間が12:15:00〜12:59:00の間かどうかを判定
            $start = $nowDate->copy()->setTime(12, 15, 0);
            $end = $nowDate->copy()->setTime(12, 59, 0);
            // 今の時間で確認した時に重なっている場合
            if($nowDate->between($start, $end)){
                // 外出時間を12:00に調整
                $nowDate = $nowDate->copy()->setTime(12, 00, 0);
            }
        }
        // 勤怠情報を取得
        $kintai = Kintai::getSpecify($request->punch_id)->first();
        // 勤怠テーブルに外出情報を更新
        $PunchOutService->updatePunchOutForKintai($request->punch_id, $nowDate);
        session()->flash('punch_type', '外出');
        session()->flash('employee_name', $kintai->employee->employee_last_name.$kintai->employee->employee_first_name);
        session()->flash('message', '');
        return redirect()->route('punch.index');
    }
}
