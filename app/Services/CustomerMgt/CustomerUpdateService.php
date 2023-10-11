<?php

namespace App\Services\CustomerMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;


class CustomerUpdateService
{
    public function updateCustomer($request)
    {
        // 現在の内容を取得
        $customer = Customer::getSpecify($request->customer_id)->first();
        // 更新処理(荷主グループIDは更新前後の拠点IDが違えばnull更新する)
        Customer::getSpecify($request->customer_id)->update([
            'base_id' => $request->base_id,
            'customer_name' => $request->customer_name,
            'customer_group_id' => $customer->base_id == $request->base_id ? $request->customer_group_id : null,
            'is_available' => $request->is_available,
            'customer_sort_order' => $request->customer_sort_order,
        ]);
        return;
    }
}