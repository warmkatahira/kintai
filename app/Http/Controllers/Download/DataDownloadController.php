<?php

namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use App\Models\Base;
use App\Services\Download\DataDownloadService;
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
        $DataDownloadService = new DataDownloadService;
        // 日付をインスタンス化
        $start_day = new CarbonImmutable($request->from_date);
        $end_day = new CarbonImmutable($request->to_date);
        // ダウンロードデータを取得
        $data = $DataDownloadService->getDownloadData($request, $start_day, $end_day);
        // emptyであれば、出力するデータがないので、処理を中断
        if(empty($data['download_data'])){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'ダウンロードできるデータがありません。',
            ]);
        }
        return (new FastExcel($data['download_data']))->download($data['file_name']);
    }
}
