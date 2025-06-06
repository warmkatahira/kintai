<?php

use Illuminate\Support\Facades\Route;
// +-+-+-+-+-+-+-+- Welcome +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Welcome\WelcomeController;
// +-+-+-+-+-+-+-+- IP共有 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\IpShare\IpShareController;
// +-+-+-+-+-+-+-+- TOP +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Top\TopController;
// +-+-+-+-+-+-+-+- 打刻 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Punch\PunchController;
    use App\Http\Controllers\Punch\PunchBeginController;
    use App\Http\Controllers\Punch\PunchFinishController;
    use App\Http\Controllers\Punch\PunchOutController;
    use App\Http\Controllers\Punch\PunchReturnController;
    use App\Http\Controllers\Punch\TodayKintaiController;
    use App\Http\Controllers\Punch\ThisMonthKintaiController;
    use App\Http\Controllers\Punch\PunchUpdateController;
// +-+-+-+-+-+-+-+- 勤怠管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\KintaiMgt\KintaiMgtController;
    use App\Http\Controllers\KintaiMgt\KintaiDeleteController;
    use App\Http\Controllers\KintaiMgt\KintaiUpdateController;
    use App\Http\Controllers\KintaiMgt\BaseCheckController;
// +-+-+-+-+-+-+-+- 従業員管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\EmployeeMgt\EmployeeMgtController;
    use App\Http\Controllers\EmployeeMgt\EmployeeCreateController;
    use App\Http\Controllers\EmployeeMgt\EmployeeUpdateController;
// +-+-+-+-+-+-+-+- 拠点管理 +-+-+-+-+-+-+-+-
    // +-+-+-+-+-+-+-+- 手動打刻 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\BaseMgt\PunchManual\PunchManualBeginOnlyController;
    use App\Http\Controllers\BaseMgt\PunchManual\PunchManualController;
    // +-+-+-+-+-+-+-+- 荷主管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\BaseMgt\CustomerMgt\CustomerMgtController;
    use App\Http\Controllers\BaseMgt\CustomerMgt\CustomerCreateController;
    use App\Http\Controllers\BaseMgt\CustomerMgt\CustomerUpdateController;
    // +-+-+-+-+-+-+-+- 荷主グループ管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\BaseMgt\CustomerGroupMgt\CustomerGroupMgtController;
    use App\Http\Controllers\BaseMgt\CustomerGroupMgt\CustomerGroupCreateController;
    use App\Http\Controllers\BaseMgt\CustomerGroupMgt\CustomerGroupUpdateController;
    // +-+-+-+-+-+-+-+- 勤怠提出 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\BaseMgt\KintaiClose\KintaiCloseController;
// +-+-+-+-+-+-+-+- ダウンロード +-+-+-+-+-+-+-+-
    // +-+-+-+-+-+-+-+- 勤怠表 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Download\KintaiReportDownloadController;
    // +-+-+-+-+-+-+-+- データ +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Download\DataDownloadController;
// +-+-+-+-+-+-+-+- その他 +-+-+-+-+-+-+-+-
    // +-+-+-+-+-+-+-+- 残業ランキング +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Other\OverTimeRankController;
    // +-+-+-+-+-+-+-+- 荷主稼働ランキング +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Other\CustomerWorkingTimeRankController;
    // +-+-+-+-+-+-+-+- 荷主割合確認 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Other\CustomerRateCheckController;
    // +-+-+-+-+-+-+-+- 派遣利用入力 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\Other\TemporaryUseController;
// +-+-+-+-+-+-+-+- 経理管理 +-+-+-+-+-+-+-+-
    // +-+-+-+-+-+-+-+- 勤怠提出確認 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\AccountingMgt\KintaiCloseCheckController;
// +-+-+-+-+-+-+-+- システム管理 +-+-+-+-+-+-+-+-
    // +-+-+-+-+-+-+-+- ユーザー管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\SystemMgt\UserMgt\UserMgtController;
    use App\Http\Controllers\SystemMgt\UserMgt\UserUpdateController;
    // +-+-+-+-+-+-+-+- 権限管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\SystemMgt\RoleMgt\RoleMgtController;
    use App\Http\Controllers\SystemMgt\RoleMgt\RoleCreateController;
    use App\Http\Controllers\SystemMgt\RoleMgt\RoleUpdateController;
    // +-+-+-+-+-+-+-+- 休日管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\SystemMgt\HolidayMgt\HolidayMgtController;
    // +-+-+-+-+-+-+-+- アクセス管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\SystemMgt\AccessMgt\AccessMgtController;
    use App\Http\Controllers\SystemMgt\AccessMgt\IpLimitCreateController;
    use App\Http\Controllers\SystemMgt\AccessMgt\IpLimitUpdateController;
    use App\Http\Controllers\SystemMgt\AccessMgt\IpLimitDeleteController;
    // +-+-+-+-+-+-+-+- 拠点管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\SystemMgt\BaseMgt\BaseMgtController;
    use App\Http\Controllers\SystemMgt\BaseMgt\BaseCreateController;
    use App\Http\Controllers\SystemMgt\BaseMgt\BaseUpdateController;
    // +-+-+-+-+-+-+-+- 派遣会社管理 +-+-+-+-+-+-+-+-
    use App\Http\Controllers\SystemMgt\TemporaryCompanyMgt\TemporaryCompanyMgtController;
    use App\Http\Controllers\SystemMgt\TemporaryCompanyMgt\TemporaryCompanyCreateController;
    use App\Http\Controllers\SystemMgt\TemporaryCompanyMgt\TemporaryCompanyUpdateController;

// ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ Welcome ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    // -+-+-+-+-+-+-+-+-+-+-+-+ Welcome -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(WelcomeController::class)->prefix('')->name('welcome.')->group(function(){
        Route::get('', 'index')->name('index');
    });
// ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ IP共有 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    // -+-+-+-+-+-+-+-+-+-+-+-+ IP共有 -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(IpShareController::class)->prefix('ipshare')->name('ipshare.')->group(function(){
        Route::post('share', 'share')->name('share');
    });
// ログインとステータスチェック
Route::middleware(['auth', 'userStatusCheck', 'OperationLogRecord', 'IPCheck'])->group(function () {
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ Top ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    // -+-+-+-+-+-+-+-+-+-+-+-+ TOP -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(TopController::class)->prefix('top')->name('top.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 打刻 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    // -+-+-+-+-+-+-+-+-+-+-+-+ 打刻トップ -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(PunchController::class)->prefix('punch')->name('punch.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    // -+-+-+-+-+-+-+-+-+-+-+-+ 出勤打刻 -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(PunchBeginController::class)->prefix('punch_begin')->name('punch_begin.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::post('enter', 'enter')->name('enter')->middleware('kintaiCloseCheck');
    });
    // -+-+-+-+-+-+-+-+-+-+-+-+ 退勤打刻 -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(PunchFinishController::class)->prefix('punch_finish')->name('punch_finish.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::get('input', 'input')->name('input')->middleware('kintaiCloseCheck');
        Route::post('enter', 'enter')->name('enter');
    });
    // -+-+-+-+-+-+-+-+-+-+-+-+ 外出打刻 -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(PunchOutController::class)->prefix('punch_out')->name('punch_out.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::post('enter', 'enter')->name('enter')->middleware('kintaiCloseCheck');
    });
    // -+-+-+-+-+-+-+-+-+-+-+-+ 戻り打刻 -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(PunchReturnController::class)->prefix('punch_return')->name('punch_return.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::post('enter', 'enter')->name('enter')->middleware('kintaiCloseCheck');
    });
    // -+-+-+-+-+-+-+-+-+-+-+-+ 今日の勤怠 -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(TodayKintaiController::class)->prefix('today_kintai')->name('today_kintai.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    // -+-+-+-+-+-+-+-+-+-+-+-+ 今月の勤怠 -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(ThisMonthKintaiController::class)->prefix('this_month_kintai')->name('this_month_kintai.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::get('detail', 'detail')->name('detail');
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 勤怠管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    Route::middleware(['KintaiMgtFuncAvailable'])->group(function () {
        // -+-+-+-+-+-+-+-+-+-+-+-+ 勤怠管理 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(KintaiMgtController::class)->prefix('kintai_mgt')->name('kintai_mgt.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('detail', 'detail')->name('detail');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 勤怠削除 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(KintaiDeleteController::class)->prefix('kintai_delete')->name('kintai_delete.')->group(function(){
            Route::post('', 'delete')->name('delete');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 勤怠修正 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(KintaiUpdateController::class)->prefix('kintai_update')->name('kintai_update.')->group(function(){
            Route::post('comment_update', 'comment_update')->name('comment_update');
            Route::post('special_working_time_update', 'special_working_time_update')->name('special_working_time_update');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 修正打刻 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(PunchUpdateController::class)->prefix('punch_update')->name('punch_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('input', 'input')->name('input')->middleware('kintaiCloseCheck');
            Route::post('enter', 'enter')->name('enter');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 拠点確認 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(BaseCheckController::class)->prefix('kintai_update')->name('kintai_update.')->group(function(){
            Route::post('', 'base_check')->name('base_check')->middleware('BaseCheckAvailable');
        });
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 従業員管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    Route::middleware(['EmployeeMgtFuncAvailable'])->group(function () {
        // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員管理 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(EmployeeMgtController::class)->prefix('employee_mgt')->name('employee_mgt.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('detail', 'detail')->name('detail');
            Route::get('download', 'download')->name('download');
        });
        Route::middleware(['EmployeeOperationAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員追加 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(EmployeeCreateController::class)->prefix('employee_create')->name('employee_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(EmployeeUpdateController::class)->prefix('employee_update')->name('employee_update.')->group(function(){
                Route::get('', 'index')->name('index')->middleware('EmployeeUpdateAvailable');
                Route::post('update', 'update')->name('update');
            });
        });
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 拠点管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    Route::middleware(['BaseMgtFuncAvailable'])->group(function () {
        Route::middleware(['ManualPunchAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 手動打刻(出勤のみ) -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(PunchManualBeginOnlyController::class)->prefix('punch_manual_begin_only')->name('punch_manual_begin_only.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('enter', 'enter')->name('enter')->middleware('kintaiCloseCheck');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 手動打刻 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(PunchManualController::class)->prefix('punch_manual')->name('punch_manual.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::get('input', 'input')->name('input')->middleware('kintaiCloseCheck');
                Route::post('enter', 'enter')->name('enter');
            });
        });
        Route::middleware(['CustomerMgtFuncAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(CustomerMgtController::class)->prefix('customer_mgt')->name('customer_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::get('search', 'search')->name('search');
                Route::get('detail', 'detail')->name('detail');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主追加 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(CustomerCreateController::class)->prefix('customer_create')->name('customer_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(CustomerUpdateController::class)->prefix('customer_update')->name('customer_update.')->group(function(){
                Route::get('', 'index')->name('index')->middleware('CustomerUpdateAvailable');
                Route::post('update', 'update')->name('update');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主グループ管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(CustomerGroupMgtController::class)->prefix('customer_group_mgt')->name('customer_group_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::get('search', 'search')->name('search');
                Route::get('detail', 'detail')->name('detail');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主グループ追加 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(CustomerGroupCreateController::class)->prefix('customer_group_create')->name('customer_group_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主グループ更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(CustomerGroupUpdateController::class)->prefix('customer_group_update')->name('customer_group_update.')->group(function(){
                Route::get('', 'index')->name('index')->middleware('CustomerGroupUpdateAvailable');
                Route::post('update', 'update')->name('update');
            });
        });
        Route::middleware(['KintaiCloseAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 勤怠提出 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(KintaiCloseController::class)->prefix('kintai_close')->name('kintai_close.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('close', 'close')->name('close');
            });
        });
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ ダウンロード ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    Route::middleware(['DownloadFuncAvailable'])->group(function () {
        Route::middleware(['KintaiReportDownloadAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 勤怠表 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(KintaiReportDownloadController::class)->prefix('kintai_report_download')->name('kintai_report_download.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::get('download', 'download')->name('download');
            });
        });
        Route::middleware(['DataDownloadAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ データ -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(DataDownloadController::class)->prefix('data_download')->name('data_download.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::get('download', 'download')->name('download');
            });
        });
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ その他 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    Route::middleware(['OtherFuncAvailable'])->group(function () {
        // -+-+-+-+-+-+-+-+-+-+-+-+ 残業ランキング -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(OverTimeRankController::class)->prefix('over_time_rank')->name('over_time_rank.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('download', 'download')->name('download');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主稼働ランキング -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(CustomerWorkingTimeRankController::class)->prefix('customer_working_time_rank')->name('customer_working_time_rank.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('detail', 'detail')->name('detail');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主割合確認 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(CustomerRateCheckController::class)->prefix('customer_rate_check')->name('customer_rate_check.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('download', 'download')->name('download');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 派遣利用 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(TemporaryUseController::class)->prefix('temporary_use')->name('temporary_use.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('create', 'create_index')->name('create_index');
            Route::post('create', 'create')->name('create');
            Route::post('delete', 'delete')->name('delete');
            Route::get('download', 'download')->name('download');
        });
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 経理管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    Route::middleware(['AccountingMgtFuncAvailable'])->group(function () {
        // -+-+-+-+-+-+-+-+-+-+-+-+ 勤怠提出確認 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(KintaiCloseCheckController::class)->prefix('kintai_close_check')->name('kintai_close_check.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
        });
    });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ システム管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
    Route::middleware(['SystemMgtFuncAvailable'])->group(function () {
        Route::middleware(['UserMgtAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ ユーザー管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(UserMgtController::class)->prefix('user_mgt')->name('user_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::get('search', 'search')->name('search');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ ユーザー更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(UserUpdateController::class)->prefix('user_update')->name('user_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
        });
        Route::middleware(['RoleMgtAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 権限管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(RoleMgtController::class)->prefix('role_mgt')->name('role_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 権限追加 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(RoleCreateController::class)->prefix('role_create')->name('role_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 権限更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(RoleUpdateController::class)->prefix('role_update')->name('role_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
        });
        Route::middleware(['HolidayMgtAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 休日管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(HolidayMgtController::class)->prefix('holiday_mgt')->name('holiday_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::get('download', 'download')->name('download');
                Route::post('upload', 'upload')->name('upload');
            });
        });
        Route::middleware(['AccessMgtAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ アクセス管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(AccessMgtController::class)->prefix('access_mgt')->name('access_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ IP追加 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(IpLimitCreateController::class)->prefix('ip_limit_create')->name('ip_limit_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ IP更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(IpLimitUpdateController::class)->prefix('ip_limit_update')->name('ip_limit_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ IP削除 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(IpLimitDeleteController::class)->prefix('ip_limit_delete')->name('ip_limit_delete.')->group(function(){
                Route::post('delete', 'delete')->name('delete');
            });
        });
        Route::middleware(['BaseMgtAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 拠点管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(BaseMgtController::class)->prefix('base_mgt')->name('base_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ IP追加 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(BaseCreateController::class)->prefix('base_create')->name('base_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ IP更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(BaseUpdateController::class)->prefix('base_update')->name('base_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
        });
        Route::middleware(['TemporaryCompanyMgtAvailable'])->group(function () {
            // -+-+-+-+-+-+-+-+-+-+-+-+ 派遣会社管理 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(TemporaryCompanyMgtController::class)->prefix('temporary_company_mgt')->name('temporary_company_mgt.')->group(function(){
                Route::get('', 'index')->name('index');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 派遣会社追加 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(TemporaryCompanyCreateController::class)->prefix('temporary_company_create')->name('temporary_company_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            // -+-+-+-+-+-+-+-+-+-+-+-+ 派遣会社更新 -+-+-+-+-+-+-+-+-+-+-+-+
            Route::controller(TemporaryCompanyUpdateController::class)->prefix('temporary_company_update')->name('temporary_company_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
        });
    });
});


require __DIR__.'/auth.php';
