<?php

namespace App\Http\Controllers\CustomerMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerMgt\CustomerMgtService;
use App\Services\CommonService;
use App\Models\Base;
use App\Models\Customer;
use Carbon\CarbonImmutable;

class CustomerMgtController extends Controller
{
    public function index()
    {
        // インスタンス化
        $CustomerMgtService = new CustomerMgtService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // セッションを削除
        $CustomerMgtService->deleteSearchSession();
        // 初期条件をセット
        $CustomerMgtService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主を取得
        $customers = $CustomerMgtService->getCustomerSearch($nowDate);
        return view('customer_mgt.index')->with([
            'bases' => $bases,
            'customers' => $customers,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $CustomerMgtService = new CustomerMgtService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // セッションを削除
        $CustomerMgtService->deleteSearchSession();
        // 検索条件をセット
        $CustomerMgtService->getSearchCondition($request);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主を取得
        $customers = $CustomerMgtService->getCustomerSearch($nowDate);
        return view('customer_mgt.index')->with([
            'bases' => $bases,
            'customers' => $customers,
        ]);
    }

    public function detail(Request $request)
    {
        // 現在のURLを取得
        session(['back_url_2' => url()->full()]);
        // インスタンス化
        $CustomerMgtService = new CustomerMgtService;
        $CommonService = new CommonService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 今月の月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth(CarbonImmutable::now());
        // 荷主を取得
        $customer = Customer::getSpecify($request->customer_id)->first();
        // 当月の荷主の総稼働時間を取得
        $kintai = $CustomerMgtService->getKintais($nowDate, $request->customer_id)->first();
        // 荷主稼働時間の多い従業員トップ5を取得
        $customer_working_time = $CustomerMgtService->getCustomerWorkingTime($nowDate, $request->customer_id);
        return view('customer_mgt.detail')->with([
            'customer' => $customer,
            'kintai' => $kintai,
            'customer_working_time' => $customer_working_time,
        ]);
    }
}
