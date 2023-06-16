<?php

namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Models\Base;
use App\Services\Download\DataDownloadService;
use App\Services\CommonService;
use App\Enums\DataDownloadEnum;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Requests\DataDownloadRequest;

class DataDownloadController extends Controller
{
    public function index()
    {
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // ダウンロード項目を取得
        $download_lists = DataDownloadEnum::DOWNLOAD_LIST;
        return view('download.data.index')->with([
            'bases' => $bases,
            'download_lists' => $download_lists,
        ]);
    }

    public function download(DataDownloadRequest $request)
    {
        // インスタンス化
        $CommonService = new CommonService;
        $DataDownloadService = new DataDownloadService;
        // 月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth($request->date);
        // ダウンロードデータを取得
        $data = $DataDownloadService->getDownloadData($request, $start_end_of_month['start'], $start_end_of_month['end']);
        return (new FastExcel($data['download_data']))->download($data['file_name']);
    }
}
