<?php

namespace App\Http\Controllers\CustomerMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerCreateRequest;
use App\Services\CustomerMgt\CustomerCreateService;
use App\Models\Base;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Auth;

class CustomerCreateController extends Controller
{
    public function index(Request $request)
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 自拠点の荷主グループを取得
        $customer_groups = CustomerGroup::getSpecifyBase(Auth::user()->base_id)->get();
        return view('customer_mgt.create')->with([
            'bases' => $bases,
            'customer_groups' => $customer_groups,
        ]);
    }

    public function create(CustomerCreateRequest $request)
    {
        // インスタンス化
        $CustomerCreateService = new CustomerCreateService;
        // レコードを変更
        $CustomerCreateService->createCustomer($request);
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '荷主を追加しました。',
        ]);
    }
}
