<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Customer;
use App\Models\Kintai;
use App\Models\KintaiDetail;
use App\Models\Employee;
use App\Models\Base;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerRateCheckService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_work_day_from',
            'search_work_day_to',
            'search_base_id',
            'search_employee_category_id',
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 初期条件をセット
        session(['search_work_day_from' => $nowDate->toDateString()]);
        session(['search_work_day_to' => $nowDate->toDateString()]);
        session(['search_base_id' => Auth::user()->base_id]);
        return;
    }

    public function setSearchCondition($request)
    {
        // 検索条件をセット
        session(['search_work_day_from' => $request->search_work_day_from]);
        session(['search_work_day_to' => $request->search_work_day_to]);
        session(['search_base_id' => $request->search_base_id]);
        session(['search_employee_category_id' => $request->search_employee_category_id]);
        session(['search_employee_name' => $request->search_employee_name]);
        return;
    }

    public function getCustomerRateSearch()
    {
        // テーブルをセット
        $employees = Employee::where('is_available', 1);
        // 拠点条件がある場合
        if (session('search_base_id') != null) {
            $employees->where('base_id', session('search_base_id'));
        }
        // 従業員名条件がある場合
        if (session('search_employee_name') != null) {
            $employees->where('employee_last_name', 'LIKE', '%'.session('search_employee_name').'%')
                    ->orWhere('employee_first_name', 'LIKE', '%'.session('search_employee_name').'%');
        }
        // 区分条件がある場合
        if (session('search_employee_category_id') != null) {
            $employees->where('employee_category_id', session('search_employee_category_id'));
        }
        // 従業員番号で並び替え
        return $employees->orderBy('base_id', 'asc')->orderBy('employee_category_id', 'asc')->orderBy('employee_no', 'asc')->get();
    }

    public function getCustomerWorkingTime($employees)
    {
        // 荷主マスタと拠点マスタをユニオン
        $subquery = Customer::select('customer_id', 'customer_name')
                ->union(Base::select('base_id', 'base_name'));
        // 情報を格納する配列をセット
        $customer_working_times = [];
        // 従業員の分だけループ処理
        foreach($employees as $employee){
            // 対象従業員の勤怠を取得
            $kintais = Kintai::where('employee_id', $employee->employee_id)
                        ->whereDate('work_day', '>=', session('search_work_day_from'))
                        ->whereDate('work_day', '<=', session('search_work_day_to'));
            // 取得した勤怠を勤怠詳細テーブルと結合して、荷主毎の稼働時間を集計
            $customer_working_time = KintaiDetail::
                        joinSub($kintais, 'KINTAIS', function ($join) {
                            $join->on('kintai_details.kintai_id', '=', 'KINTAIS.kintai_id');
                        })
                        ->joinSub($subquery, 'SUBQUERY', function ($join) {
                            $join->on('kintai_details.customer_id', '=', 'SUBQUERY.customer_id');
                        })
                        ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, kintai_details.customer_id, SUBQUERY.customer_name"))
                        ->groupBy('kintai_details.customer_id', 'SUBQUERY.customer_name')
                        ->orderBy('total_customer_working_time', 'desc')
                        ->get();
            // 荷主毎の稼働時間を格納
            $customer_working_times[$employee->employee_id]['rank'] = $customer_working_time;
            // 期間の合計稼働時間を格納
            $customer_working_times[$employee->employee_id]['total'] = $kintais->select(DB::raw("sum(working_time) as total_working_time"))->first()->total_working_time;
        }
        return $customer_working_times;
    }

    public function getDownloadCustomerRate($employees, $customer_working_times)
    {
        $response = new StreamedResponse(function () use ($employees, $customer_working_times) {
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // ヘッダー行を書き込む
            $headerRow = [
                '拠点',
                '従業員区分',
                '従業員名',
                '荷主名',
                '荷主稼働時間',
                '割合',
            ];
            fputcsv($handle, $headerRow);
            // 従業員の分だけループ
            foreach($employees as $employee){
                foreach($customer_working_times[$employee->employee_id]['rank'] as $customer_working_time){
                    $row = [
                        $employee->base->base_name,
                        $employee->employee_category->employee_category_name,
                        $employee->employee_last_name.' '.$employee->employee_first_name,
                        $customer_working_time->customer_name,
                        $customer_working_time->total_customer_working_time / 60,
                        number_format(($customer_working_time->total_customer_working_time / $customer_working_times[$employee->employee_id]['total']) * 100, 1),
                    ];
                    fputcsv($handle, $row);
                }
            };
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}