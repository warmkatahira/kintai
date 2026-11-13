<?php

namespace App\Services\PaidLeaveMgt;

use App\Models\PaidLeave;
use Carbon\CarbonImmutable;

class PaidLeaveMgtService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_date_from',
            'search_date_to',
            'search_base_id',
            'search_employee_no',
            'search_employee_name',
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 初期条件をセット(当月)
        session(['search_date_from' => $nowDate->startOfMonth()->toDateString()]);
        session(['search_date_to' => $nowDate->endOfMonth()->toDateString()]);
        return;
    }

    public function setSearchCondition($request)
    {
        // 検索条件をセット
        session(['search_date_from' => $request->search_date_from]);
        session(['search_date_to' => $request->search_date_to]);
        session(['search_base_id' => $request->search_base_id]);
        session(['search_employee_no' => $request->search_employee_no]);
        session(['search_employee_name' => $request->search_employee_name]);
        return;
    }

    // 有給情報を取得
    public function getPaidLeaveSearch()
    {
        $paid_leaves = PaidLeave::leftJoin('employees', 'employees.employee_no', 'paid_leaves.employee_no')
                            ->leftJoin('bases', 'bases.base_id', 'employees.base_id')
                            ->select('paid_leaves.*', 'employees.employee_last_name', 'employees.employee_first_name', 'bases.base_name');
        // 有給取得日条件がある場合
        if (session('search_date_from') != null) {
            $paid_leaves->whereDate('paid_leave_date', '>=', session('search_date_from'));
        }
        if (session('search_date_to') != null) {
            $paid_leaves->whereDate('paid_leave_date', '<=', session('search_date_to'));
        }
        // 拠点条件がある場合
        if (session('search_base_id') != null) {
            $paid_leaves->where('employees.base_id', session('search_base_id'));
        }
        // 従業員番号条件がある場合
        if (session('search_employee_no') != null) {
            $paid_leaves->where('paid_leaves.employee_no', session('search_employee_no'));
        }
        // 従業員名条件がある場合
        if (session('search_employee_name') != null) {
            $paid_leaves->where(function ($query) {
                $query->where('employee_last_name', 'LIKE', '%'.session('search_employee_name').'%')
                    ->orWhere('employee_first_name', 'LIKE', '%'.session('search_employee_name').'%');
            });
        }
        // 並び替え
        return $paid_leaves->orderBy('paid_leave_date', 'asc')->orderBy('paid_leaves.employee_no', 'asc');
    }

    public function getDownloadPaidLeave()
    {
        // ダウンロードする有給情報を取得
        $paid_leaves = $this->getPaidLeaveSearch()->get();
        // データが空ならヘッダーだけを出力
        if ($paid_leaves->isEmpty()) {
            return [array_fill_keys(PaidLeave::csvHeader(), '')];
        }
        $download_data = [];
        // 対象の分だけループ処理
        foreach ($paid_leaves as $paid_leave) {
            $download_data[] = [
                '従業員番号' => $paid_leave->employee_no,
                '有給取得日' => $paid_leave->paid_leave_date,
                '取得日数' => (string)(float)$paid_leave->paid_leave_days,
            ];
        }
        return $download_data;
    }
}
