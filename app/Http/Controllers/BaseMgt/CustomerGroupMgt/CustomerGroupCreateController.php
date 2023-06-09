<?php

namespace App\Http\Controllers\BaseMgt\CustomerGroupMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerGroupCreateRequest;
use App\Services\CustomerGroupMgt\CustomerGroupCreateService;
use App\Models\Base;
use Illuminate\Support\Facades\Auth;

class CustomerGroupCreateController extends Controller
{
    public function index(Request $request)
    {
        // 拠点情報を取得(全拠点操作の状態によって可変)
        if(Auth::user()->role->is_all_base_operation_available == 1){
            $bases = Base::getAll()->get();
        }else{
            $bases = Base::getSpecify(Auth::user()->base_id)->get();
        }
        return view('customer_group_mgt.create')->with([
            'bases' => $bases,
        ]);
    }

    public function create(CustomerGroupCreateRequest $request)
    {
        // インスタンス化
        $CustomerGroupCreateService = new CustomerGroupCreateService;
        // レコードを変更
        $CustomerGroupCreateService->createCustomerGroup($request);
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '荷主グループを追加しました。',
        ]);
    }
}
