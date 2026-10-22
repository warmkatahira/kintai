<?php

namespace App\Http\Middleware\Available;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\KintaiMgt\KintaiDispScopeService;

class KintaiDispScopeAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // インスタンス化
        $KintaiDispScopeService = new KintaiDispScopeService;
        // 勤怠表示範囲外の勤怠である
        if(!$KintaiDispScopeService->isDispKintai($request->kintai_id)){
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
}
