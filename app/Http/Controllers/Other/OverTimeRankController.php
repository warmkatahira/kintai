<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Other\OverTimeRankService;

class OverTimeRankController extends Controller
{
    public function index()
    {
        // サービスクラスを定義
        $OverTimeRankService = new OverTimeRankService;
        // 初期条件をセット
        $OverTimeRankService->setDefaultCondition();
        // 正社員の残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeData();
        return view('other.over_time_rank.index')->with([
            'employees' => $employees,
        ]);
    }

    public function search(Request $request)
    {
        // サービスクラスを定義
        $OverTimeRankService = new OverTimeRankService;
        // 検索条件をセット
        $OverTimeRankService->getSearchCondition($request->search_month);
        // 正社員の残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeData();
        return view('other.over_time_rank.index')->with([
            'employees' => $employees,
        ]);
    }
}
