<?php

namespace App\Http\Controllers\BaseMgt\KintaiClose;

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
                // ステータスが有効で勤怠が1つもない従業員を取得
                $kintai_nothing_employees = $KintaiCloseService->getNotingKintaiEmployee($start_end_of_month);
                // 対象の方がいるかつ、セッションが空であれば初めての提出処理なので、処理を中断して警告を表示する
                if(count($kintai_nothing_employees) > 0 && empty(session('kintai_nothing_employees'))){
                    session(['kintai_nothing_employees' => $kintai_nothing_employees]);
                    throw new \Exception('勤怠がない従業員の方がいます。<br>退職者でないか確認して下さい。');
                }
                // セッションを削除
                session()->forget(['kintai_nothing_employees']);
                // 勤怠提出テーブルを追加
                $kintai_close = $KintaiCloseService->createKintaiClose($request->close_date);
                // 勤怠提出従業員テーブルを追加
                $KintaiCloseService->createKintaiCloseEmployee($kintai_close);
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
