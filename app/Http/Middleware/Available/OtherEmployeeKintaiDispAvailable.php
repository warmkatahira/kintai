<?php

namespace App\Http\Middleware\Available;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\RoleEnum;

class OtherEmployeeKintaiDispAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 勤怠表示範囲が自分のみの場合、403ページを表示
        if(Auth::user()->role->kintai_disp_scope == RoleEnum::KINTAI_DISP_SCOPE_SELF){
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
}
