<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\Other\CustomerWorkingTimeRankService;

class CustomerWorkingTimeRankController extends Controller
{
    public function index()
    {
        // サービスクラスを定義
        $CustomerWorkingTimeRankService = new CustomerWorkingTimeRankService;
        $CommonService = new CommonService;
        // 初期条件をセット
        $CustomerWorkingTimeRankService->setDefaultCondition();
        // 荷主稼働情報を取得
        $customers = $CustomerWorkingTimeRankService->getCustomerWorkingTimeDataForIndex();
        // 並び順条件を適用
        $customers = $CustomerWorkingTimeRankService->setOrderbyCondition($customers);
        // 拠点情報を取得
        $bases = $CommonService->getBases(true, false);
        return view('other.customer_working_time_rank.index')->with([
            'customers' => $customers,
            'bases' => $bases,
        ]);
    }

    public function search(Request $request)
    {
        // サービスクラスを定義
        $CustomerWorkingTimeRankService = new CustomerWorkingTimeRankService;
        $CommonService = new CommonService;
        // 検索条件をセット
        $CustomerWorkingTimeRankService->getSearchCondition($request->search_month, $request->search_base, $request->search_orderby);
        // 荷主稼働情報を取得
        $customers = $CustomerWorkingTimeRankService->getCustomerWorkingTimeDataForIndex();
        // 並び順条件を適用
        $customers = $CustomerWorkingTimeRankService->setOrderbyCondition($customers);
        // 拠点情報を取得
        $bases = $CommonService->getBases(true, false);
        return view('other.customer_working_time_rank.index')->with([
            'customers' => $customers,
            'bases' => $bases,
        ]);
    }

    public function detail(Request $request)
    {
        // サービスクラスを定義
        $CustomerWorkingTimeRankService = new CustomerWorkingTimeRankService;
        // 指定した荷主の稼働情報を取得
        $customer = $CustomerWorkingTimeRankService->getCustomerWorkingTimeDataForDetail($request->customer_id);
        // 稼働している従業員情報を取得
        $employees = $CustomerWorkingTimeRankService->getWorkingEmployees($request->customer_id);
        return view('other.customer_working_time_rank.detail')->with([
            'customer' => $customer,
            'employees' => $employees,
        ]);
    }
}
