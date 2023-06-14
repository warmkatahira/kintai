<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Other\OverTimeRankService;
use App\Services\CommonService;
use App\Models\Base;
use App\Models\EmployeeCategory;

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
        $start_end_of_month = $CommonService->getStartEndOfMonth(session('search_date'));
        // 正社員の残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_end_of_month['start'], $start_end_of_month['end']);
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
        $start_end_of_month = $CommonService->getStartEndOfMonth(session('search_date'));
        // 正社員の残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_end_of_month['start'], $start_end_of_month['end']);
        return view('other.over_time_rank.index')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employees' => $employees,
        ]);
    }
}
