<?php

namespace App\Http\Controllers\KintaiCloseCheck;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\KintaiCloseCheck\KintaiCloseCheckService;

class KintaiCloseCheckController extends Controller
{
    public function index(Request $request)
    {
        // インスタンス化
        $KintaiCloseCheckService = new KintaiCloseCheckService;
        // セッションを削除
        $KintaiCloseCheckService->deleteSearchSession();
        // 初期条件をセット
        $KintaiCloseCheckService->setDefaultCondition();
        // 勤怠提出情報を取得
        $kintai_closes = $KintaiCloseCheckService->getKintaiCloseCheckSearch();
        return view('kintai_close_check.index')->with([
            'kintai_closes' => $kintai_closes,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $KintaiCloseCheckService = new KintaiCloseCheckService;
        // セッションを削除
        $KintaiCloseCheckService->deleteSearchSession();
        // 検索条件をセット
        $KintaiCloseCheckService->setSearchCondition($request);
        // 勤怠提出情報を取得
        $kintai_closes = $KintaiCloseCheckService->getKintaiCloseCheckSearch();
        return view('kintai_close_check.index')->with([
            'kintai_closes' => $kintai_closes,
        ]);
    }
}
