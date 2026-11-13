<?php

namespace App\Http\Controllers\SystemMgt\PayrollDataDownload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Services\PayrollDataDownload\PayrollDataDownloadService;

class PayrollDataDownloadController extends Controller
{
    public function index()
    {
        return view('payroll_data_download.index');
    }

    public function download(Request $request)
    {
        // 年月が正しくなければ処理を中断
        $request->validate([
            'date' => 'required|date_format:Y-m',
        ], [], [
            'date' => 'ダウンロード年月',
        ]);
        // インスタンス化
        $PayrollDataDownloadService = new PayrollDataDownloadService;
        // ダウンロードするデータを取得
        $download_data = $PayrollDataDownloadService->getDownloadData($request->date);
        return (new FastExcel($download_data))->download('給与計算データ_'.CarbonImmutable::parse($request->date)->isoFormat('Y年MM月').'_'.CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv');
    }
}
