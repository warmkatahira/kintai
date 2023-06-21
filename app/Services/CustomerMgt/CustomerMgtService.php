<?php

namespace App\Services\CustomerMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Customer;
use App\Models\Kintai;
use App\Models\KintaiDetail;

class CustomerMgtService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_base_id',
            'search_customer_name'
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
        session(['search_customer_name' => $request->search_customer_name]);
        return;
    }

    public function getCustomerSearch($start_day, $end_day)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 荷主をセット
        $customers = Customer::query();
        // 拠点条件がある場合
        if (session('search_base_id') != null) {
            $customers->where('base_id', session('search_base_id'));
        }
        // 荷主名条件がある場合
        if (session('search_customer_name') != null) {
            $customers->where('customer_name', 'LIKE', '%'.session('search_customer_name').'%');
        }
        // 拠点IDと荷主IDで並び替え
        return $customers->orderBy('base_id', 'asc')->orderBy('customer_sort_order', 'asc')->paginate(50);
    }
}