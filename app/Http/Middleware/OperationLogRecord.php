<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OperationLogRecord
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 出力するログを配列に格納
        $logData = [
            'id' => Auth::user()->id,
            'base' => Auth::user()->base->base_name,
            'user_name' => Auth::user()->last_name.' '.Auth::user()->first_name,
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'path' => $request->path(),
            'param' => $request->all(),
        ];
        // ログを出力
        Log::channel('operation')->info('Action executed', $logData);
        return $next($request);
    }
}
