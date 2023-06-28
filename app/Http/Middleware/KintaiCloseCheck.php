<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\CarbonImmutable;
use App\Models\KintaiClose;
use Illuminate\Support\Facades\Auth;

class KintaiCloseCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ロック後の勤怠操作が有効であれば通す
        if(Auth::user()->role->is_lock_kintai_operation_available == 1){
            return $next($request);
        }
        // 提出情報を取得する日付を取得（出勤日のパラメータがあればそちらを使用）
        if(isset($request->work_day)){
            $work_day = new CarbonImmutable($request->work_day);
            $close_date = $work_day->format('Y-m');
        }else{
            // 現在の日時を取得
            $nowDate = CarbonImmutable::now();
            $close_date = $nowDate->format('Y-m');
        }
        // 勤怠提出情報を取得
        $kintai_close = KintaiClose::where('close_date', $close_date)
                            ->where('base_id', Auth::user()->base_id)
                            ->count();
        // 勤怠提出されているか確認し、提出されていれば直前の画面に戻す
        if($kintai_close > 0){
            return redirect()->route('top.index')->with([
                'alert_type' => 'error',
                'alert_message' => $close_date.'の勤怠は提出されている為、打刻できません。',
            ]);
        }
        return $next($request);
    }
}
