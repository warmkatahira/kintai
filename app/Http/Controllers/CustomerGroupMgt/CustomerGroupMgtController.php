<?php

namespace App\Http\Controllers\CustomerGroupMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerGroupMgt\CustomerGroupMgtService;
use App\Services\CommonService;
use App\Models\Base;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Carbon\CarbonImmutable;

class CustomerGroupMgtController extends Controller
{
    public function index()
    {
        // インスタンス化
        $CustomerGroupMgtService = new CustomerGroupMgtService;
        // セッションを削除
        $CustomerGroupMgtService->deleteSearchSession();
        // 初期条件をセット
        $CustomerGroupMgtService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主グループを取得
        $customer_groups = $CustomerGroupMgtService->getCustomerGroupSearch();
        return view('customer_group_mgt.index')->with([
            'bases' => $bases,
            'customer_groups' => $customer_groups,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $CustomerGroupMgtService = new CustomerGroupMgtService;
        // セッションを削除
        $CustomerGroupMgtService->deleteSearchSession();
        // 検索条件をセット
        $CustomerGroupMgtService->getSearchCondition($request);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主グループを取得
        $customer_groups = $CustomerGroupMgtService->getCustomerGroupSearch();
        return view('customer_group_mgt.index')->with([
            'bases' => $bases,
            'customer_groups' => $customer_groups,
        ]);
    }

    public function detail(Request $request)
    {
        // 現在のURLを取得
        session(['back_url_2' => url()->full()]);
        // インスタンス化
        $CustomerGroupMgtService = new CustomerGroupMgtService;
        // 荷主グループを取得
        $customer_group = CustomerGroup::getSpecify($request->customer_group_id)->first();
        // 荷主グループ配下の荷主を取得
        $customers = Customer::getSpecifyCustomerGroup($request->customer_group_id)->get();
        return view('customer_group_mgt.detail')->with([
            'customer_group' => $customer_group,
            'customers' => $customers,
        ]);
    }
}
