<?php

namespace App\Http\Controllers\BaseMgt\PunchManual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Punch\PunchManualService;
use App\Services\Punch\PunchFinishInputService;
use App\Services\Punch\PunchUpdateService;
use App\Services\Punch\PunchFinishEnterService;
use App\Http\Requests\PunchManualRequest;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Kintai;
use App\Models\Base;

class PunchManualController extends Controller
{
    public function index()
    {
        // インスタンス化
        $PunchManualService = new PunchManualService;
        // 自拠点の従業員を取得
        $employees = Employee::getSpecifyBase(Auth::user()->base_id)
                        ->where('is_available', 1)
                        ->orderBy('employee_category_id', 'asc')
                        ->orderBy('employee_no', 'asc')
                        ->get();
        return view('punch.manual.index')->with([
            'employees' => $employees,
        ]);
    }

    public function input(PunchManualRequest $request)
    {
        // インスタンス化
        $PunchManualService = new PunchManualService;
        $PunchFinishInputService = new PunchFinishInputService;
        $PunchUpdateService = new PunchUpdateService;
        // 打刻しようとしている条件のレコードを取得
        $kintai = Kintai::checkKintaiRecordCreateAvailable($request->work_day, $request->employee_id);
        // 既に存在する勤怠であれば中断
        if(isset($kintai)){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '打刻されている勤怠です。'.$kintai->employee->employee_last_name.$kintai->employee->employee_first_name.'('.$request->work_day.')',
            ]);
        }
        // 外出戻り関連の時間を取得
        $out_return_time = $PunchUpdateService->getOutReturnTime($request);
        // 出勤・退勤勤時間を取得
        $begin_finish_time = $PunchUpdateService->getBeginFinishTime($request, true);
        // 出退勤時間から、取得可能な休憩時間を算出
        $rest_time = $PunchFinishInputService->getRestTimeForBeginFinish($begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj']);
        // デフォルト休憩取得時間の計算で使用する調整時間を取得（2025/06/01からの件で追加）
        $default_rest_time_adjustment_time = $PunchFinishInputService->getDefaultRestTimeAdjustmentTime($request->employee_id, $begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj']);
        // 外出戻り時間から、取得可能な休憩時間を算出(外出戻り時間がある場合のみ)
        if($out_return_time['out_return_time'] != 0){
            $rest_time = $PunchFinishInputService->getRestTimeForOutReturn($rest_time, $out_return_time['out_time_adj'], $out_return_time['return_time_adj']);
        }
        // 稼働時間を算出
        $working_time = $PunchFinishInputService->getWorkingTime($begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj'], $out_return_time['out_return_time'], 0);
        // 稼働時間から法令で取得するべき休憩時間を取得
        $law_rest_time = $PunchFinishInputService->getLawRestTime($working_time, $out_return_time['out_return_time']);
        // 休憩未取得回数の情報を取得
        $no_rest_times = $PunchFinishInputService->getNoRestTime($request->employee_id, $rest_time);
        // 休憩取得回数の情報を取得
        $rest_times = $PunchFinishInputService->getRestTime($request->employee_id, max($rest_time, $law_rest_time));
        // 各種情報をセッションに格納
        $PunchUpdateService->setSessionKintaiModifyInfo($out_return_time, $begin_finish_time, $rest_time, $no_rest_times, $working_time, $request->punch_begin_type, $rest_times);
        // 自拠点の荷主情報を取得
        $customers = Customer::getSpecifyBase(Auth::user()->base_id)->get();
        $customer_groups = CustomerGroup::getSpecifyBase(Auth::user()->base_id)->has('customers')->get();
        // 荷主から応援タブの情報を取得
        $support_bases = $PunchFinishInputService->getSupportedBases();
        // 追加休憩取得時間を取得
        $add_rest_times = $PunchFinishInputService->getAddRestTime($request->employee_id);
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        // 従業員情報を取得
        $employee = Employee::getSpecify($request->employee_id)->first();
        // 拠点情報を取得（休憩関連選択モードを取得するため）
        $base = Base::getSpecify(Auth::user()->base_id)->first();
        // デフォルト休憩取得時間を取得（通常の休憩時間と法令の休憩時間で大きい方を適用）
        $default_rest_time = max($rest_time - $default_rest_time_adjustment_time, $law_rest_time);
        return view('punch.manual.input')->with([
            'customers' => $customers,
            'customer_groups' => $customer_groups,
            'employee' => $employee,
            'work_day' => $request->work_day,
            'support_bases' => $support_bases,
            'add_rest_times' => $add_rest_times,
            'add_rest_available' => $add_rest_available,
            'base' => $base,
            'default_rest_time' => $default_rest_time,
            'law_rest_time' => $law_rest_time,
        ]);
    }

    public function enter(Request $request)
    {
        // インスタンス化
        $PunchUpdateService = new PunchUpdateService;
        $PunchFinishEnterService = new PunchFinishEnterService;
        $PunchManualService = new PunchManualService;
        // 概要を追加
        $kintai = $PunchManualService->createKintai($request);
        // 残業時間を算出・取得
        $over_time = $PunchFinishEnterService->getOverTime($kintai, $request->working_time);
        // 残業時間を更新
        $PunchManualService->updateOverTime($kintai->kintai_id, $over_time);
        // 深夜関連の時間を算出・取得
        $late_night = $PunchFinishEnterService->getLateNight($kintai->finish_time_adj, $request->working_time, $over_time);
        // 深夜関連の時間を更新
        $PunchManualService->updateLateNight($kintai->kintai_id, $late_night);
        // 勤怠詳細を追加
        $PunchFinishEnterService->createPunchFinishForKintaiDetail($kintai->kintai_id, $request->working_time_input);
        // セッションをクリア
        $PunchUpdateService->removeSessionKintaiModifyInfo();
        return redirect()->route('top.index')->with([
            'alert_type' => 'success',
            'alert_message' => '手動打刻が完了しました。'.$kintai->employee->employee_last_name.$kintai->employee->employee_first_name.'('.$request->work_day.')',
        ]);
    }
}
