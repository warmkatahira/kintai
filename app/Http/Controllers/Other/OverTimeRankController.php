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
        // 日付条件が同じ月かどうかを判定するフラグを初期化
        $same_month_flg = false;
        // $start_dayと$end_dayが同じ月であればフラグを変更
        if(CarbonImmutable::parse($start_of_month)->format('Ym') == CarbonImmutable::parse($end_of_month)->format('Ym')){
            $same_month_flg = true;
        }
        // 前月の1日
        $first_day_of_previous_month = CarbonImmutable::parse($start_of_month)->subMonthNoOverflow()->startOfMonth()->toDateString();
        // 前月の末日
        $last_day_of_previous_month = CarbonImmutable::parse($start_of_month)->subMonthNoOverflow()->endOfMonth()->toDateString();
        // 残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_of_month, $end_of_month, $same_month_flg, $first_day_of_previous_month, $last_day_of_previous_month);
        return view('other.over_time_rank.index')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employees' => $employees,
            'same_month_flg' => $same_month_flg,
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
        // 日付条件が同じ月かどうかを判定するフラグを初期化
        $same_month_flg = false;
        // $start_dayと$end_dayが同じ月であればフラグを変更
        if(CarbonImmutable::parse($start_of_month)->format('Ym') == CarbonImmutable::parse($end_of_month)->format('Ym')){
            $same_month_flg = true;
        }
        // 前月の1日
        $first_day_of_previous_month = CarbonImmutable::parse($start_of_month)->subMonthNoOverflow()->startOfMonth()->toDateString();
        // 前月の末日
        $last_day_of_previous_month = CarbonImmutable::parse($start_of_month)->subMonthNoOverflow()->endOfMonth()->toDateString();
        // 残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_of_month, $end_of_month, $same_month_flg, $first_day_of_previous_month, $last_day_of_previous_month);
        return view('other.over_time_rank.index')->with([
            'bases' => $bases,
            'employee_categories' => $employee_categories,
            'employees' => $employees,
            'same_month_flg' => $same_month_flg,
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
        // 日付条件が同じ月かどうかを判定するフラグを初期化
        $same_month_flg = false;
        // $start_dayと$end_dayが同じ月であればフラグを変更
        if(CarbonImmutable::parse($start_of_month)->format('Ym') == CarbonImmutable::parse($end_of_month)->format('Ym')){
            $same_month_flg = true;
        }
        // 前月の1日
        $first_day_of_previous_month = CarbonImmutable::parse($start_of_month)->subMonthNoOverflow()->startOfMonth()->toDateString();
        // 前月の末日
        $last_day_of_previous_month = CarbonImmutable::parse($start_of_month)->subMonthNoOverflow()->endOfMonth()->toDateString();
        // 残業時間情報を取得
        $employees = $OverTimeRankService->getOverTimeRankSearch($start_of_month, $end_of_month, $same_month_flg, $first_day_of_previous_month, $last_day_of_previous_month);
        // ダウンロードするデータを取得
        $response = $OverTimeRankService->getDownloadOverTimeRank($employees, $same_month_flg);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=残業ランキング_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
