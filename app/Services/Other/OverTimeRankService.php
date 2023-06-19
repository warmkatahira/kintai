<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Employee;
use App\Models\Kintai;
use App\Enums\EmployeeCategoryEnum;
use Illuminate\Support\Facades\Gate;

class OverTimeRankService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_date',
            'search_base_id',
            'search_employee_category_id',
            'search_employee_name',
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
        return;
    }

    public function setSearchCondition($request)
    {
        // 検索条件をセット
        session(['search_date' => $request->search_date]);
        session(['search_base_id' => $request->search_base_id]);
        session(['search_employee_category_id' => $request->search_employee_category_id]);
        session(['search_employee_name' => $request->search_employee_name]);
        return;
    }

    // 残業時間情報を取得
    public function getOverTimeRankSearch($start_day, $end_day)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 残業時間を集計
        $kintais = Kintai::whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->select(DB::raw("sum(over_time) as total_over_time, employee_id, DATE_FORMAT(work_day, '%Y-%m') as date"))
                        ->groupBy('employee_id', 'date');
        // 残業時間と従業員を結合(残業時間がない人は表示させないようにしている)
        $employees = Employee::
            rightJoinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('employees.employee_id', '=', 'KINTAIS.employee_id');
            })
            ->where('total_over_time', '!=', 0)
            ->select('employees.employee_id', 'employee_last_name', 'employee_first_name', 'total_over_time', 'base_id', 'employee_no', 'date', 'employee_category_id', 'over_time_start');
        // 時短情報が無効な場合、時短勤務者以外を対象とする
        if (!Gate::allows('isShortTimeInfoAvailable')) {
            $employees->where('over_time_start', 0);
        }
        // 拠点条件がある場合
        if (session('search_base_id')  != null) {
            $employees->where('base_id', session('search_base_id'));
        }
        // 従業員区分条件がある場合
        if (session('search_employee_category_id') != null) {
            $employees->where('employee_category_id', session('search_employee_category_id'));
        }
        // 従業員名条件がある場合
        if (session('search_employee_name') != null) {
            $employees->where('employee_last_name', 'LIKE', '%'.session('search_employee_name').'%')
                    ->orWhere('employee_first_name', 'LIKE', '%'.session('search_employee_name').'%');
        }
        // 残業時間が多い順に並び替え
        return $employees->orderBy('total_over_time', 'desc')->paginate(500);
    }
}