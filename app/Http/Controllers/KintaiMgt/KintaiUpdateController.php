<?php

namespace App\Http\Controllers\KintaiMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\KintaiMgt\KintaiUpdateService;
use App\Http\Requests\KintaiCommentUpdateRequest;
use Carbon\CarbonImmutable;

class KintaiUpdateController extends Controller
{
    public function comment_update(KintaiCommentUpdateRequest $request)
    {
        // インスタンス化
        $KintaiUpdateService = new KintaiUpdateService;
        // コメントを更新
        $KintaiUpdateService->updateComment($request->kintai_id, $request->comment);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'コメントを更新しました。',
        ]);
    }

    public function base_check(Request $request)
    {
        // インスタンス化
        $KintaiUpdateService = new KintaiUpdateService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 拠点確認日時を更新
        $KintaiUpdateService->updateBaseCheck($request->chk, $nowDate);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '拠点確認を実行しました。',
        ]);
    }
}
