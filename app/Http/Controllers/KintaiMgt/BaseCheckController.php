<?php

namespace App\Http\Controllers\KintaiMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\KintaiMgt\BaseCheckService;
use Carbon\CarbonImmutable;

class BaseCheckController extends Controller
{
    public function base_check(Request $request)
    {
        // インスタンス化
        $BaseCheckService = new BaseCheckService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 拠点確認ユーザーIDと拠点確認日時を更新
        $BaseCheckService->updateBaseCheck($request->chk, $nowDate);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '拠点確認を実行しました。',
        ]);
    }
}
