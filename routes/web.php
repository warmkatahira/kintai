<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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
// +-+-+-+-+-+-+-+- 従業員管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\EmployeeMgt\EmployeeMgtController;
use App\Http\Controllers\EmployeeMgt\EmployeeCreateController;
use App\Http\Controllers\EmployeeMgt\EmployeeUpdateController;
// +-+-+-+-+-+-+-+- 拠点管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Punch\PunchManualController;
// +-+-+-+-+-+-+-+- 荷主管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\CustomerMgt\CustomerMgtController;
use App\Http\Controllers\CustomerMgt\CustomerCreateController;
use App\Http\Controllers\CustomerMgt\CustomerUpdateController;
// +-+-+-+-+-+-+-+- 荷主グループ管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\CustomerGroupMgt\CustomerGroupMgtController;
use App\Http\Controllers\CustomerGroupMgt\CustomerGroupCreateController;
use App\Http\Controllers\CustomerGroupMgt\CustomerGroupUpdateController;

Route::get('/', function () {
    return view('welcome');
});

// ログインとステータスチェック
Route::middleware(['auth'])->group(function () {
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
            Route::post('enter', 'enter')->name('enter');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 退勤打刻 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(PunchFinishController::class)->prefix('punch_finish')->name('punch_finish.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('input', 'input')->name('input');
            Route::post('enter', 'enter')->name('enter');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 外出打刻 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(PunchOutController::class)->prefix('punch_out')->name('punch_out.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('enter', 'enter')->name('enter');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 戻り打刻 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(PunchReturnController::class)->prefix('punch_return')->name('punch_return.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('enter', 'enter')->name('enter');
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
            Route::post('base_check', 'base_check')->name('base_check');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 修正打刻 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(PunchUpdateController::class)->prefix('punch_update')->name('punch_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('input', 'input')->name('input');
            Route::post('enter', 'enter')->name('enter');
        });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 従業員管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
        // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員管理 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(EmployeeMgtController::class)->prefix('employee_mgt')->name('employee_mgt.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('detail', 'detail')->name('detail');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員追加 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(EmployeeCreateController::class)->prefix('employee_create')->name('employee_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員更新 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(EmployeeUpdateController::class)->prefix('employee_update')->name('employee_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 拠点管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
        // -+-+-+-+-+-+-+-+-+-+-+-+ 手動打刻 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(PunchManualController::class)->prefix('punch_manual')->name('punch_manual.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('input', 'input')->name('input');
            Route::post('enter', 'enter')->name('enter');
        });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 荷主管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
        // -+-+-+-+-+-+-+-+-+-+-+-+ 荷主管理 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(CustomerMgtController::class)->prefix('customer_mgt')->name('customer_mgt.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('search', 'search')->name('search');
            Route::get('detail', 'detail')->name('detail');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員追加 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(CustomerCreateController::class)->prefix('customer_create')->name('customer_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        // -+-+-+-+-+-+-+-+-+-+-+-+ 従業員更新 -+-+-+-+-+-+-+-+-+-+-+-+
        Route::controller(CustomerUpdateController::class)->prefix('customer_update')->name('customer_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
    // ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆ 荷主グループ管理 ★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆
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
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });


});


require __DIR__.'/auth.php';
