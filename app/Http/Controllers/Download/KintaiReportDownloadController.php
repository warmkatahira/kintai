<?php

namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Models\Base;
use App\Services\Download\KintaiReportDownloadService;
use App\Services\CommonService;

class KintaiReportDownloadController extends Controller
{
    public function index()
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        return view('download.kintai_report.index')->with([
            'bases' => $bases,
        ]);
    }

    public function download(Request $request)
    {
        // インスタンス化
        $KintaiReportDownloadService = new KintaiReportDownloadService;
        $CommonService = new CommonService;
        // 出力対象の営業所情報を取得
        $base = $KintaiReportDownloadService->getBase($request->base_id, $request->date);
        // 月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth($request->date);
        // 月の情報を取得
        $month_date = $KintaiReportDownloadService->getMonthDate($start_end_of_month['start'], $start_end_of_month['end']);
        // 出力対象の従業員を取得
        $employees = $KintaiReportDownloadService->getDownloadEmployee($request->base_id, $request->date);
        // 出力する勤怠情報を取得
        $kintais = $KintaiReportDownloadService->getDownloadKintai($base['base']['base_name'], $month_date, $employees, $start_end_of_month['start'], $start_end_of_month['end']);
        // nullであれば、出力するデータがないので、処理を中断
        if(is_null($kintais)){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'ダウンロードできる勤怠がありません。',
            ]);
        }
        // 週40時間超過情報を取得
        $over40 = $KintaiReportDownloadService->getOver40($month_date, $employees, $start_end_of_month['start'], $start_end_of_month['end']);
        // 対象月の祝日を取得
        $holidays = $KintaiReportDownloadService->getHolidays($start_end_of_month['start'], $start_end_of_month['end']);
        // 国民の祝日に大洋製薬の稼働がある日を取得
        $taiyo_working_times = $KintaiReportDownloadService->getTaiyoWorkingTimeAtHoliday($request->base_id, $month_date, $employees, $start_end_of_month['start'], $start_end_of_month['end']);
        // ファイル名を取得
        $filename = $KintaiReportDownloadService->getDownloadFileName($request->date, $base['base']['base_name']);
        // PDF出力ビューに情報を渡す
        $pdf = $KintaiReportDownloadService->passDownloadInfo($kintais, $request->date, $base, $over40, $holidays, $taiyo_working_times);
        // ファイル名を設定してPDFをダウンロード
        return $pdf->download($filename);
    }
}
