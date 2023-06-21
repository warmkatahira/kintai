<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\Other\CustomerWorkingTimeRankService;
use App\Models\Base;
use App\Models\Customer;

class CustomerWorkingTimeRankController extends Controller
{
    public function index()
    {
        // インスタンス化
        $CustomerWorkingTimeRankService = new CustomerWorkingTimeRankService;
        $CommonService = new CommonService;
        // セッションを削除
        $CustomerWorkingTimeRankService->deleteSearchSession();
        // 初期条件をセット
        $CustomerWorkingTimeRankService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth(session('search_date'));
        // 荷主稼働情報を取得
        $customers = $CustomerWorkingTimeRankService->getCustomerWorkingTimeRankSearch($start_end_of_month['start'], $start_end_of_month['end']);
        return view('other.customer_working_time_rank.index')->with([
            'bases' => $bases,
            'customers' => $customers,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $CustomerWorkingTimeRankService = new CustomerWorkingTimeRankService;
        $CommonService = new CommonService;
        // セッションを削除
        $CustomerWorkingTimeRankService->deleteSearchSession();
        // 検索条件をセット
        $CustomerWorkingTimeRankService->setSearchCondition($request);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth(session('search_date'));
        // 荷主稼働情報を取得
        $customers = $CustomerWorkingTimeRankService->getCustomerWorkingTimeRankSearch($start_end_of_month['start'], $start_end_of_month['end']);
        return view('other.customer_working_time_rank.index')->with([
            'bases' => $bases,
            'customers' => $customers,
        ]);
    }

    public function detail(Request $request)
    {
        // インスタンス化
        $CustomerWorkingTimeRankService = new CustomerWorkingTimeRankService;
        $CommonService = new CommonService;
        // 月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth($request->date);
        // 荷主情報を取得
        $customer = Customer::getSpecify($request->customer_id)->first();
        // 荷主稼働時間の多い従業員トップ10を取得
        $employees = $CustomerWorkingTimeRankService->getCustomerWorkingTime($start_end_of_month['start'], $start_end_of_month['end'], $request->customer_id);
        return view('other.customer_working_time_rank.detail')->with([
            'date' => $request->date,
            'customer' => $customer,
            'employees' => $employees,
            'total' => $request->total,
            'shain' => $request->shain,
            'part' => $request->part,
        ]);
    }
}
