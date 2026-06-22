<?php

namespace App\Services\Download;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\Base;
use App\Models\Kintai;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\Holiday;
use App\Models\KintaiClose;
use App\Models\KintaiCloseEmployee;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Gate;
use App\Enums\EmployeeCategoryEnum;

class KintaiReportDownloadService
{
    public function getBase($base_id, $date)
    {
        // 出力対象の営業所を取得
        $base = Base::getSpecify($base_id)->first();
        // 従業員区分を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 勤怠提出テーブルから、ダウンロード条件のレコードを取得
        $kintai_close = KintaiClose::where('base_id', $base_id)
                        ->where('close_date', $date)
                        ->first();
        // nullだったら未提出なので、現状の営業所人数を取得
        if(is_null($kintai_close)){
            foreach($employee_categories as $employee_category){
                $employee_count = Employee::where('base_id', $base_id)
                                    ->where('employee_category_id', $employee_category->employee_category_id)
                                    ->where('is_available', 1)
                                    ->count();
                $total_employee[$employee_category->employee_category_name] = $employee_count;
            }
        }
        // nullではなかったら提出済みなので、履歴に残っている営業所人数を取得
        if(!is_null($kintai_close)){
            foreach($employee_categories as $employee_category){
                $employee_count = $kintai_close->kintai_close_employees()
                                    ->where('kintai_close_employees.is_available', 1)
                                    ->where('employee_category_id', $employee_category->employee_category_id)
                                    ->count();
                $total_employee[$employee_category->employee_category_name] = $employee_count;
            }
        }
        return compact('base', 'total_employee');
    }

    public function getMonthDate($start_of_month, $end_of_month)
    {
        // 月の日数を取得
        $days = CarbonImmutable::parse($start_of_month)->daysInMonth;
        // 配列をセット
        $month_date = [];
        // 月初の日付をインスタンス化
        $start_day = CarbonImmutable::parse($start_of_month)->startOfMonth();
        // for文で月の日数をループ
        for($i = 0; $i < $days; $i++){
            // 月初の日付をインスタンス化
            $day = new CarbonImmutable($start_day);
            // 配列に月初の日付+日数の日付をセット
            $month_date[$i] = $day->addDays($i)->toDateString();
        }
        return $month_date;
    }

    public function getDownloadEmployee($start_of_month, $end_of_month, $base_id, $date)
    {
        // 勤怠提出テーブルから、ダウンロード条件のレコードを取得
        $kintai_close = KintaiClose::where('base_id', $base_id)
                            ->where('close_date', $date)
                            ->first();
        // nullだったら未提出なので、現状の営業所所属従業員を取得
        if(is_null($kintai_close)){
            $employees = Employee::where('employees.base_id', $base_id)
                            ->where('is_available', 1)
                            ->join('bases', 'bases.base_id', 'employees.base_id')
                            ->join('employee_categories', 'employee_categories.employee_category_id', 'employees.employee_category_id')
                            ->select('employee_id', 'employee_no', 'employee_last_name', 'employee_first_name', 'bases.base_id', 'base_name', 'employees.employee_category_id', 'over_time_start', 'employee_category_name')
                            ->orderBy('employees.employee_category_id', 'asc')
                            ->orderBy('employee_no', 'asc');
        }
        // nullではなかったら提出済みなので、履歴に残っている営業所所属従業員を取得
        if(!is_null($kintai_close)){
            $employees = KintaiClose::where('kintai_closes.base_id', $base_id)
                            ->where('close_date', $date)
                            ->join('kintai_close_employees', 'kintai_closes.kintai_close_id', 'kintai_close_employees.kintai_close_id')
                            ->where('kintai_close_employees.is_available', 1)
                            ->join('bases', 'bases.base_id', 'kintai_closes.base_id')
                            ->join('employee_categories', 'employee_categories.employee_category_id', 'kintai_close_employees.employee_category_id')
                            ->join('employees', 'employees.employee_id', 'kintai_close_employees.employee_id')
                            ->select('kintai_close_employees.employee_id', 'employees.employee_no', 'employees.employee_last_name', 'employees.employee_first_name', 'bases.base_id', 'bases.base_name', 'kintai_close_employees.employee_category_id', 'employees.over_time_start', 'employee_categories.employee_category_name')
                            ->orderBy('kintai_close_employees.employee_category_id', 'asc')
                            ->orderBy('employees.employee_no', 'asc');
        }
        return $employees;
    }

    public function getDownloadKintai($base_name, $month_date, $employees, $start_day, $end_day)
    {
        // 従業員が存在しない場合
        if ($employees->isEmpty()) {
            return null;
        }
        // employee_idを取得
        $employee_ids = $employees->pluck('employee_id')->all();
        // 月全体の勤怠を一括取得
        $all_kintais = Kintai::whereIn('employee_id', $employee_ids)
                            ->whereDate('work_day', '>=', $start_day)
                            ->whereDate('work_day', '<=', $end_day)
                            ->get()
                            ->groupBy('employee_id');
        // 集計を一括取得（employee_idごとにSUM）
        $totals = Kintai::whereIn('employee_id', $employee_ids)
                        ->whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->selectRaw('
                            employee_id,
                            SUM(working_time) as total_working_time,
                            SUM(over_time) as total_over_time,
                            SUM(late_night_over_time) as total_late_night_over_time,
                            SUM(late_night_working_time) as total_late_night_working_time
                        ')
                        ->groupBy('employee_id')
                        ->get()
                        ->keyBy('employee_id');
        // 応援稼働を一括取得
        $all_support = $this->getSupportWorkingTimeBulk($employee_ids, $start_day, $end_day);
        // 祝日稼働を一括取得
        $all_national_holiday = $this->getNationalHolidayTotalWorkingTimeBulk($employee_ids, $start_day, $end_day);
        $isShortTimeAvailable = Gate::allows('isShortTimeInfoAvailable');
        // 従業員の分だけループ処理
        foreach ($employees as $employee) {
            // emoloyee_idを取得
            $eid = $employee->employee_id;
            // 勤怠情報を格納する配列を用意
            $kintais[$eid] = [
                'employee_no'            => $employee->employee_no,
                'employee_name'          => $employee->employee_last_name . $employee->employee_first_name,
                'base_id'                => $employee->base_id,
                'base_name'              => $base_name,
                'employee_category_id'   => $employee->employee_category_id,
                'employee_category_name' => $employee->employee_category_name,
                'kintai'                 => [],
            ];
            // 従業員の勤怠をwork_dayをキーにしたコレクションに
            $employee_kintais = ($all_kintais[$eid] ?? collect())->keyBy('work_day');
            // 月の日付の分だけループ処理
            foreach ($month_date as $date) {
                // 1日の勤怠を取得
                $kintai = $employee_kintais->get($date);
                if ($employee->over_time_start != 0 && !is_null($kintai) && !$isShortTimeAvailable) {
                    $kintai->over_time = 0;
                }
                $kintais[$eid]['kintai'][$date] = $kintai;
            }
            // 稼働日数
            $kintais[$eid]['working_days'] = collect($kintais[$eid]['kintai'])->filter()->count();
            $total = $totals->get($eid);
            $kintais[$eid]['total_working_time']            = $total?->total_working_time ?? 0;
            $kintais[$eid]['total_over_time']               = $total?->total_over_time ?? 0;
            $kintais[$eid]['total_late_night_over_time']    = $total?->total_late_night_over_time ?? 0;
            $kintais[$eid]['total_late_night_working_time'] = $total?->total_late_night_working_time ?? 0;
            if ($employee->over_time_start != 0 && !$isShortTimeAvailable) {
                $kintais[$eid]['total_over_time'] = 0;
            }
            $kintais[$eid]['support_working_time']                 = $all_support[$eid] ?? collect();
            $kintais[$eid]['national_holiday_total_working_time']  = $all_national_holiday[$eid] ?? 0;
        }
        return isset($kintais) ? $kintais : null;
    }

    public function getSupportWorkingTimeBulk(array $employee_ids, $start_day, $end_day)
    {
        $rows = Kintai::whereIn('kintais.employee_id', $employee_ids)
                        ->whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                        ->join('bases', 'bases.base_id', 'kintai_details.customer_id')
                        ->selectRaw("
                            kintais.employee_id,
                            bases.base_id,
                            bases.base_name,
                            DATE_FORMAT(work_day, '%Y-%m') as date,
                            SUM(customer_working_time) as total_customer_working_time
                        ")
                        ->groupBy('kintais.employee_id', 'bases.base_id', 'bases.base_name', 'date')
                        ->orderBy('bases.base_id')
                        ->get()
                        ->groupBy('employee_id');
        return $rows;
    }

    // 国民の祝日に稼働した時間を取得
    public function getNationalHolidayTotalWorkingTimeBulk(array $employee_ids, $start_day, $end_day)
    {
        $national_holidays = Holiday::whereDate('holiday', '>=', $start_day)
                                    ->whereDate('holiday', '<=', $end_day)
                                    ->where('is_national_holiday', 1);
        $rows = Kintai::whereIn('kintais.employee_id', $employee_ids)
                        ->whereDate('work_day', '>=', $start_day)
                        ->whereDate('work_day', '<=', $end_day)
                        ->joinSub($national_holidays, 'HOLIDAY', function ($join) {
                            $join->on('kintais.work_day', '=', 'HOLIDAY.holiday');
                        })
                        ->selectRaw('employee_id, SUM(working_time) as total')
                        ->groupBy('employee_id')
                        ->get()
                        ->keyBy('employee_id');
        return $rows->map(fn($r) => $r->total)->all();
    }

    public function getOver40($month_date, $employees, $start_day, $end_day)
    {
        $over40 = [];
        $part_employees = $employees->filter(
            fn($e) => $e->employee_category_id == EmployeeCategoryEnum::PART_TIME_EMPLOYEE
        );
        if ($part_employees->isEmpty()) {
            return $over40;
        }
        $employee_ids = $part_employees->pluck('employee_id')->all();
        // 週単位で必要な日付範囲を収集
        $week_ranges = [];
        foreach ($month_date as $date) {
            $day = new CarbonImmutable($date);
            if ($day->isSunday()) {
                $from = $day->subDays(6)->toDateString();
                $to   = $day->toDateString();
                $week_ranges[$to] = [$from, $to];
            }
        }
        // 週ごと・従業員ごとに一括集計
        foreach ($week_ranges as $sunday => [$from, $to]) {
            $rows = Kintai::whereIn('employee_id', $employee_ids)
                ->whereDate('work_day', '>=', $from)
                ->whereDate('work_day', '<=', $to)
                ->selectRaw("
                    employee_id,
                    SUM(special_working_time) as total_special_working_time,
                    SUM(working_time) as total_working_time,
                    SUM(over_time) as total_over_time,
                    GREATEST((SUM(working_time) - SUM(over_time) - 2400), 0) as over40,
                    IF(
                        (SUM(special_working_time) - GREATEST((SUM(working_time) - SUM(over_time) - 2400), 0)) < 0
                        OR (SUM(special_working_time) IS NULL),
                        0,
                        (SUM(special_working_time) - GREATEST((SUM(working_time) - SUM(over_time) - 2400), 0))
                    ) as special_minus_over40,
                    DATE_FORMAT(work_day, '%v') as date
                ")
                ->groupBy('employee_id', 'date')
                ->get()
                ->keyBy('employee_id');
            foreach ($employee_ids as $eid) {
                if (!isset($over40[$eid])) {
                    $over40[$eid] = [];
                }
                $over40[$eid][$sunday] = $rows->get($eid);
            }
        }
        // 合計を計算
        foreach ($employee_ids as $eid) {
            $total_over40 = 0;
            $total_special = 0;
            foreach ($week_ranges as $sunday => $_) {
                $row = $over40[$eid][$sunday] ?? null;
                if ($row) {
                    $total_over40   += $row->over40 > 0 ? $row->over40 : 0;
                    $total_special  += $row->special_minus_over40 ?? 0;
                }
            }
            $over40[$eid]['total_over40']                = $total_over40;
            $over40[$eid]['total_special_working_time']  = $total_special;
        }
        return $over40;
    }

    public function getHolidays($start_day, $end_day)
    {
        // 対象月の祝日を取得
        $holidays = Holiday::whereDate('holiday', '>=', $start_day)
                            ->whereDate('holiday', '<=', $end_day)
                            ->get();
        // 配列に祝日を格納
        foreach($holidays as $holiday){
            $holiday_info[$holiday->holiday] = $holiday->holiday;
        }
        return isset($holiday_info) ? $holiday_info : array();
    }

    public function getDateInfo($month_date, $holidays)
    {
        $date_info = [];
        foreach ($month_date as $date) {
            $carbon = CarbonImmutable::parse($date);
            $is_holiday_style = $carbon->dayOfWeekIso >= 6 || isset($holidays[$date]);
            $date_info[$date] = [
                'formatted'  => $carbon->isoFormat('Y年MM月DD日(ddd)'),
                'is_sunday'  => $carbon->isSunday(),
                'cell_style' => $is_holiday_style ? 'background-color: #CCFFFF' : '',
            ];
        }
        return $date_info;
    }

    public function getTaiyoWorkingTimeAtHoliday($base, $month_date, $employees, $start_day, $end_day)
    {
        // 第1営業所のみ処理を実施
        if($base == '01_1st'){
            // 指定された期間の国民の祝日を取得
            $national_holidays = Holiday::whereDate('holiday', '>=', $start_day)
                                        ->whereDate('holiday', '<=', $end_day)
                                        ->where('is_national_holiday', 1);
            // 従業員数分だけループ処理
            foreach($employees as $employee){
                // 従業員区分がパートのみを対象とする
                if($employee->employee_category_id == EmployeeCategoryEnum::PART_TIME_EMPLOYEE){
                    // 国民の祝日に大洋製薬の稼働がある日を取得
                    $kintais = Kintai::where('employee_id', $employee->employee_id)
                                    ->joinSub($national_holidays, 'HOLIDAY', function ($join) {
                                        $join->on('kintais.work_day', '=', 'HOLIDAY.holiday');
                                    })
                                    ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                                    ->whereDate('work_day', '>=', $start_day)
                                    ->whereDate('work_day', '<=', $end_day)
                                    ->where('kintai_details.customer_id', '230829-016') // 大洋製薬のcustomer_idを設定
                                    ->select('kintais.work_day', 'kintais.employee_id')
                                    ->get();
                    // 配列に格納
                    foreach($kintais as $kintai){
                        $taiyo_working_times[$employee->employee_id][$kintai->work_day] = $kintai->work_day;
                    }
                }
            }
        }
        return isset($taiyo_working_times) ? $taiyo_working_times : array();
    }

    public function getDownloadFileName($month, $base_name)
    {
        // 出力年月をフォーマット
        $month = new CarbonImmutable($month);
        $month = $month->format('Y年m月');
        // ファイル名を作成
        $filename = '【'.$base_name.'】勤怠表_'.$month.'.pdf';
        return $filename;
    }

    public function passDownloadInfo($kintais, $month, $base, $over40, $holidays, $taiyo_working_times, $date_info)
    {
        // PDF出力ビューに情報を渡す
        $pdf = PDF::loadView('download.kintai_report.report', compact('kintais', 'month', 'base', 'over40', 'holidays', 'taiyo_working_times', 'date_info'));
        return $pdf;
    }
}