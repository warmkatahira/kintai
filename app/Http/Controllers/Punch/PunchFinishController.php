<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Base;
use App\Models\Kintai;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Employee;
use App\Services\Punch\PunchFinishInputService;
use App\Services\Punch\PunchFinishEnterService;

class PunchFinishController extends Controller
{
    public function index()
    {
        // インスタンス化
        $PunchFinishInputService = new PunchFinishInputService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 退勤打刻対象者を取得
        $employees = $PunchFinishInputService->getPunchFinishTargetEmployee($nowDate);
        return view('punch.finish.index')->with([
            'employees' => $employees,
        ]);
    }

    public function input(Request $request)
    {
        // インスタンス化
        $PunchFinishInputService = new PunchFinishInputService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 退勤時間をフォーマット
        $finish_time = $PunchFinishInputService->formatFinishTime($nowDate);
        // 勤怠情報を取得
        $kintai = Kintai::getSpecify($request->punch_id)->first();
        // 退勤時間調整を算出
        $finish_time_adj = $PunchFinishInputService->getFinishTimeAdj($nowDate);
        // 出退勤時間から、取得可能な休憩時間を算出
        $rest_time = $PunchFinishInputService->getRestTimeForBeginFinish($kintai->begin_time_adj, $finish_time_adj);
        // 外出戻り時間から、取得可能な休憩時間を算出(外出戻り時間がある場合のみ)
        if($kintai->out_return_time != 0){
            $rest_time = $PunchFinishInputService->getRestTimeForOutReturn($rest_time, $kintai->out_time_adj, $kintai->return_time_adj);
        }
        // 休憩未取得回数の情報を取得
        $no_rest_times = $PunchFinishInputService->getNoRestTime($kintai->employee_id, $rest_time);
        // 休憩取得回数の情報を取得
        $rest_times = $PunchFinishInputService->getRestTime($kintai->employee_id, $rest_time);
        // 稼働時間を算出
        $working_time = $PunchFinishInputService->getWorkingTime($kintai->begin_time_adj, $finish_time_adj, $rest_time, $kintai->out_return_time, $request->add_rest_time);
        // 自拠点の荷主情報を取得
        $customers = Customer::getSpecifyBase(Auth::user()->base_id)->get();
        $customer_groups = CustomerGroup::getSpecifyBase(Auth::user()->base_id)->has('customers')->get();
        // 荷主から応援タブの情報を取得
        $support_bases = $PunchFinishInputService->getSupportedBases();
        // 追加休憩取得時間を取得
        $add_rest_times = $PunchFinishInputService->getAddRestTime($kintai->employee_id);
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        // 従業員情報を取得
        $employee = Employee::getSpecify($kintai->employee_id)->first();
        // 拠点情報を取得（休憩関連選択モードを取得するため）
        $base = Base::getSpecify(Auth::user()->base_id)->first();
        return view('punch.finish.input')->with([
            'kintai' => $kintai,
            'finish_time' => $finish_time,
            'finish_time_adj' => $finish_time_adj,
            'working_time' => $working_time,
            'rest_time' => $rest_time,
            'no_rest_times' => $no_rest_times,
            'rest_times' => $rest_times,
            'customers' => $customers,
            'customer_groups' => $customer_groups,
            'support_bases' => $support_bases,
            'add_rest_times' => $add_rest_times,
            'add_rest_available' => $add_rest_available,
            'employee' => $employee,
            'base' => $base,
        ]);
    }

    public function enter(Request $request)
    {
        // インスタンス化
        $PunchFinishEnterService = new PunchFinishEnterService;
        // 勤怠情報を取得
        $kintai = Kintai::getSpecify($request->kintai_id)->first();
        // 既に退勤済みの勤怠であれば中断
        if(!is_null($kintai->finish_time)){
            return redirect()->route('top.index')->with([
                'alert_type' => 'error',
                'alert_message' => '退勤されている勤怠です。'.$kintai->employee->employee_last_name.$kintai->employee->employee_first_name.'('.$kintai->work_day.')',
            ]);
        }
        // 残業時間を算出
        $over_time = $PunchFinishEnterService->getOverTime($kintai, $request->working_time);
        // 勤怠概要を更新
        $PunchFinishEnterService->updatePunchFinishForKintai($request, $over_time);
        // 勤怠詳細を追加
        $PunchFinishEnterService->createPunchFinishForKintaiDetail($request->kintai_id, $request->working_time_input);
        // 労働可能時間を算出
        $hours_data = $PunchFinishEnterService->getWorkableTimes($kintai);
        session()->flash('punch_type', '退勤');
        session()->flash('employee_name', $kintai->employee->employee_last_name.$kintai->employee->employee_first_name);
        session()->flash('message', '1日お疲れ様でした');
        session()->flash('monthly_workable_time', $hours_data['monthly_workable_time']);
        session()->flash('workable_times', $hours_data['monthly_workable_time'] == 0 ? 0 : $hours_data['workable_times']);
        session()->flash('total_month_working_time', $hours_data['total_month_working_time']);
        session()->flash('total_over_time', $hours_data['total_over_time']);
        return redirect()->route('punch_finish.index');
    }
}
