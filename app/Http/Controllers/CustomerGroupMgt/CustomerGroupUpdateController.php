<?php

namespace App\Http\Controllers\CustomerGroupMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerGroupCreateRequest;
use App\Services\CustomerGroupMgt\CustomerGroupUpdateService;
use App\Models\CustomerGroup;
use App\Models\Base;
use Illuminate\Support\Facades\Auth;

class CustomerGroupUpdateController extends Controller
{
    public function __construct()
    {
        // ミドルウェアを適用するメソッドを指定
        $this->middleware('FindByCustomerGroup')->only(['index', 'update']);
    }

    public function index(Request $request)
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主グループを取得
        $customer_group = CustomerGroup::getSpecify($request->customer_group_id)->first();
        return view('customer_group_mgt.update')->with([
            'bases' => $bases,
            'customer_group' => $customer_group,
        ]);
    }

    public function update(CustomerGroupCreateRequest $request)
    {
        // インスタンス化
        $CustomerGroupUpdateService = new CustomerGroupUpdateService;
        // レコードを変更
        $CustomerGroupUpdateService->updateCustomerGroup($request);
        return redirect(session('back_url_2'))->with([
            'alert_type' => 'success',
            'alert_message' => '荷主グループ情報を更新しました。',
        ]);
    }
}
