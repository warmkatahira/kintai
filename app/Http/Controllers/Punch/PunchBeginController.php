<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Punch\PunchBeginService;
use Carbon\CarbonImmutable;
use App\Models\Kintai;

class PunchBeginController extends Controller
{
    public function index()
    {
        // インスタンス化
        $PunchBeginService = new PunchBeginService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 早出が可能か判定
        $early_work_available = $PunchBeginService->checkEarlyWorkAvailable($nowDate);
        // 早出が可能な状態の時、直近4回分の情報を取得（15分刻みで）
        $early_work_select_info = $PunchBeginService->getEarlyWorkSelectInfo($nowDate, $early_work_available);
        // 出勤打刻対象者を取得
        $employees = $PunchBeginService->getPunchBeginTargetEmployee();
        return view('punch.begin.index')->with([
            'employees' => $employees,
            'early_work_available' => $early_work_available,
            'early_work_select_info' => $early_work_select_info,
        ]);
    }

    public function enter(Request $request)
    {
        // インスタンス化
        $PunchBeginService = new PunchBeginService;
        // 今日の日付をインスタンス化
        $work_day = CarbonImmutable::today();
        // 打刻しようとしている条件のレコードを取得
        $kintai = Kintai::checkKintaiRecordCreateAvailable($work_day, $request->punch_id);
        // 既に存在する勤怠であれば中断
        if(isset($kintai)){
            return redirect()->route('top.index')->with([
                'alert_type' => 'error',
                'alert_message' => '打刻されている勤怠です。'.$kintai->employee->employee_last_name.$kintai->employee->employee_first_name.'('.$work_day->format('Y-m-d').')',
            ]);
        }
        // 勤怠テーブルにレコードを追加
        $kintai = $PunchBeginService->createKintai($request);
        session()->flash('punch_type', '出勤');
        session()->flash('employee_name', $kintai->employee->employee_last_name.''.$kintai->employee->employee_first_name);
        session()->flash('message', '本日も宜しくお願いします');
        return redirect()->back();
    }
}
