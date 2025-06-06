<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Base;
use App\Models\Kintai;
use App\Models\KintaiDetail;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Employee;
use App\Services\Punch\PunchUpdateService;
use App\Services\Punch\PunchFinishInputService;
use App\Services\Punch\PunchFinishEnterService;
use App\Http\Requests\PunchUpdateRequest;

class PunchUpdateController extends Controller
{
    public function __construct()
    {
        // ミドルウェアを適用するメソッドを指定
        $this->middleware(['FindByKintai', 'KintaiOperationAllAvailable'])->only(['index']);
    }

    public function index(Request $request)
    {
        // サービスクラスを定義
        $PunchUpdateService = new PunchUpdateService;
        // 勤怠IDをセッションに格納
        $PunchUpdateService->setSessionKintaiId($request->kintai_id);
        // 勤怠概要を取得
        $kintai = Kintai::getSpecify($request->kintai_id)->first();
        return view('punch.update.index')->with([
            'kintai' => $kintai,
        ]);
    }

    // 出退勤・外出戻り時間をバリデーションしている
    public function input(PunchUpdateRequest $request)
    {
        // サービスクラスを定義
        $PunchUpdateService = new PunchUpdateService;
        $PunchFinishInputService = new PunchFinishInputService;
        // 勤怠情報を取得
        $kintai = Kintai::getSpecify(session('kintai_id'))->first();
        $kintai_details = $PunchUpdateService->getCustomerWorkingTime(session('kintai_id'));
        // 外出戻り関連の時間を取得
        $out_return_time = $PunchUpdateService->getOutReturnTime($request);
        // 出勤・退勤勤時間を取得
        $begin_finish_time = $PunchUpdateService->getBeginFinishTime($request, true);
        // 出退勤時間から、取得可能な休憩時間を算出
        $rest_time = $PunchFinishInputService->getRestTimeForBeginFinish($begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj']);
        // デフォルト休憩取得時間の計算で使用する調整時間を取得（2025/06/01からの件で追加）
        $default_rest_time_adjustment_time = $PunchFinishInputService->getDefaultRestTimeAdjustmentTime($kintai->employee_id, $begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj']);
        // 外出戻り時間から、取得可能な休憩時間を算出(外出戻り時間がある場合のみ)
        if($out_return_time['out_return_time'] != 0){
            $rest_time = $PunchFinishInputService->getRestTimeForOutReturn($rest_time, $out_return_time['out_time_adj'], $out_return_time['return_time_adj']);
        }
        // 稼働時間を算出
        $working_time = $PunchFinishInputService->getWorkingTime($begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj'], $out_return_time['out_return_time'], $kintai->add_rest_time);
        // 稼働時間から法令で取得するべき休憩時間を取得
        $law_rest_time = $PunchFinishInputService->getLawRestTime($working_time, $out_return_time['out_return_time']);
        // 休憩未取得回数の情報を取得
        $no_rest_times = $PunchFinishInputService->getNoRestTime($kintai->employee_id, $rest_time);
        // 休憩取得回数の情報を取得
        $rest_times = $PunchFinishInputService->getRestTime($kintai->employee_id, max($rest_time, $law_rest_time));
        // 各種情報をセッションに格納
        $PunchUpdateService->setSessionKintaiModifyInfo($out_return_time, $begin_finish_time, $rest_time, $no_rest_times, $working_time, $request->punch_begin_type, $rest_times);
        // 荷主から応援タブの情報を取得
        $support_bases = $PunchFinishInputService->getSupportedBases();
        // 自拠点の荷主情報を取得
        $customers = Customer::getSpecifyBase(Auth::user()->base_id)->get();
        $customer_groups = CustomerGroup::getSpecifyBase(Auth::user()->base_id)->has('customers')->get();
        // 追加休憩取得時間を取得
        $add_rest_times = $PunchFinishInputService->getAddRestTime($kintai->employee_id);
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        // 従業員情報を取得
        $employee = Employee::getSpecify($kintai->employee_id)->first();
        // 拠点情報を取得（休憩関連選択モードを取得するため）
        $base = Base::getSpecify(Auth::user()->base_id)->first();
        // デフォルト休憩取得時間を取得（通常の休憩時間と法令の休憩時間で大きい方を適用）
        $default_rest_time = max($rest_time - $default_rest_time_adjustment_time, $law_rest_time);
        return view('punch.update.input')->with([
            'kintai' => $kintai,
            'kintai_details' => $kintai_details,
            'customers' => $customers,
            'customer_groups' => $customer_groups,
            'support_bases' => $support_bases,
            'add_rest_times' => $add_rest_times,
            'add_rest_available' => $add_rest_available,
            'employee' => $employee,
            'base' => $base,
            'default_rest_time' => $default_rest_time,
            'law_rest_time' => $law_rest_time,
        ]);
    }

    public function enter(Request $request)
    {
        // サービスクラスを定義
        $PunchUpdateService = new PunchUpdateService;
        $PunchFinishEnterService = new PunchFinishEnterService;
        // 勤怠情報を取得
        $kintai = Kintai::getSpecify(session('kintai_id'))->first();
        // 残業時間を算出・取得
        $over_time = $PunchFinishEnterService->getOverTime($kintai, $request->working_time);
        // 深夜関連の時間を算出・取得
        $late_night = $PunchFinishEnterService->getLateNight(session('finish_time_adj'), $request->working_time, $over_time);
        // 勤怠概要を更新
        $PunchUpdateService->updatePunchModifyKintai($request, $over_time, $late_night);
        // 荷主稼働時間を削除して追加
        $PunchUpdateService->addPunchModifyForKintaiDetail($request->working_time_input);
        // セッションをクリア
        $PunchUpdateService->removeSessionKintaiModifyInfo();
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '修正打刻が完了しました。'.$kintai->employee->employee_last_name.$kintai->employee->employee_first_name.'('.$kintai->work_day.')',
        ]);
    }
}
