<?php

namespace App\Http\Controllers\BaseMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerMgt\CustomerMgtService;
use App\Models\Base;

class CustomerMgtController extends Controller
{
    public function index()
    {
        // インスタンス化
        $CustomerMgtService = new CustomerMgtService;
        // セッションを削除
        $CustomerMgtService->deleteSearchSession();
        // 初期条件をセット
        $CustomerMgtService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主を取得
        $customers = $CustomerMgtService->getCustomerSearch();
        return view('customer_mgt.index')->with([
            'bases' => $bases,
            'customers' => $customers,
        ]);
    }
}
