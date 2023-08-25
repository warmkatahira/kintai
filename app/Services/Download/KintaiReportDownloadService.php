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
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Gate;
use App\Enums\EmployeeCategoryEnum;

class KintaiReportDownloadService
{
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

    public function getDownloadEmployee($base_id)
    {
        // 出力対象の従業員を取得
        return Employee::where('employees.base_id', $base_id)
                        ->where('is_available', 1)
                        ->join('bases', 'bases.base_id', 'employees.base_id')
                        ->select('employee_id', 'employee_no', 'employee_last_name', 'employee_first_name', 'bases.base_id', 'base_name', 'employee_category_id', 'over_time_start')
                        ->orderBy('employee_no', 'asc');
    }

    public function getDownloadKintai($month_date, $employees, $start_day, $end_day)
    {
        // 従業員分だけループ処理
        $employees = $employees->get();
        foreach($employees as $employee){
            // 勤怠情報を格納する配列をセット
            $kintais[$employee->employee_id] = [];
            $kintais[$employee->employee_id]['employee_no'] = $employee->employee_no;
            $kintais[$employee->employee_id]['employee_name'] = $employee->employee_last_name.$employee->employee_first_name;
            $kintais[$employee->employee_id]['base_id'] = $employee->base_id;
            $kintais[$employee->employee_id]['base_name'] = $employee->base_name;
            $kintais[$employee->employee_id]['employee_category_name'] = $employee->employee_category->employee_category_name;
            // 月の日数分だけループ処理
            foreach($month_date as $date){
                // 該当日の勤怠を取得
                $kintai = Kintai::where('work_day', $date)->where('employee_id', $employee->employee_id)->first();
                // 時短勤務者であり、勤怠があって、時短情報が無効の場合、残業時間を0にする
                if($employee->over_time_start != 0 && !is_null($kintai) && !Gate::allows('isShortTimeInfoAvailable')){
                    $kintai->over_time = 0;
                }
                // 配列に格納
                $kintais[$employee->employee_id]['kintai'][$date] = $kintai;
            }
            // 稼働日数を取得（配列の数 - 配列のnullの数）
            $kintais[$employee->employee_id]['working_days'] = count($kintais[$employee->employee_id]['kintai']) - count(array_keys($kintais[$employee->employee_id]['kintai'], null));
            // 総稼働時間を取得
            $kintais[$employee->employee_id]['total_working_time'] = Kintai::where('employee_id', $employee->employee_id)
                                                                        ->whereDate('work_day', '>=', $start_day)
                                                                        ->whereDate('work_day', '<=', $end_day)
                                                                        ->sum('working_time');
            // 総残業時間を取得
            $kintais[$employee->employee_id]['total_over_time'] = Kintai::where('employee_id', $employee->employee_id)
                                                                        ->whereDate('work_day', '>=', $start_day)
                                                                        ->whereDate('work_day', '<=', $end_day)
                                                                        ->sum('over_time');
            // 時短勤務者であり、時短情報が無効の場合、総残業時間を0にする
            if($employee->over_time_start != 0 && !Gate::allows('isShortTimeInfoAvailable')){
                $kintais[$employee->employee_id]['total_over_time'] = 0;
            }
            // 応援稼働時間を取得 
            $kintais[$employee->employee_id]['support_working_time'] = $this->getSupportWorkingTime($employee->employee_id, $start_day, $end_day);
            // 国民の祝日の総稼働時間を取得
            $kintais[$employee->employee_id]['national_holiday_total_working_time'] = $this->getNationalHolidayTotalWorkingTime($employee->employee_id, $start_day, $end_day);
        }
        // 出力するデータがなければ、nullを返す
        return isset($kintais) ? $kintais : null;
    }

    public function getSupportWorkingTime($employee_id, $start_day, $end_day)
    {
        // 従業員を取得
        $employee = Employee::getSpecify($employee_id);
        // 応援稼働時間を取得
        $support_working_times = Kintai::whereDate('work_day', '>=', $start_day)
                ->whereDate('work_day', '<=', $end_day)
                ->joinSub($employee, 'EMPLOYEE', function ($join) {
                    $join->on('kintais.employee_id', '=', 'EMPLOYEE.employee_id');
                })
                ->join('kintai_details', 'kintai_details.kintai_id', 'kintais.kintai_id')
                ->join('bases', 'bases.base_id', 'kintai_details.customer_id')
                ->select(DB::raw("sum(customer_working_time) as total_customer_working_time, EMPLOYEE.employee_id, EMPLOYEE.employee_no, bases.base_id, DATE_FORMAT(work_day, '%Y-%m') as date, bases.base_name"))
                ->groupBy('EMPLOYEE.employee_id', 'EMPLOYEE.employee_no', 'bases.base_id', 'bases.base_name', 'date')
                ->orderBy('EMPLOYEE.employee_no', 'asc')
                ->orderBy('bases.base_id', 'asc')
                ->get();
        return $support_working_times;
    }

    // 国民の祝日に稼働した時間を取得
    public function getNationalHolidayTotalWorkingTime($employee_id, $start_day, $end_day)
    {
        // 従業員を取得
        $employee = Employee::getSpecify($employee_id);
        // 国民の祝日を取得
        $national_holidays = Holiday::whereDate('holiday', '>=', $start_day)
                                ->whereDate('holiday', '<=', $end_day)
                                ->where('is_national_holiday', 1);
        // 稼働時間を取得
        $national_holiday_total_working_time = Kintai::joinSub($employee, 'EMPLOYEE', function ($join) {
                    $join->on('kintais.employee_id', '=', 'EMPLOYEE.employee_id');
                })
                ->whereDate('work_day', '>=', $start_day)
                ->whereDate('work_day', '<=', $end_day)
                ->joinSub($national_holidays, 'HOLIDAY', function ($join) {
                    $join->on('kintais.work_day', '=', 'HOLIDAY.holiday');
                })
                ->sum('working_time');
        return $national_holiday_total_working_time;
    }

    public function getOver40($month_date, $employees, $start_day, $end_day)
    {
        // 従業員数分だけループ処理
        foreach($employees->get() as $employee){
            // 従業員区分がパートのみを対象とする
            if($employee->employee_category_id == EmployeeCategoryEnum::PART_TIME_EMPLOYEE){
                // 情報を格納する配列をセット
                $over40[$employee->employee_id] = [];
                // 月の日数分だけループ処理
                foreach($month_date as $date){
                    // 日付をインスタンス化
                    $to_day = new CarbonImmutable($date);
                    // 日曜日だったら、週40時間超過情報を取得
                    if($to_day->isSunday()){
                        // 同週の月曜日の日付を取得
                        $from_day = new CarbonImmutable($to_day);
                        $from_day = $from_day->subDays(6);
                        // フォーマット変換
                        $from_day = $from_day->toDateString();
                        $to_day = $to_day->toDateString();
                        // 週40時間超過情報を取得
                        $over40[$employee->employee_id][$date] = Kintai::where('employee_id', $employee->employee_id)
                                                                    ->whereDate('work_day', '>=', $from_day)
                                                                    ->whereDate('work_day', '<=', $to_day)
                                                                    ->select(DB::raw("sum(working_time) as total_working_time, sum(over_time) as total_over_time, (sum(working_time) - sum(over_time) - 2400) as over40, DATE_FORMAT(work_day, '%v') as date"))
                                                                    ->groupBy('employee_id', 'date')
                                                                    ->first();
                    }
                }
            }
        }
        return isset($over40) ? $over40 : array();
    }

    public function getBase($base_id)
    {
        // 出力対象の営業所を取得
        $base = Base::getSpecify($base_id)->first();
        // 従業員区分を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 従業員区分毎の人数を取得
        foreach($employee_categories as $employee_category){
            $employee_count = Employee::where('base_id', $base_id)
                                ->where('employee_category_id', $employee_category->employee_category_id)
                                ->count();
            $total_employee[$employee_category->employee_category_name] = $employee_count;
        }
        return compact('base', 'total_employee');
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

    public function getTaiyoWorkingTimeAtHoliday($base, $month_date, $employees, $start_day, $end_day)
    {
        // 第1営業所のみ処理を実施
        if($base == '01_1st'){
            // 指定された期間の国民の祝日を取得
            $national_holidays = Holiday::whereDate('holiday', '>=', $start_day)
                                        ->whereDate('holiday', '<=', $end_day)
                                        ->where('is_national_holiday', 1);
            // 従業員数分だけループ処理
            foreach($employees->get() as $employee){
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
                                    ->where('kintai_details.customer_id', '230606-003') // 大洋製薬のcustomer_idを設定
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

    public function passDownloadInfo($kintais, $month, $base, $over40, $holidays, $taiyo_working_times)
    {
        // PDF出力ビューに情報を渡す
        $pdf = PDF::loadView('download.kintai_report.report', compact('kintais', 'month', 'base', 'over40', 'holidays', 'taiyo_working_times'));
        return $pdf;
    }
}