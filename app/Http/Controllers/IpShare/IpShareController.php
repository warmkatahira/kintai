<?php

namespace App\Http\Controllers\IpShare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IpShareController extends Controller
{
    public function share(Request $request)
    {
        $access_token = '00e3e3cd814793bb6ff7115af3ae506e';
        $url = 'https://api.chatwork.com/v2/rooms/354903263/messages';
        $account_id = '3281641';
        $message = "[info][title]IP変更連絡[/title]".$request->info."[/info]";
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
        return redirect()->route('welcome.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'システム担当者へ連絡しました。',
        ]);
    }
}
