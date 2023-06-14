<?php

namespace App\Services\CustomerGroupMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\CustomerGroup;

class CustomerGroupMgtService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_base_id',
            'search_customer_group_name'
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 初期条件をセット
        session(['search_base_id' => Auth::user()->base_id]);
        return;
    }

    public function setSearchCondition($request)
    {
        // セッションに検索条件をセット
        session(['search_base_id' => $request->search_base_id]);
        session(['search_customer_group_name' => $request->search_customer_group_name]);
        return;
    }

    public function getCustomerGroupSearch()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // クエリをセット
        $customer_groups = CustomerGroup::query();
        // 拠点条件がある場合
        if (session('search_base_id') != null) {
            $customer_groups->where('base_id', session('search_base_id'));
        }
        // 荷主グループ名条件がある場合
        if (!empty(session('search_customer_group_name'))) {
            $customer_groups->where('customer_group_name', 'LIKE', '%'.session('search_customer_group_name').'%');
        }
        // 拠点IDと荷主IDで並び替え
        $customer_groups = $customer_groups->orderBy('base_id', 'asc')->orderBy('customer_group_sort_order', 'asc')->paginate(50);
        return $customer_groups;
    }
}