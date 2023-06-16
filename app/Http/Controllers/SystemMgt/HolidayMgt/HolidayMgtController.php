<?php

namespace App\Http\Controllers\SystemMgt\HolidayMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HolidayMgt\HolidayDownloadService;
use App\Services\HolidayMgt\HolidayUploadService;
use App\Models\Holiday;
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class HolidayMgtController extends Controller
{
    public function index()
    {
        // 拠点情報を取得
        $holidays = Holiday::getAll()->get();
        return view('holiday_mgt.index')->with([
            'holidays' => $holidays,
        ]);
    }

    public function download()
    {
        // インスタンス化
        $HolidayDownloadService = new HolidayDownloadService;
        // ダウンロードする休日マスタを取得
        $download_data = $HolidayDownloadService->getDownloadHoliday();
        return (new FastExcel($download_data))->download('休日マスタ_'.CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv');
    }

    public function upload(Request $request)
    {
        try {
            DB::transaction(function () use (&$request) {
                // インスタンス化
                $HolidayUploadService = new HolidayUploadService;
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 選択したデータをストレージにインポート
                $path = $HolidayUploadService->importData($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $result = $HolidayUploadService->checkHeader($path);
                // エラーがあれば処理を中断
                if (!is_null($result)) {
                    throw new \Exception($result);
                }
                // 追加するデータを配列に格納（同時にバリデーションも実施）
                $data = $HolidayUploadService->setArrayImportData($path);
                // バリデーションエラー配列の中にnull以外があれば、エラー情報を出力
                if (count(array_filter($data['validation_error'])) != 0) {
                    throw new \Exception('データが正しくない為、アップロードできませんでした。');
                }
                // 休日マスタ更新処理
                $HolidayUploadService->updateHoliday($data['upload_data']);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '休日マスタをアップロードしました。',
        ]);
    }
}
