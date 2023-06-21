<?php

namespace App\Services\CustomerMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Carbon\CarbonImmutable;

class CustomerCreateService
{
    public function createCustomer($request)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // テーブルをロック
        Customer::select()->lockForUpdate()->get();
        // customer_idを採番(拠点ID-yyyyの末尾2桁+mmdd-連番)
        $num = 0;
        $i = 1;
        // 重複しないidを生成するまでループ
        $customer_id = $nowDate->format('ymd') . '-' . sprintf('%03d', $num + $i);
        while(Customer::where('customer_id', $customer_id)->exists()){
            $customer_id = $nowDate->format('ymd') . '-' . sprintf('%03d', $num + $i);
            $i++;
        }
        // テーブルへ追加
        Customer::create([
            'customer_id' => $customer_id,
            'base_id' => $request->base_id,
            'customer_name' => $request->customer_name,
            'customer_group_id' => $request->customer_group_id,
            'customer_sort_order' => $request->customer_sort_order,
        ]);
        return;
    }
}