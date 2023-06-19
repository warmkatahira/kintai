<?php

namespace App\Http\Middleware\Available;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class KintaiReportDownloadAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 無効の場合、403ページを表示
        if(Auth::user()->role->is_kintai_report_download_available == 0){
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
}
