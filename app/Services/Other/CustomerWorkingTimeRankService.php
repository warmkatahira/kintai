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
use App\Models\TemporaryUse;

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
        // 荷主毎の稼働時間を集計(派遣)
        $common_kintais = $this->getCommonCustomerWorkingTime($start_day, $end_day);
        $kintais_3 = $this->getCustomerWorkingTimeByTemporary($start_day, $end_day);
        // 拠点条件を適用
        $customers = $this->getTargetCustomers();
        // 各稼働時間を結合
        $customers = $this->joinCustomerWorkingTime($customers, $kintais, $kintais_1, $kintais_2, $kintais_3);
        // 並び順条件を適用
        return $this->setOrderbyCondition($customers);
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

    public function getCustomerWorkingTimeByTemporary($start_day, $end_day)
    {
        // 期間で情報を抽出
        $temporary_kintais = TemporaryUse::whereDate('date', '>=', $start_day)
                                ->whereDate('date', '<=', $end_day);
        // 拠点条件がある場合は適用
        if (session('search_base_id') != null) {
            $temporary_kintais->where('base_id', session('search_base_id'));
        }
        // 派遣の荷主稼働時間を集計
        return $temporary_kintais->select(DB::raw("sum(working_time) as total_customer_working_time, customer_id, DATE_FORMAT(date, '%Y-%m') as date_ym"))
                                ->groupBy('customer_id', 'date_ym');
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

    public function joinCustomerWorkingTime($customer, $kintais, $kintais_1, $kintais_2, $kintais_3)
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
            ->leftJoinSub($kintais_3, 'KINTAIS_3', function ($join) {
                $join->on('customers.customer_id', '=', 'KINTAIS_3.customer_id');
            })
            ->where('KINTAIS.total_customer_working_time', '!=', 0)
            ->select('customers.customer_id', 'customers.base_id', 'customers.customer_name', 'KINTAIS_1.total_customer_working_time as total_customer_working_time_shain', 'KINTAIS_2.total_customer_working_time as total_customer_working_time_part', 'KINTAIS_3.total_customer_working_time as total_customer_working_time_temporary', 'KINTAIS.date', DB::raw('COALESCE(KINTAIS.total_customer_working_time, 0) + COALESCE(KINTAIS_3.total_customer_working_time, 0) as total_customer_working_time_total'));
    }

    public function setOrderbyCondition($customers)
    {
        // 並び順の条件を適用(優先1位に指定した条件、優先2位以降に補助の条件を適用)
        // 合計
        if(session('search_orderby') == 'total'){
            $customers->orderBy('total_customer_working_time_total', 'desc')
                        ->orderBy('total_customer_working_time_shain', 'desc')
                        ->orderBy('total_customer_working_time_part', 'desc')
                        ->orderBy('total_customer_working_time_temporary', 'desc');
        }
        // 社員
        if(session('search_orderby') == 'shain'){
            $customers->orderBy('total_customer_working_time_shain', 'desc')
                        ->orderBy('total_customer_working_time_part', 'desc')
                        ->orderBy('total_customer_working_time_temporary', 'desc');
        }
        // パート
        if(session('search_orderby') == 'part'){
            $customers->orderBy('total_customer_working_time_part', 'desc')
                        ->orderBy('total_customer_working_time_shain', 'desc')
                        ->orderBy('total_customer_working_time_temporary', 'desc');
        }
        // 派遣
        if(session('search_orderby') == 'temporary'){
            $customers->orderBy('total_customer_working_time_temporary', 'desc')
                        ->orderBy('total_customer_working_time_shain', 'desc')
                        ->orderBy('total_customer_working_time_part', 'desc');
        }
        return $customers->paginate(50);
    }

    // 荷主稼働時間の多い従業員トップ10を取得
    public function getCustomerWorkingTime($start_day, $end_day, $customer_id)
    {
        // 当月の勤怠を抽出
        $kintais = Kintai::whereDate('work_day', '>=', $start_day)
                            ->whereDate('work_day', '<=', $end_day);
        // 当月の勤怠と選択した荷主の勤怠詳細を結合
        $employees = KintaiDetail::where('customer_id', $customer_id)
            ->joinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('kintai_details.kintai_id', '=', 'KINTAIS.kintai_id');
            })
            ->join('employees', 'employees.employee_id', 'KINTAIS.employee_id')
            ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, DATE_FORMAT(work_day, '%Y-%m') as date, employees.*"))
            ->groupBy('date', 'employees.employee_id')
            ->orderBy('total_customer_working_time', 'desc')
            ->take(10)
            ->get();
        return $employees;
    }
}