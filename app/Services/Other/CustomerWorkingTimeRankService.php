<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Customer;
use App\Models\Kintai;
use App\Models\KintaiDetail;
use App\Models\Employee;
use App\Enums\EmployeeCategoryEnum;

class CustomerWorkingTimeRankService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_date',
            'search_base_id',
            'search_customer_name',
            'search_orderby',
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 初期条件をセット
        session(['search_date' => $nowDate->format('Y-m')]);
        session(['search_base_id' => Auth::user()->base_id]);
        session(['search_orderby' => 'total']);
        return;
    }

    public function setSearchCondition($request)
    {
        // 検索条件をセット
        session(['search_date' => $request->search_date]);
        session(['search_base_id' => $request->search_base_id]);
        session(['search_customer_name' => $request->search_customer_name]);
        session(['search_orderby' => $request->search_orderby]);
        return;
    }

    public function getCustomerWorkingTimeRankSearch($start_day, $end_day)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 荷主毎の稼働時間を集計(全て)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_day, $end_day);
        $kintais = $this->getCustomerWorkingTimeByCustomer($common_kintais);
        // 荷主毎の稼働時間を集計(社員)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_day, $end_day);
        $kintais_1 = $this->getCustomerWorkingTimeByCustomerAndEmployeeCategory($common_kintais, EmployeeCategoryEnum::CONTRACT_EMPLOYEE, '<=');
        // 荷主毎の稼働時間を集計(パート)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_day, $end_day);
        $kintais_2 = $this->getCustomerWorkingTimeByCustomerAndEmployeeCategory($common_kintais, EmployeeCategoryEnum::PART_TIME_EMPLOYEE, '=');
        // 拠点条件を適用
        $customers = $this->getTargetCustomers();
        // 各稼働時間を結合
        $customers = $this->joinCustomerWorkingTime($customers, $kintais, $kintais_1, $kintais_2);
        // 並び順条件を適用
        $customers = $this->setOrderbyCondition($customers);
        return $customers;
    }

    public function getCommonCustomerWorkingTime($start_day, $end_day)
    {
        // この後の様々な情報取得で共通する部分を定義
        $common_kintais = Kintai::whereDate('work_day', '>=', $start_day)
                    ->whereDate('work_day', '<=', $end_day)
                    ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                    ->join('employees', 'employees.employee_id', 'kintais.employee_id');
        // 拠点条件がある場合は適用
        if (session('search_base_id') != null) {
            $common_kintais->whereHas('kintai_details.customer', function ($query) {
                $query->where('base_id', session('search_base_id'));
            });
        }
        return $common_kintais;
    }

    public function getCustomerWorkingTimeByCustomer($kintais)
    {
        // 荷主毎の荷主稼働時間を集計
        return $kintais->select(DB::raw("sum(customer_working_time) as total_customer_working_time, customer_id, DATE_FORMAT(work_day, '%Y-%m') as date"))
                    ->groupBy('customer_id', 'date');
    }

    public function getCustomerWorkingTimeByCustomerAndEmployeeCategory($kintais, $employee_category_id, $sign)
    {
        // 指定された従業員区分毎の荷主稼働時間を集計
        return $kintais->where('employee_category_id', $sign, $employee_category_id)
                    ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, customer_id, DATE_FORMAT(work_day, '%Y-%m') as date, GROUP_CONCAT(employees.employee_category_id) as employee_category_ids"))
                    ->groupBy('customer_id', 'date');
    }

    public function getTargetCustomers()
    {
        // インスタンス化
        $customers = new Customer;
        // 拠点条件がある場合
        if(session('search_base_id') != null){
            $customers = $customers->where('base_id', session('search_base_id'));
        }
        // 荷主名条件がある場合
        if(session('search_customer_name') != null){
            $customers = $customers->where('customer_name', 'LIKE', '%'.session('search_customer_name').'%');
        }
        return $customers;
    }

    public function joinCustomerWorkingTime($customer, $kintais, $kintais_1, $kintais_2)
    {
        // 稼働時間を結合する
        return $customer
            ->leftJoinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS.customer_id');
            })
            ->leftJoinSub($kintais_1, 'KINTAIS_1', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS_1.customer_id');
            })
            ->leftJoinSub($kintais_2, 'KINTAIS_2', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS_2.customer_id');
            })
            ->select('customers.customer_id', 'customers.base_id', 'customers.customer_name', 'KINTAIS.total_customer_working_time as total_customer_working_time_total', 'KINTAIS_1.total_customer_working_time as total_customer_working_time_full', 'KINTAIS_2.total_customer_working_time as total_customer_working_time_part');
    }

    public function setOrderbyCondition($customers)
    {
        // 並び順の条件を適用(優先1位に指定した条件、優先2位以降に補助の条件を適用)
        // 合計
        if(session('search_orderby') == 'total'){
            $customers->orderBy('total_customer_working_time_total', 'desc')
                        ->orderBy('total_customer_working_time_full', 'desc')
                        ->orderBy('total_customer_working_time_part', 'desc');
        }
        // 正社員
        if(session('search_orderby') == 'full'){
            $customers->orderBy('total_customer_working_time_full', 'desc')
                        ->orderBy('total_customer_working_time_part', 'desc');
        }
        // パート
        if(session('search_orderby') == 'part'){
            $customers->orderBy('total_customer_working_time_part', 'desc')
                        ->orderBy('total_customer_working_time_full', 'desc');
        }
        return $customers->paginate(50);
    }
}