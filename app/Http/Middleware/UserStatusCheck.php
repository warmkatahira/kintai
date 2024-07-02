<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserStatusCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ステータスが1以外のユーザーはログインさせない
        if (Auth::user()->status == 0) {
            // ログアウトさせる
            Auth::logout();
            // welcomeページへ遷移
            return redirect()->route('welcome.index')->with([
                'alert_type' => 'error',
                'alert_message' => 'アカウント承認がされていません。',
            ]);
        }
        return $next($request);
    }
}
