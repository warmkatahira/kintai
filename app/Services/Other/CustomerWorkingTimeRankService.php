<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Customer;
use App\Models\Kintai;
use App\Models\KintaiDetail;
use App\Models\Employee;

class CustomerWorkingTimeRankService
{
    public function setDefaultCondition()
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 初期条件をセット
        session(['search_month' => $nowDate->format('Y-m')]);
        session(['search_base' => Auth::user()->base_id]);
        session(['search_orderby' => 'total']);
        return;
    }

    public function getSearchCondition($search_month, $search_base, $search_orderby)
    {
        // 検索条件をセット
        session(['search_month' => $search_month]);
        session(['search_base' => $search_base]);
        session(['search_orderby' => $search_orderby]);
        return;
    }

    public function getCustomerWorkingTimeDataForIndex()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 月初と月末の日付を取得
        $start_end_day = $this->getStartEndDay();
        // 荷主毎の稼働時間を集計(全て)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_end_day['start_day'], $start_end_day['end_day']);
        $kintais = $this->getCustomerWorkingTimeByCustomer($common_kintais);
        // 荷主毎の稼働時間を集計(正社員)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_end_day['start_day'], $start_end_day['end_day']);
        $kintais_1 = $this->getCustomerWorkingTimeByCustomerAndEmployeeCategory($common_kintais, 1);
        // 荷主毎の稼働時間を集計(パート)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_end_day['start_day'], $start_end_day['end_day']);
        $kintais_2 = $this->getCustomerWorkingTimeByCustomerAndEmployeeCategory($common_kintais, 2);
        // 拠点条件を適用して取得
        $customers = $this->getTargetCustomers();
        // 各稼働時間を結合
        $customers = $this->joinCustomerWorkingTime($customers, $kintais, $kintais_1, $kintais_2);
        return $customers;
    }

    public function getStartEndDay()
    {
        // 月初と月末の日付を取得
        $start_day = new CarbonImmutable(session('search_month'));
        $end_day = new CarbonImmutable(session('search_month'));
        $start_day = $start_day->startOfMonth()->toDateString();
        $end_day = $end_day->endOfMonth()->toDateString();
        return compact('start_day', 'end_day');
    }

    public function getCommonCustomerWorkingTime($start_day, $end_day)
    {
        // この後の様々な情報取得で共通する部分を定義
        $common_kintais = Kintai::join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                            ->join('employees', 'employees.employee_no', 'kintais.employee_no')
                            ->whereBetween('work_day', [$start_day, $end_day]);
        return $common_kintais;
    }

    public function getCustomerWorkingTimeByCustomer($kintais)
    {
        // 荷主毎の荷主稼働時間を集計
        $kintais = $kintais->select(DB::raw("sum(customer_working_time) as total_customer_working_time, customer_id, DATE_FORMAT(work_day, '%Y-%m') as date"))
                    ->groupBy('customer_id', 'date');
        return $kintais;
    }

    public function getCustomerWorkingTimeByCustomerAndEmployeeCategory($kintais, $employee_category_id)
    {
        // 指定された従業員区分毎の荷主稼働時間を集計
        $kintais = $kintais->where('employees.employee_category_id', $employee_category_id)
                    ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, customer_id, DATE_FORMAT(work_day, '%Y-%m') as date, employees.employee_category_id"))
                    ->groupBy('customer_id', 'date', 'employee_category_id');
        return $kintais;
    }

    public function getTargetCustomers()
    {
        // インスタンス化
        $customers = new Customer;
        // 全社以外の条件だった場合に条件を適用
        if(session('search_base') != 0){
            $customers = $customers->where('control_base_id', session('search_base'));
        }
        return $customers;
    }

    public function joinCustomerWorkingTime($customer, $kintais, $kintais_1, $kintais_2)
    {
        // 稼働時間を結合する
        $customers = $customer
            ->leftJoinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS.customer_id');
            })
            ->leftJoinSub($kintais_1, 'KINTAIS_1', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS_1.customer_id');
            })
            ->leftJoinSub($kintais_2, 'KINTAIS_2', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS_2.customer_id');
            })
            ->select('customers.customer_id', 'customers.control_base_id', 'customers.customer_name', 'customers.control_base_id', 'KINTAIS.total_customer_working_time as total_customer_working_time_total', 'KINTAIS_1.total_customer_working_time as total_customer_working_time_shain', 'KINTAIS_2.total_customer_working_time as total_customer_working_time_part');
        return $customers;
    }

    public function setOrderbyCondition($customers)
    {
        // 並び順の条件を適用(優先1位に指定した条件、優先2位以降に補助の条件を適用)
        // 合計
        if(session('search_orderby') == 'total'){
            $customers->orderBy('total_customer_working_time_total', 'desc')
                        ->orderBy('total_customer_working_time_shain', 'desc')
                        ->orderBy('total_customer_working_time_part', 'desc');
        }
        // 正社員
        if(session('search_orderby') == 'shain'){
            $customers->orderBy('total_customer_working_time_shain', 'desc')
                        ->orderBy('total_customer_working_time_part', 'desc');
        }
        // パート
        if(session('search_orderby') == 'part'){
            $customers->orderBy('total_customer_working_time_part', 'desc')
                        ->orderBy('total_customer_working_time_shain', 'desc');
        }
        $customers = $customers->paginate(20);;
        return $customers;
    }

    public function getCustomerWorkingTimeDataForDetail($customer_id)
    {
        // 月初と月末の日付を取得
        $start_end_day = $this->getStartEndDay();
        // 荷主毎の稼働時間を集計(全て)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_end_day['start_day'], $start_end_day['end_day']);
        $kintais = $this->getCustomerWorkingTimeByCustomer($common_kintais);
        // 荷主毎の稼働時間を集計(正社員)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_end_day['start_day'], $start_end_day['end_day']);
        $kintais_1 = $this->getCustomerWorkingTimeByCustomerAndEmployeeCategory($common_kintais, 1);
        // 荷主毎の稼働時間を集計(パート)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_end_day['start_day'], $start_end_day['end_day']);
        $kintais_2 = $this->getCustomerWorkingTimeByCustomerAndEmployeeCategory($common_kintais, 2);
        // 各稼働時間を結合
        $customer = Customer::where('customers.customer_id', $customer_id);
        $customer = $this->joinCustomerWorkingTime($customer, $kintais, $kintais_1, $kintais_2);
        $customer = $customer->first();
        return $customer;
    }

    public function getWorkingEmployees($customer_id)
    {
        // 月初と月末の日付を取得
        $start_end_day = $this->getStartEndDay();
        // 稼働従業員情報を取得
        $employees = Employee::join('kintais', 'kintais.employee_no', 'employees.employee_no')
                            ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                            ->whereBetween('work_day', [$start_end_day['start_day'], $start_end_day['end_day']])
                            ->where('customer_id', $customer_id)
                            ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, employees.*, DATE_FORMAT(work_day, '%Y-%m') as date"))
                            ->groupBy('employee_no', 'date')
                            ->orderBy('total_customer_working_time', 'desc')
                            ->orderBy('employee_no', 'asc')
                            ->get();
        return $employees;
    }
}