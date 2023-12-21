<?php

namespace App\Services\Other;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\Employee;
use App\Models\Kintai;
use App\Enums\EmployeeCategoryEnum;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OverTimeRankService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_work_day_from',
            'search_work_day_to',
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
        session(['search_work_day_from' => $nowDate->format('Y-m')]);
        session(['search_work_day_to' => $nowDate->format('Y-m')]);
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

    // 残業時間情報を取得
    public function getOverTimeRankSearch($start_day, $end_day)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 残業時間を集計
        $kintais = Kintai::whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->select(DB::raw("sum(over_time) as total_over_time, employee_id"))
                        ->groupBy('employee_id');
        // 残業時間と従業員を結合(残業時間がない人は表示させないようにしている)
        $employees = Employee::
            rightJoinSub($kintais, 'KINTAIS', function ($join) {
                $join->on('employees.employee_id', '=', 'KINTAIS.employee_id');
            })
            ->where('total_over_time', '!=', 0)
            ->select('employees.employee_id', 'employee_last_name', 'employee_first_name', 'total_over_time', 'base_id', 'employee_no', 'employee_category_id', 'over_time_start');
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
        return $employees->orderBy('total_over_time', 'desc')->orderBy('base_id', 'asc')->orderBy('employee_category_id', 'asc')->orderBy('employee_no', 'asc')->paginate(500);
    }

    public function getDownloadOverTimeRank($employees)
    {
        $response = new StreamedResponse(function () use ($employees) {
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // ヘッダー行を書き込む
            $headerRow = [
                'Rank',
                '期間',
                '拠点',
                '従業員区分',
                '従業員番号',
                '従業員名',
                '残業時間',
            ];
            fputcsv($handle, $headerRow);
            // 従業員の分だけループ
            foreach($employees as $key => $employee){
                $row = [
                    $key + 1,
                    CarbonImmutable::parse(session('search_work_day_from'))->isoFormat('YYYY年MM月').'～'.CarbonImmutable::parse(session('search_work_day_to'))->isoFormat('YYYY年MM月'),
                    $employee->base->base_name,
                    $employee->employee_category->employee_category_name,
                    $employee->employee_no,
                    $employee->employee_last_name.' '.$employee->employee_first_name,
                    number_format(($employee->total_over_time / 60), 2),
                ];
                fputcsv($handle, $row);
            };
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}