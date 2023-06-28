<?php

namespace App\Http\Controllers\BaseMgt\CustomerMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerCreateRequest;
use App\Services\CustomerMgt\CustomerCreateService;
use App\Models\Base;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerCreateController extends Controller
{
    public function index(Request $request)
    {
        // 拠点情報を取得(全拠点操作の状態によって可変)
        if(Auth::user()->role->is_all_base_operation_available == 1){
            $bases = Base::getAll()->get();
        }else{
            $bases = Base::getSpecify(Auth::user()->base_id)->get();
        }
        // 自拠点の荷主グループを取得
        $customer_groups = CustomerGroup::getSpecifyBase(Auth::user()->base_id)->get();
        return view('customer_mgt.create')->with([
            'bases' => $bases,
            'customer_groups' => $customer_groups,
        ]);
    }

    public function create(CustomerCreateRequest $request)
    {
        try {
            DB::transaction(function () use (&$request) {
                // インスタンス化
                $CustomerCreateService = new CustomerCreateService;
                // レコードを追加
                $CustomerCreateService->createCustomer($request);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '荷主追加に失敗しました。',
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '荷主を追加しました。',
        ]);
    }
}
