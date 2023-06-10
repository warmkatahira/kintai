<?php

namespace App\Http\Controllers\KintaiMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Base;
use App\Models\Kintai;
use App\Models\KintaiDetail;
use App\Models\EmployeeCategory;
use App\Services\KintaiMgt\KintaiMgtService;
use App\Services\Punch\PunchFinishInputService;
use Carbon\CarbonImmutable;
use App\Enums\KintaiMgtEnum;

class KintaiMgtController extends Controller
{
    public function index()
    {
        // インスタンス化
        $KintaiMgtService = new KintaiMgtService;
        $PunchFinishInputService = new PunchFinishInputService;
        // 検索条件が格納されているセッションをクリア
        $KintaiMgtService->deleteSearchSession();
        // 初期条件をセット
        $KintaiMgtService->setDefaultCondition(); 
        // 検索条件と一致した勤怠を取得
        $kintais = $KintaiMgtService->getKintaiSearch(null);
        // 従業員区分情報を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // Enumに定義してある条件値を取得
        $target_conditions = KintaiMgtEnum::TARGET_CONDITIONS;
        $base_check_conditions = KintaiMgtEnum::BASE_CHECK_CONDITIONS;
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        return view('kintai_mgt.index')->with([
            'kintais' => $kintais,
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'target_conditions' => $target_conditions,
            'base_check_conditions' => $base_check_conditions,
            'add_rest_available' => $add_rest_available,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $KintaiMgtService = new KintaiMgtService;
        $PunchFinishInputService = new PunchFinishInputService;
        // 検索条件が格納されているセッションをクリア
        $KintaiMgtService->deleteSearchSession();
        // 検索条件をセッションに格納
        $KintaiMgtService->setSearchCondition($request);
        // 出勤日の条件が正しいか確認
        $error = $KintaiMgtService->checkWorkdayCondition();
        // 出勤日の条件でエラーがあれば処理を中断
        if(!is_null($error)){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $error,
            ]);
        }
        // 指定された条件の勤怠を取得
        $kintais = $KintaiMgtService->getKintaiSearch();
        // 従業員区分情報を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // Enumに定義してある条件値を取得
        $target_conditions = KintaiMgtEnum::TARGET_CONDITIONS;
        $base_check_conditions = KintaiMgtEnum::BASE_CHECK_CONDITIONS;
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        return view('kintai_mgt.index')->with([
            'kintais' => $kintais,
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'target_conditions' => $target_conditions,
            'base_check_conditions' => $base_check_conditions,
            'add_rest_available' => $add_rest_available,
        ]);
    }

    public function detail(Request $request)
    {
        // 現在のURLを取得
        session(['back_url_2' => url()->full()]);
        // インスタンス化
        $KintaiMgtService = new KintaiMgtService;
        $PunchFinishInputService = new PunchFinishInputService;
        // 勤怠概要を取得
        $kintai = Kintai::getSpecify($request->kintai_id)->first();
        // 勤怠詳細を取得
        $kintai_details = $KintaiMgtService->getKintaiDetail($request->kintai_id);
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        return view('kintai_mgt.detail')->with([
            'kintai' => $kintai,
            'kintai_details' => $kintai_details,
            'add_rest_available' => $add_rest_available,
        ]);
    }
}
