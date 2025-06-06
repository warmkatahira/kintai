<?php

namespace App\Http\Controllers\EmployeeMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Base;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use Carbon\CarbonImmutable;
use App\Services\EmployeeMgt\EmployeeMgtService;
use App\Services\Employee\EmployeeRegisterService;
use App\Services\Download\KintaiReportDownloadService;
use App\Services\Punch\PunchFinishInputService;
use App\Services\CommonService;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Auth;
use App\Enums\EmployeeMgtEnum;

class EmployeeMgtController extends Controller
{
    public function __construct()
    {
        // ミドルウェアを適用するメソッドを指定
        $this->middleware('FindByEmployee')->only(['detail']);
    }

    public function index()
    {
        // インスタンス化
        $EmployeeMgtService = new EmployeeMgtService;
        // セッションを削除
        $EmployeeMgtService->deleteSearchSession();
        // 初期条件をセット
        $EmployeeMgtService->setDefaultCondition();
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 従業員を取得
        $employees = $EmployeeMgtService->getEmployeeSearch();
        // ページネーションを実施
        $employees = $EmployeeMgtService->setPaginate($employees);
        // ステータス情報を取得($xx->aaの形式でアクセスできるようにしている)
        $statuses = collect(StatusEnum::PULLDOWN_LIST)->map(function ($item) {
            return (object) $item;
        });
        // Enumに定義してある条件値を取得
        $sort_order_conditions = EmployeeMgtEnum::SORT_ORDER_CONDITIONS;
        return view('employee_mgt.index')->with([
            'bases' => $bases,
            'employees' => $employees,
            'employee_categories' => $employee_categories,
            'statuses' => $statuses,
            'sort_order_conditions' => $sort_order_conditions,
        ]);
    }

    public function search(Request $request)
    {
        // インスタンス化
        $EmployeeMgtService = new EmployeeMgtService;
        // セッションを削除
        $EmployeeMgtService->deleteSearchSession();
        // 検索条件をセット
        $EmployeeMgtService->setSearchCondition($request);
        // 拠点情報を取得
        $bases = Base::getAll()->get();
        // 従業員区分を取得
        $employee_categories = EmployeeCategory::getAll()->get();
        // 従業員を取得
        $employees = $EmployeeMgtService->getEmployeeSearch();
        // ページネーションを実施
        $employees = $EmployeeMgtService->setPaginate($employees);
        // ステータス情報を取得($xx->aaの形式でアクセスできるようにしている)
        $statuses = collect(StatusEnum::PULLDOWN_LIST)->map(function ($item) {
            return (object) $item;
        });
        // Enumに定義してある条件値を取得
        $sort_order_conditions = EmployeeMgtEnum::SORT_ORDER_CONDITIONS;
        return view('employee_mgt.index')->with([
            'bases' => $bases,
            'employees' => $employees,
            'employee_categories' => $employee_categories,
            'statuses' => $statuses,
            'sort_order_conditions' => $sort_order_conditions,
        ]);
    }

    public function detail(Request $request)
    {
        // 現在のURLを取得
        session(['back_url_2' => url()->full()]);
        // インスタンス化
        $EmployeeMgtService = new EmployeeMgtService;
        $KintaiReportDownloadService = new KintaiReportDownloadService;
        $CommonService = new CommonService;
        $PunchFinishInputService = new PunchFinishInputService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 今月の月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth($nowDate);
        // 従業員の情報を取得
        $employee = Employee::getSpecify($request->employee_id)->first();
        // 当月稼働情報を取得
        $this_month_data = $EmployeeMgtService->getThisMonthData($start_end_of_month['start'], $start_end_of_month['end'], $request->employee_id);
        // 荷主稼働時間トップ3の情報を取得
        $customer_working_time = $EmployeeMgtService->getCustomerWorkingTime($start_end_of_month['start'], $start_end_of_month['end'], $request->employee_id);
        // 当月の日数情報を取得
        $month_date = $KintaiReportDownloadService->getMonthDate($start_end_of_month['start'], $start_end_of_month['end']);
        // 勤怠表に使用する情報を取得
        $kintais = $KintaiReportDownloadService->getDownloadKintai(Auth::user()->base->base_name, $month_date, Employee::getSpecify($request->employee_id), $start_end_of_month['start'], $start_end_of_month['end']);
        // 追加休憩取得時間が有効か判定
        $add_rest_available = $PunchFinishInputService->checkAddRestAvailable();
        return view('employee_mgt.detail')->with([
            'employee' => $employee,
            'working_days' => is_null($this_month_data) ? 0 : $this_month_data->working_days,
            'total_working_time' => is_null($this_month_data) ? 0 : $this_month_data->total_working_time,
            'total_over_time' => is_null($this_month_data) ? 0 : $this_month_data->total_over_time,
            'customer_working_time' => $customer_working_time,
            'month_date' => $month_date,
            'kintais' => $kintais,
            'add_rest_available' => $add_rest_available,
        ]);
    }

    public function download(Request $request)
    {
        // インスタンス化
        $EmployeeMgtService = new EmployeeMgtService;
        // 従業員を取得
        $employees = $EmployeeMgtService->getEmployeeSearch();
        // ダウンロードするデータを取得
        $response = $EmployeeMgtService->getDownloadEmployee($employees);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=従業員情報_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }    
}