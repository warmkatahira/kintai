<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $begin_finish_time = $PunchUpdateService->getBeginFinishTime($request);
        // 出退勤時間から、取得可能な休憩時間を算出
        $rest_time = $PunchFinishInputService->getRestTimeForBeginFinish($begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj']);
        // 外出戻り時間から、取得可能な休憩時間を算出(外出戻り時間がある場合のみ)
        if($out_return_time['out_return_time'] != 0){
            $rest_time = $PunchFinishInputService->getRestTimeForOutReturn($rest_time, $out_return_time['out_time_adj'], $out_return_time['return_time_adj']);
        }
        // 休憩未取得回数の情報を取得
        $no_rest_times = $PunchFinishInputService->getNoRestTime($kintai->employee_id, $rest_time);
        // 稼働時間を算出
        $working_time = $PunchFinishInputService->getWorkingTime($begin_finish_time['begin_time_adj'], $begin_finish_time['finish_time_adj'], $rest_time, $out_return_time['out_return_time'], $kintai->add_rest_time);
        // 各種情報をセッションに格納
        $PunchUpdateService->setSessionKintaiModifyInfo($out_return_time, $begin_finish_time, $rest_time, $no_rest_times, $working_time, $request->punch_begin_type);
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
        return view('punch.update.input')->with([
            'kintai' => $kintai,
            'kintai_details' => $kintai_details,
            'customers' => $customers,
            'customer_groups' => $customer_groups,
            'support_bases' => $support_bases,
            'add_rest_times' => $add_rest_times,
            'add_rest_available' => $add_rest_available,
            'employee' => $employee,
        ]);
    }

    public function enter(Request $request)
    {
        // サービスクラスを定義
        $PunchUpdateService = new PunchUpdateService;
        $PunchFinishEnterService = new PunchFinishEnterService;
        // 勤怠情報を取得
        $kintai = Kintai::getSpecify(session('kintai_id'))->first();
        // 残業時間を算出
        $over_time = $PunchFinishEnterService->getOverTime($kintai, $request->working_time);
        // 勤怠概要を更新
        $PunchUpdateService->updatePunchModifyKintai($request, $over_time);
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
