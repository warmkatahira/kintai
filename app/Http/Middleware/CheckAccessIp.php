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
            $base_id = Auth::user()->base_id;
            $base_name = Auth::user()->base->base_name;
            // ログインユーザーのユーザー名を取得
            $user = Auth::user()->last_name.Auth::user()->first_name;
            // アクセス管理のレコードを追加
            $this->createAccessIp($ip, $base_id);
            // チャットワークへ通知
            $this->postChatwork($user.'/'.$base_name.'/'.$ip);
            
            
            /* // ログアウトさせる
            auth()->logout();
            // 403ページを表示
            abort(403, $user.'/'.$base_name.'/'.$ip); */
        }
        return $next($request);
    }

    // アクセス管理のレコードを追加
    public function createAccessIp($ip, $base_id)
    {
        IpLimit::create([
            'ip' => $ip,
            'base_id' => $base_id,
            'is_available' => 1,
        ]);
        return;
    }

    // チャットワークへ通知
    public function postChatwork($info)
    {
        $access_token = '4a5298afe510a3cd79ef2b683e1e2287';
        //$url = 'https://api.chatwork.com/v2/rooms/354903263/messages';
        $url = 'https://api.chatwork.com/v2/rooms/383516897/messages';
        $message = "[info][title]IP追加連絡[/title]".$info."[/info]";
        // メッセージを投稿
        $data = array('body' => $message);
        $options = array(
            'http' => array(
                'header' => "X-ChatWorkToken: " . $access_token . "\r\n" .
                            "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return;
    }
}