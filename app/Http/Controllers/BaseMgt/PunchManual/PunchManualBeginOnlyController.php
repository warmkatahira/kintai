<?php

namespace App\Http\Controllers\BaseMgt\PunchManual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Punch\PunchManualBeginOnlyService;
use App\Services\Punch\PunchBeginService;
use App\Http\Requests\PunchManualBeginOnlyRequest;
use App\Models\Employee;

class PunchManualBeginOnlyController extends Controller
{
    public function index()
    {
        // 自拠点の従業員を取得
        $employees = Employee::getSpecifyBase(Auth::user()->base_id)->get();
        return view('punch.manual_begin_only.index')->with([
            'employees' => $employees,
        ]);
    }

    public function enter(PunchManualBeginOnlyRequest $request)
    {
        // インスタンス化
        $PunchManualBeginOnlyService = new PunchManualBeginOnlyService;
        // 勤怠テーブルにレコードを追加
        $kintai = $PunchManualBeginOnlyService->createKintai($request);
        return redirect()->route('top.index')->with([
            'alert_type' => 'success',
            'alert_message' => '手動打刻(出勤のみ)が完了しました。'.$kintai->employee->employee_last_name.$kintai->employee->employee_first_name.'('.$request->work_day.')',
        ]);
    }
}
