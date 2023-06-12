<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\IpLimit;

class CheckAccessIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // IPを取得
        $ip = $request->ip();
        // IPを条件にしてレコードを取得（有効フラグが1であるかも確認）
        $allowedIp = IpLimit::where('ip', $ip)->where('is_available', 1)->first();
        // テーブルに存在していない場合
        if(!$allowedIp){
            // 403ページを表示
            abort(403, 'Access Denied');
        }
        return $next($request);
    }
}
