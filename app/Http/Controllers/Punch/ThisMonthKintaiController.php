<?php

namespace App\Http\Controllers\Punch;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Services\CommonService;
use App\Services\Punch\ThisMonthKintaiService;
use App\Services\Download\KintaiReportDownloadService;
use App\Services\Punch\PunchFinishInputService;

class ThisMonthKintaiController extends Controller
{
    public function index()
    {
        // サービスクラスを定義
        $CommonService = new CommonService;
        $ThisMonthKintaiService = new ThisMonthKintaiService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 今月の月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth($nowDate);
        // 月単位で勤怠を集計・取得
        $month_kintais = $ThisMonthKintaiService->getMonthKintai($start_end_of_month['start'], $start_end_of_month['end']);
        return view('punch.this_month_kintai.index')->with([
            'month_kintais' => $month_kintais,
        ]);
    }

    public function detail(Request $request)
    {
        // サービスクラスを定義
        $CommonService = new CommonService;
        $ThisMonthKintaiService = new ThisMonthKintaiService;
        $KintaiReportDownloadService = new KintaiReportDownloadService;
        $PunchFinishInputService = new PunchFinishInputService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 今月の月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth($nowDate);
        // 従業員の情報を取得
        $employee = Employee::getSpecify($request->employee_id)->first();
        // 当月の情報を取得
        $month_date = $KintaiReportDownloadService->getMonthDate($start_end_of_month['start'], $start_end_of_month['end']);
        // 勤怠表に使用する情報を取得
        $kintais = $KintaiReportDownloadService->getDownloadKintai(Auth::user()->base->base_name, $month_date, Employee::getSpecify($request->employee_id), $start_end_of_month['start'], $start_end_of_month['end']);
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        return view('punch.this_month_kintai.detail')->with([
            'kintais' => $kintais,
            'employee' => $employee,
            'add_rest_available' => $add_rest_available,
        ]);
    }
}
