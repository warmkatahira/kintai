<?php

namespace App\Services\KintaiCloseCheck;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Models\KintaiClose;
use App\Models\Base;

class KintaiCloseCheckService
{
    public function deleteSearchSession()
    {
        // セッションを削除
        session()->forget([
            'search_date',
        ]);
        return;
    }

    public function setDefaultCondition()
    {
        // 初期条件をセット
        session(['search_date' => CarbonImmutable::now()->format('Y-m')]);
        return;
    }

    public function setSearchCondition($request)
    {
        // セッションに検索条件をセット
        session(['search_date' => $request->search_date]);
        return;
    }

    public function getKintaiCloseCheckSearch()
    {
        // 指定された年月の勤怠提出情報を取得
        $kintai_closes = KintaiClose::where('close_date', session('search_date'));
        // 拠点と勤怠提出情報を結合
        $kintai_closes = Base::where('bases.base_id', '!=', '10_SOUKO')
            ->leftJoinSub($kintai_closes, 'SUB', function ($join) {
                $join->on('bases.base_id', '=', 'SUB.base_id');
            })
            ->select('bases.base_id', 'bases.base_name', 'SUB.updated_at', DB::raw("'".session('search_date')."' as close_date"))
            ->orderBy('bases.base_id', 'asc')
            ->get();
        return $kintai_closes;
    }
}