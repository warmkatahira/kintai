<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\CheckAccessIp::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        // ステータスチェック
        'userStatusCheck' => \App\Http\Middleware\UserStatusCheck::class,
        // 勤怠提出チェック
        'kintaiCloseCheck' => \App\Http\Middleware\KintaiCloseCheck::class,
        // 操作ログ記録
        'OperationLogRecord' => \App\Http\Middleware\OperationLogRecord::class,
        // 権限チェック
        'AccessMgtAvailable' => \App\Http\Middleware\Available\AccessMgtAvailable::class,
        'AccountingMgtFuncAvailable' => \App\Http\Middleware\Available\AccountingMgtFuncAvailable::class,
        'BaseCheckAvailable' => \App\Http\Middleware\Available\BaseCheckAvailable::class,
        'BaseMgtFuncAvailable' => \App\Http\Middleware\Available\BaseMgtFuncAvailable::class,
        'CustomerMgtFuncAvailable' => \App\Http\Middleware\Available\CustomerMgtFuncAvailable::class,
        'DownloadFuncAvailable' => \App\Http\Middleware\Available\DownloadFuncAvailable::class,
        'OtherFuncAvailable' => \App\Http\Middleware\Available\OtherFuncAvailable::class,
        'EmployeeMgtFuncAvailable' => \App\Http\Middleware\Available\EmployeeMgtFuncAvailable::class,
        'EmployeeOperationAvailable' => \App\Http\Middleware\Available\EmployeeOperationAvailable::class,
        'HolidayMgtAvailable' => \App\Http\Middleware\Available\HolidayMgtAvailable::class,
        'KintaiCloseAvailable' => \App\Http\Middleware\Available\KintaiCloseAvailable::class,
        'KintaiMgtFuncAvailable' => \App\Http\Middleware\Available\KintaiMgtFuncAvailable::class,
        'ManualPunchAvailable' => \App\Http\Middleware\Available\ManualPunchAvailable::class,
        'RoleMgtAvailable' => \App\Http\Middleware\Available\RoleMgtAvailable::class,
        'SystemMgtFuncAvailable' => \App\Http\Middleware\Available\SystemMgtFuncAvailable::class,
        'UserMgtAvailable' => \App\Http\Middleware\Available\UserMgtAvailable::class,
        'KintaiOperationAllAvailable' => \App\Http\Middleware\Available\KintaiOperationAllAvailable::class,
        'EmployeeUpdateAvailable' => \App\Http\Middleware\Available\EmployeeUpdateAvailable::class,
        'BaseMgtAvailable' => \App\Http\Middleware\Available\BaseMgtAvailable::class,
        'KintaiReportDownloadAvailable' => \App\Http\Middleware\Available\KintaiReportDownloadAvailable::class,
        'DataDownloadAvailable' => \App\Http\Middleware\Available\DataDownloadAvailable::class,
        // レコード存在チェック
        'FindByKintai' => \App\Http\Middleware\FindBy\FindByKintai::class,
        'FindByEmployee' => \App\Http\Middleware\FindBy\FindByEmployee::class,
        'FindByCustomer' => \App\Http\Middleware\FindBy\FindByCustomer::class,
        'FindByCustomerGroup' => \App\Http\Middleware\FindBy\FindByCustomerGroup::class,
    ];
}
