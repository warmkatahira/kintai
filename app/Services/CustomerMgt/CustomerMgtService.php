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
        // 当月の勤怠を取得
        $kintais = $this->getKintais($start_day, $end_day, null);
        //dd($kintais->get());
        // 勤怠と荷主を結合
        $customers = Customer::
            leftJoinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS.customer_id');
            })
            ->select('customers.*', 'KINTAIS.total_customer_working_time');
        // 拠点条件がある場合
        if (session('search_base_id') != null) {
            $customers->where('base_id', session('search_base_id'));
        }
        // 荷主名条件がある場合
        if (session('search_customer_name') != null) {
            $customers->where('customer_name', 'LIKE', '%'.session('search_customer_name').'%');
        }
        // 拠点IDと荷主IDで並び替え
        return $customers->orderBy('base_id', 'asc')->orderBy('customer_id', 'asc')->paginate(50);
    }

    public function getKintais($start_day, $end_day, $customer_id)
    {
        // 当月の勤怠を抽出
        $kintais = Kintai::whereDate('work_day', '>=', $start_day)
                            ->whereDate('work_day', '<=', $end_day);
        // 当月の勤怠に勤怠詳細を結合
        $kintais = KintaiDetail::
            joinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('kintai_details.kintai_id', '=', 'KINTAIS.kintai_id');
            })
            ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, customer_id, DATE_FORMAT(work_day, '%Y-%m') as date"))
            ->groupBy('customer_id', 'date');
        // 指定がある場合は、customer_idで抽出
        if(!is_null($customer_id)){
            $kintais->where('customer_id', $customer_id);
        }
        return $kintais;
    }

    // 荷主稼働時間の多い従業員トップ5を取得
    public function getCustomerWorkingTime($start_day, $end_day, $customer_id)
    {
        // 当月の勤怠を抽出
        $kintais = Kintai::whereDate('work_day', '>=', $start_day)
                            ->whereDate('work_day', '<=', $end_day);
        // 当月の勤怠と選択した荷主の勤怠詳細を結合
        $customer_working_time = KintaiDetail::where('customer_id', $customer_id)
            ->joinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('kintai_details.kintai_id', '=', 'KINTAIS.kintai_id');
            })
            ->join('employees', 'employees.employee_id', 'KINTAIS.employee_id')
            ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, DATE_FORMAT(work_day, '%Y-%m') as date, employees.*"))
            ->groupBy('date', 'employees.employee_id')
            ->orderBy('total_customer_working_time', 'desc')
            ->take(5)
            ->get();
        return $customer_working_time;
    }
}