<?php

namespace App\Http\Controllers\KintaiClose;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\KintaiClose\KintaiCloseService;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class KintaiCloseController extends Controller
{
    public function index(Request $request)
    {
        // インスタンス化
        $KintaiCloseService = new KintaiCloseService;
        // ロックがかかっていない自拠点の勤怠の年月を取得
        $kintais = $KintaiCloseService->getNotCloseKintai();
        return view('kintai_close.index')->with([
            'kintais' => $kintais,
        ]);
    }

    public function close(Request $request)
    {
        try {
            DB::transaction(function () use (&$request) {
                // インスタンス化
                $KintaiCloseService = new KintaiCloseService;
                $CommonService = new CommonService;
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 月初・月末の日付を取得
                $start_end_of_month = $CommonService->getStartEndOfMonth(CarbonImmutable::parse($request->close_date));
                // 指定した年月の勤怠で拠点確認が未実施の勤怠数を取得
                $count = $KintaiCloseService->checkKintai($start_end_of_month);
                // 未実施の勤怠があれば処理を中断
                if($count > 0){
                    throw new \Exception($count.'件の拠点確認未実施の勤怠がある為、提出できません。');
                }
                // 勤怠提出テーブルを追加
                $KintaiCloseService->createKintaiClose($request->close_date);
                // locked_atを更新
                $KintaiCloseService->updateLockedAt($nowDate, $start_end_of_month);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '勤怠を提出しました。',
        ]);
    }
}
