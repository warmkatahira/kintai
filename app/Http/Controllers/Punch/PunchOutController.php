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
        return view('punch.out.index')->with([
            'employees' => $employees,
        ]);
    }

    public function enter(Request $request)
    {
        // サービスクラスを定義
        $PunchOutService = new PunchOutService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
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
