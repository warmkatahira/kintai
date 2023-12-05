<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Other\CustomerRateCheckService;
use App\Models\Base;
use App\Models\EmployeeCategory;
use Carbon\CarbonImmutable;

class CustomerRateCheckController extends Controller
{
    public function index()
    {
        // インスタンス化
        $CustomerRateCheckService = new CustomerRateCheckService;
        // セッションを削除
        $CustomerRateCheckService->deleteSearchSession();
        // 初期条件をセット
        $CustomerRateCheckService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分情報を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 表示させる従業員情報を取得
        $employees = $CustomerRateCheckService->getCustomerRateSearch();
        // 従業員情報の荷主稼働時間を取得
        $customer_working_times = $CustomerRateCheckService->getCustomerWorkingTime($employees);
        return view('other.customer_rate_check.index')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employees' => $employees,
            'customer_working_times' => $customer_working_times,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $CustomerRateCheckService = new CustomerRateCheckService;
        // セッションを削除
        $CustomerRateCheckService->deleteSearchSession();
        // 初期条件をセット
        $CustomerRateCheckService->setSearchCondition($request);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分情報を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 表示させる従業員情報を取得
        $employees = $CustomerRateCheckService->getCustomerRateSearch();
        // 従業員情報の荷主稼働時間を取得
        $customer_working_times = $CustomerRateCheckService->getCustomerWorkingTime($employees);
        return view('other.customer_rate_check.index')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employees' => $employees,
            'customer_working_times' => $customer_working_times,
        ]);
    }

    public function download()
    {
        // インスタンス化
        $CustomerRateCheckService = new CustomerRateCheckService;
        // 表示させる従業員情報を取得
        $employees = $CustomerRateCheckService->getCustomerRateSearch();
        // 従業員情報の荷主稼働時間を取得
        $customer_working_times = $CustomerRateCheckService->getCustomerWorkingTime($employees);
        // ダウンロードするデータを取得
        $response = $CustomerRateCheckService->getDownloadCustomerRate($employees, $customer_working_times);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=荷主割合_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
