<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Other\OverTimeRankService;
use App\Services\CommonService;
use App\Models\Base;
use App\Models\EmployeeCategory;
use Carbon\CarbonImmutable;

class OverTimeRankController extends Controller
{
    public function index()
    {
        // インスタンス化
        $OverTimeRankService = new OverTimeRankService;
        $CommonService = new CommonService;
        // セッションを削除
        $OverTimeRankService->deleteSearchSession();
        // 初期条件をセット
        $OverTimeRankService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分情報を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 月初・月末の日付を取得
        $start_of_month = $CommonService->getStartOfMonth(session('search_work_day_from'));
        $end_of_month = $CommonService->getEndOfMonth(session('search_work_day_to'));
        // 残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_of_month, $end_of_month);
        return view('other.over_time_rank.index')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employees' => $employees,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $OverTimeRankService = new OverTimeRankService;
        $CommonService = new CommonService;
        // セッションを削除
        $OverTimeRankService->deleteSearchSession();
        // 検索条件をセット
        $OverTimeRankService->setSearchCondition($request);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分情報を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 月初・月末の日付を取得
        $start_of_month = $CommonService->getStartOfMonth(session('search_work_day_from'));
        $end_of_month = $CommonService->getEndOfMonth(session('search_work_day_to'));
        // 残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_of_month, $end_of_month);
        return view('other.over_time_rank.index')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employees' => $employees,
        ]);
    }

    public function download()
    {
        // インスタンス化
        $OverTimeRankService = new OverTimeRankService;
        $CommonService = new CommonService;
        // 月初・月末の日付を取得
        $start_of_month = $CommonService->getStartOfMonth(session('search_work_day_from'));
        $end_of_month = $CommonService->getEndOfMonth(session('search_work_day_to'));
        // 残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_of_month, $end_of_month);
        // ダウンロードするデータを取得
        $response = $OverTimeRankService->getDownloadOverTimeRank($employees);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=残業ランキング_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
