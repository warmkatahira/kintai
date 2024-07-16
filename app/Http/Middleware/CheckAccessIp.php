<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\IpLimit;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckAccessIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 開発環境ではチェックしないようにしている
        /* if(env('APP_ENV') == 'local'){
            return $next($request);
        } */
        // userのno_ip_checkがtrueならチェックをしない
        if(Auth::user()->no_ip_check){
            return $next($request);
        }
        // 接続しているネットワークのIPを取得
        $ip = $request->ip();
        // IPを条件にしてレコードを取得（有効フラグが1であるかも確認）
        $allowedIp = IpLimit::where('ip', $ip)->where('is_available', 1)->first();
        // IPがアクセス可能な設定としてテーブルに存在していない場合
        if(!$allowedIp){
            // ログインユーザーの拠点を取得
            $base_name = Auth::user()->base->base_name;
            // ユーザーを取得
            $user = Auth::user()->last_name.Auth::user()->first_name;
            // ログアウトさせる
            auth()->logout();
            // 403ページを表示
            abort(403, $user.'/'.$base_name.'/'.$ip);
        }
        return $next($request);
    }
}
