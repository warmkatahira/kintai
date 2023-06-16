<?php

namespace App\Http\Controllers\BaseMgt\CustomerMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerUpdateRequest;
use App\Services\CustomerMgt\CustomerUpdateService;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Base;
use Illuminate\Support\Facades\Auth;

class CustomerUpdateController extends Controller
{
    public function __construct()
    {
        // ミドルウェアを適用するメソッドを指定
        $this->middleware('FindByCustomer')->only(['index', 'update']);
    }

    public function index(Request $request)
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 荷主を取得
        $customer = Customer::getSpecify($request->customer_id)->first();
        // 自拠点の荷主グループを取得
        $customer_groups = CustomerGroup::getSpecifyBase(Auth::user()->base_id)->get();
        return view('customer_mgt.update')->with([
            'bases' => $bases,
            'customer' => $customer,
            'customer_groups' => $customer_groups,
        ]);
    }

    public function update(CustomerUpdateRequest $request)
    {
        // インスタンス化
        $CustomerUpdateService = new CustomerUpdateService;
        // レコードを変更
        $CustomerUpdateService->updateCustomer($request);
        return redirect(session('back_url_2'))->with([
            'alert_type' => 'success',
            'alert_message' => '荷主情報を更新しました。',
        ]);
    }
}
