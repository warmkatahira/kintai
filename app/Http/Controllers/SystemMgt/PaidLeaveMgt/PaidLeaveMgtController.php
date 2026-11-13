<?php

namespace App\Http\Controllers\SystemMgt\PaidLeaveMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaidLeaveMgt\PaidLeaveMgtService;
use App\Services\PaidLeaveMgt\PaidLeaveUploadService;
use App\Models\Base;
use App\Models\PaidLeave;
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class PaidLeaveMgtController extends Controller
{
    public function index()
    {
        // インスタンス化
        $PaidLeaveMgtService = new PaidLeaveMgtService;
        // セッションを削除
        $PaidLeaveMgtService->deleteSearchSession();
        // 初期条件をセット
        $PaidLeaveMgtService->setDefaultCondition();
        return $this->showIndex($PaidLeaveMgtService);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $PaidLeaveMgtService = new PaidLeaveMgtService;
        // セッションを削除
        $PaidLeaveMgtService->deleteSearchSession();
        // 検索条件をセット
        $PaidLeaveMgtService->setSearchCondition($request);
        return $this->showIndex($PaidLeaveMgtService);
    }

    private function showIndex(PaidLeaveMgtService $PaidLeaveMgtService)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 有給情報を取得
        $paid_leaves = $PaidLeaveMgtService->getPaidLeaveSearch()->paginate(50);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        return view('paid_leave_mgt.index')->with([
            'paid_leaves' => $paid_leaves,
            'bases' => $bases,
        ]);
    }

    public function download()
    {
        // インスタンス化
        $PaidLeaveMgtService = new PaidLeaveMgtService;
        // ダウンロードする有給情報を取得(検索条件で絞り込み)
        $download_data = $PaidLeaveMgtService->getDownloadPaidLeave();
        return (new FastExcel($download_data))->download('有給取得_'.CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv');
    }

    public function upload(Request $request)
    {
        try {
            $count = DB::transaction(function () use (&$request) {
                // インスタンス化
                $PaidLeaveUploadService = new PaidLeaveUploadService;
                // ファイルが選択されていなければ処理を中断
                if (!$request->hasFile('select_file')) {
                    throw new \Exception('ファイルが選択されていません。');
                }
                // 選択したデータをストレージにインポート
                $path = $PaidLeaveUploadService->importData($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $result = $PaidLeaveUploadService->checkHeader($path);
                // エラーがあれば処理を中断
                if (!is_null($result)) {
                    throw new \Exception($result);
                }
                // 追加するデータを配列に格納（同時にバリデーションも実施）
                $data = $PaidLeaveUploadService->setArrayImportData($path);
                // バリデーションエラーがあれば、エラー情報を出力
                if (count($data['validation_error']) != 0) {
                    throw new \Exception($PaidLeaveUploadService->makeValidationErrorMessage($data['validation_error']));
                }
                // 有給更新処理
                return $PaidLeaveUploadService->upsertPaidLeave($data['upload_data']);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '有給をアップロードしました。（'.$count.'件）',
        ]);
    }

    public function delete(Request $request)
    {
        // 有給を削除
        PaidLeave::getSpecify($request->paid_leave_id)->delete();
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '有給を削除しました。',
        ]);
    }
}
