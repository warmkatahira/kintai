<?php

namespace App\Http\Controllers\EmployeeMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmployeeMgt\EmployeeMgtService;
use App\Services\EmployeeMgt\EmployeeCsvService;
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class EmployeeCsvController extends Controller
{
    public function download()
    {
        // インスタンス化
        $EmployeeMgtService = new EmployeeMgtService;
        $EmployeeCsvService = new EmployeeCsvService;
        // 従業員を取得(従業員管理の検索条件で絞り込み)
        $employees = $EmployeeMgtService->getEmployeeSearch();
        // ダウンロードするデータを取得
        $download_data = $EmployeeCsvService->getDownloadData($employees);
        return (new FastExcel($download_data))->download('従業員マスタ_'.CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv');
    }

    public function upload(Request $request)
    {
        try {
            $count = DB::transaction(function () use (&$request) {
                // インスタンス化
                $EmployeeCsvService = new EmployeeCsvService;
                // ファイルが選択されていなければ処理を中断
                if (!$request->hasFile('select_file')) {
                    throw new \Exception('ファイルが選択されていません。');
                }
                // 選択したデータをストレージにインポート
                $path = $EmployeeCsvService->importData($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $result = $EmployeeCsvService->checkHeader($path);
                // エラーがあれば処理を中断
                if (!is_null($result)) {
                    throw new \Exception($result);
                }
                // 更新するデータを配列に格納（同時にバリデーションも実施）
                $data = $EmployeeCsvService->setArrayImportData($path);
                // バリデーションエラーがあれば、エラー情報を出力
                if (count($data['validation_error']) != 0) {
                    throw new \Exception($EmployeeCsvService->makeValidationErrorMessage($data['validation_error']));
                }
                // 従業員更新処理
                return $EmployeeCsvService->upsertEmployee($data['upload_data']);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '従業員マスタをアップロードしました。（追加：'.$count['create_count'].'件／更新：'.$count['update_count'].'件）',
        ]);
    }
}
