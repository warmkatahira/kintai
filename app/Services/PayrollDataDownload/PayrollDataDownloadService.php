<?php

namespace App\Services\PayrollDataDownload;

use App\Models\Base;
use App\Services\CommonService;
use App\Services\Download\KintaiReportDownloadService;
use Carbon\CarbonImmutable;
use App\Enums\EmployeeCategoryEnum;

class PayrollDataDownloadService
{
    // CSVのヘッダー
    public static function csvHeader()
    {
        return ['年月', '営業所名', '従業員番号', '氏名', '出勤日数', '定時', '残業', '深夜残業', '有給取得日数', '交通費'];
    }

    // 給与計算データを取得(勤怠表と同じ集計方法で全営業所分を作成)
    public function getDownloadData($date)
    {
        // インスタンス化
        $KintaiReportDownloadService = new KintaiReportDownloadService;
        $CommonService = new CommonService;
        // 月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth($date);
        $start = $start_end_of_month['start'];
        $end = $start_end_of_month['end'];
        // 月の情報を取得
        $month_date = $KintaiReportDownloadService->getMonthDate($start, $end);
        // 出力する年月
        $month = CarbonImmutable::parse($start)->isoFormat('Y年MM月');
        $download_data = [];
        // 営業所の分だけループ処理
        foreach (Base::getAll()->get() as $base) {
            // 出力対象の従業員を取得(勤怠提出済みなら提出時の従業員)
            // パートのみを対象とする
            $employees = $KintaiReportDownloadService->getDownloadEmployee($start, $end, $base->base_id, $date)->get()
                            ->filter(fn($employee) => $employee->employee_category_id == EmployeeCategoryEnum::PART_TIME_EMPLOYEE)
                            ->values();
            // 勤怠情報を取得
            $kintais = $KintaiReportDownloadService->getDownloadKintai($base->base_name, $month_date, $employees, $start, $end);
            // 従業員がいなければ次の営業所へ
            if (is_null($kintais)) {
                continue;
            }
            // 週40時間超過情報を取得
            $over40 = $KintaiReportDownloadService->getOver40($month_date, $employees, $start, $end);
            // 有給取得情報を取得
            $paid_leaves = $KintaiReportDownloadService->getPaidLeaves($employees, $start, $end);
            // 従業員の分だけループ処理
            foreach ($kintais as $employee_id => $kintai) {
                // 定時・残業・深夜残業の時間(分)を取得 ※勤怠表と同じ計算
                $time_breakdown = $KintaiReportDownloadService->getTimeBreakdown($kintai, $over40[$employee_id] ?? []);
                $download_data[] = [
                    '年月' => $month,
                    '営業所名' => $base->base_name,
                    '従業員番号' => $kintai['employee_no'],
                    '氏名' => $kintai['employee_name'],
                    '出勤日数' => $this->blankIfZero($kintai['working_days']),
                    '定時' => $this->formatHour($time_breakdown['regular']),
                    '残業' => $this->formatHour($time_breakdown['over']),
                    '深夜残業' => $this->formatHour($time_breakdown['late_night_over']),
                    // 有給取得日数(有給テーブルから)
                    '有給取得日数' => $this->blankIfZero((string)(float)array_sum($paid_leaves[$employee_id] ?? [])),
                    // 交通費(従業員マスタの支給形態に応じて計算)
                    '交通費' => $this->blankIfZero($kintai['commuting_allowance']['total']),
                ];
            }
        }
        // データが空ならヘッダーだけを出力
        if (empty($download_data)) {
            return [array_fill_keys(self::csvHeader(), '')];
        }
        return $download_data;
    }

    // 分を時間(小数点2桁)に変換
    private function formatHour($minutes)
    {
        return $this->blankIfZero(number_format($minutes / 60, 2, '.', ''));
    }

    // 0の場合は空欄にする
    private function blankIfZero($value)
    {
        return (float)$value == 0 ? '' : $value;
    }
}
