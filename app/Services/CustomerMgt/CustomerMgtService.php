<?php

namespace App\Services\CustomerMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Customer;

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

    public function getSearchCondition($request)
    {
        // セッションに検索条件をセット
        session(['search_base_id' => $request->search_base_id]);
        session(['search_customer_name' => $request->search_customer_name]);
        return;
    }

    public function getCustomerSearch()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 
        $customers = Customer::query();
        // 拠点条件がある場合
        if (session('search_base_id') != 0) {
            $customers->where('base_id', session('search_base_id'));
        }
        // 従業員名条件がある場合
        if (!empty(session('search_customer_name'))) {
            $customers->where('customer_name', 'LIKE', '%'.session('search_customer_name').'%');
        }
        // 従業員番号で並び替え
        $customers = $customers->orderBy('customer_id')->paginate(50);
        return $customers;
    }

    public function getThisMonthData($nowDate, $employee_id){
        // 当月の合計時間・稼働日数を取得
        return Kintai::where('employee_id', $employee_id)
                        ->whereDate('work_day', '>=', $nowDate->startOfMonth()->toDateString())
                        ->whereDate('work_day', '<=', $nowDate->endOfMonth()->toDateString())
                        ->select(DB::raw("sum(working_time) as total_working_time, sum(over_time) as total_over_time, count(work_day) as working_days, DATE_FORMAT(work_day, '%Y-%m') as date"))
                        ->groupBy('employee_id', 'date')
                        ->first();
    }

    public function getCustomerWorkingTime($nowDate, $employee_id)
    {
        // 荷主マスタと拠点マスタをユニオン
        $subquery = Customer::select('customer_id', 'customer_name')
                ->union(Base::select('base_id', 'base_name'));
        // 対象従業員の当月の勤怠を取得
        $kintais = Kintai::where('employee_id', $employee_id)
                    ->whereDate('work_day', '>=', $nowDate->startOfMonth()->toDateString())
                    ->whereDate('work_day', '<=', $nowDate->endOfMonth()->toDateString());
        // 取得した勤怠を勤怠詳細テーブルと結合して、荷主毎の稼働時間を集計
        $customer_working_time = KintaiDetail::
            joinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('kintai_details.kintai_id', '=', 'KINTAIS.kintai_id');
            })
            ->joinSub($subquery, 'SUBQUERY', function ($join) {
                $join->on('kintai_details.customer_id', '=', 'SUBQUERY.customer_id');
            })
            ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, DATE_FORMAT(work_day, '%Y-%m') as date, kintai_details.customer_id, SUBQUERY.customer_name"))
            ->groupBy('date', 'kintai_details.customer_id', 'SUBQUERY.customer_name')
            ->orderBy('total_customer_working_time', 'desc')
            ->take(3)
            ->get();
        return $customer_working_time;
    }
}