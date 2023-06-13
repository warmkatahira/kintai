<?php

namespace App\Providers;

 use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // 勤怠管理機能有効のみ許可
        Gate::define('isKintaiMgtFuncAvailable', function($user){
            return ($user->role->is_kintai_mgt_func_available == 1);
        });
        // 拠点確認有効のみ許可
        Gate::define('isBaseCheckAvailable', function($user){
            return ($user->role->is_base_check_available == 1);
        });
        // 勤怠操作有効のみ許可
        Gate::define('isKintaiOperationAvailable', function($user){
            return ($user->role->is_kintai_operation_available == 1);
        });
        // 従業員管理機能有効のみ許可
        Gate::define('isEmployeeMgtFuncAvailable', function($user){
            return ($user->role->is_employee_mgt_func_available == 1);
        });
        // 従業員操作有効のみ許可
        Gate::define('isEmployeeOperationAvailable', function($user){
            return ($user->role->is_employee_operation_available == 1);
        });
        // 拠点管理機能有効のみ許可
        Gate::define('isBaseMgtFuncAvailable', function($user){
            return ($user->role->is_base_mgt_func_available == 1);
        });
        // 手動打刻有効のみ許可
        Gate::define('isManualPunchAvailable', function($user){
            return ($user->role->is_manual_punch_available == 1);
        });
        // 荷主管理機能有効のみ許可
        Gate::define('isCustomerMgtFuncAvailable', function($user){
            return ($user->role->is_customer_mgt_func_available == 1);
        });
        // 勤怠提出有効のみ許可
        Gate::define('isKintaiCloseAvailable', function($user){
            return ($user->role->is_kintai_close_available == 1);
        });
        // ダウンロード機能有効のみ許可
        Gate::define('isDownloadFuncAvailable', function($user){
            return ($user->role->is_download_func_available == 1);
        });
        // 経理管理機能有効のみ許可
        Gate::define('isAccountingMgtFuncAvailable', function($user){
            return ($user->role->is_accounting_mgt_func_available == 1);
        });
        // システム管理機能有効のみ許可
        Gate::define('isSystemMgtFuncAvailable', function($user){
            return ($user->role->is_system_mgt_func_available == 1);
        });
        // ユーザー管理有効のみ許可
        Gate::define('isUserMgtAvailable', function($user){
            return ($user->role->is_user_mgt_available == 1);
        });
        // 権限管理有効のみ許可
        Gate::define('isRoleMgtAvailable', function($user){
            return ($user->role->is_role_mgt_available == 1);
        });
        // 休日管理有効のみ許可
        Gate::define('isHolidayMgtAvailable', function($user){
            return ($user->role->is_holiday_mgt_available == 1);
        });
        // アクセス管理有効のみ許可
        Gate::define('isAccessMgtAvailable', function($user){
            return ($user->role->is_access_mgt_available == 1);
        });
        // ロック後の勤怠操作有効のみ許可
        Gate::define('isLockKintaiOperationAvailable', function($user){
            return ($user->role->is_lock_kintai_operation_available == 1);
        });
        // 全勤怠操作有効のみ許可
        Gate::define('isAllKintaiOperationAvailable', function($user){
            return ($user->role->is_all_kintai_operation_available == 1);
        });
        // 拠点やロック有無などを全て考慮して操作できる勤怠であるか
        Gate::define('isKintaiOperationAllAvailable', function($user, $base_id, $locked_at) {
            return (
                // 勤怠操作が有効である
                $user->role->is_kintai_operation_available == 1 &&
                // 自拠点の勤怠であるか、全勤怠操作が有効である
                ($user->base_id == $base_id || $user->role->is_all_kintai_operation_available == 1) &&
                // ロックされていないか、ロックされている場合、ロック後の勤怠操作が有効である
                (is_null($locked_at) || (!is_null($locked_at) && $user->role->is_lock_kintai_operation_available == 1))
            );
        });
        // 拠点確認が実施できる勤怠であるか
        Gate::define('isBaseCheckAvailableKintai', function($user, $kintai){
            return (
                // 拠点確認が有効
                $user->role->is_base_check_available == 1 &&
                // ロックされていない
                is_null($kintai->locked_at) &&
                // 退勤されている
                !is_null($kintai->finish_time) &&
                // 自拠点と同じ勤怠
                $user->base_id == $kintai->base_id
            );
        });
    }
}
