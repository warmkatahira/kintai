<?php

namespace App\Services\KintaiMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Kintai;
use App\Models\KintaiDetail;
use App\Models\Employee;
use App\Models\Base;
use App\Models\Customer;
use App\Enums\KintaiMgtEnum;

class KintaiMgtService
{
    // セッションを削除
    public function deleteSearchSession()
    {
        session()->forget([
            'search_work_day_from',
            'search_work_day_to',
            'search_base_id',
            'search_employee_category_id',
            'search_employee_name',
            'search_target',
            'search_base_check',
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
        // セッションに検索条件をセット
        session(['search_work_day_from' => $request->search_work_day_from]);
        session(['search_work_day_to' => $request->search_work_day_to]);
        session(['search_base_id' => $request->search_base_id]);
        session(['search_employee_category_id' => $request->search_employee_category_id]);
        session(['search_employee_name' => $request->search_employee_name]);
        session(['search_target' => $request->search_target]);
        session(['search_base_check' => $request->search_base_check]);
        return;
    }

    public function checkWorkdayCondition()
    {
        // 出勤日の条件が正しいかチェック
        // 条件1:開始と終了の日付がどちらも指定されているか
        // 条件2:開始と終了の期間が90日以内であるか
        if (!empty(session('search_work_day_from')) && !empty(session('search_work_day_to'))) {
            // 開始と終了の日にちの差を取得
            $from = new CarbonImmutable(session('search_work_day_from'));
            $to = new CarbonImmutable(session('search_work_day_to'));
            $diff_days_from_to = $from->diffInDays($to);
            // 日にちの差が90日より大きければNG
            if($diff_days_from_to > 90){
                return "出勤日の範囲が大き過ぎます。90日以内になるように指定して下さい。";
            }
        }else{
            return '出勤日の指定は必須です。';
        }
        return;
    }

    public function getKintaiSearch()
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 出勤日条件で勤怠を抽出
        $kintais = Kintai::whereDate('work_day', '>=', session('search_work_day_from'))
                        ->whereDate('work_day', '<=', session('search_work_day_to'))
                        ->join('employees', 'employees.employee_id', 'kintais.employee_id')
                        ->join('employee_categories', 'employee_categories.employee_category_id', 'employees.employee_category_id');
        // 拠点条件がある場合
        if (!empty(session('search_base_id'))) {
            $kintais->where('base_id', session('search_base_id'));
        }
        // 従業員区分条件がある場合
        if (!empty(session('search_employee_category_id'))) {
            $kintais->where('employees.employee_category_id', session('search_employee_category_id'));
        }
        // 従業員名条件がある場合
        if (!empty(session('search_employee_name'))) {
            $kintais->where('employee_last_name', 'LIKE', '%'.session('search_employee_name').'%');
        }
        // 従業員名条件がある場合
        if (!empty(session('search_employee_name'))) {
            $kintais->orWhere('employee_first_name', 'LIKE', '%'.session('search_employee_name').'%');
        }
        // 対象条件がある場合
        if (!empty(session('search_target'))) {
            // 未退勤
            if (session('search_target') == KintaiMgtEnum::TARGET_NO_FINISH) {
                $kintais->whereNull('finish_time');
            }
            // 退勤済
            if (session('search_target') == KintaiMgtEnum::TARGET_FINISHED) {
                $kintais->whereNotNull('finish_time');
            }
        }
        // 拠点確認条件がある場合
        if (!empty(session('search_base_check'))) {
            // 未確認
            if (session('search_base_check') == KintaiMgtEnum::BASE_CHECK_NO_CHECK) {
                $kintais->whereNull('base_checked_at');
            }
            // 確認済
            if (session('search_base_check') == KintaiMgtEnum::BASE_CHECK_CHECKED) {
                $kintais->whereNotNull('base_checked_at');
            }
        }
        // 出勤日と従業員番号で並び替え
        $kintais = $kintais->orderBy('work_day', 'asc')
                    ->orderBy('base_id', 'asc')
                    ->orderBy('employees.employee_category_id', 'asc')
                    ->orderBy('employees.employee_no', 'asc')
                    ->paginate(50);
        return $kintais;
    }

    public function getKintaiDetail($kintai_id)
    {
        // 荷主マスタと拠点マスタをユニオン
        $subquery = Customer::select('customer_id', 'customer_name')
                ->union(Base::select('base_id', 'base_name'));
        // 勤怠詳細テーブルと荷主・拠点情報を結合
        $kintai_details = KintaiDetail::getSpecifyKintai($kintai_id)
                        ->joinSub($subquery, 'SUB', function ($join) {
                            $join->on('kintai_details.customer_id', '=', 'SUB.customer_id');
                        })
                        ->select('SUB.customer_name', 'kintai_details.customer_working_time')
                        ->orderBy('kintai_details.customer_working_time', 'desc')
                        ->get();
        return $kintai_details;
    }
}