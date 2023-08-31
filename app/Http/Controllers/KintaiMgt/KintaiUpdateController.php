<?php

namespace App\Http\Controllers\KintaiMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\KintaiMgt\KintaiUpdateService;
use App\Http\Requests\KintaiCommentUpdateRequest;

class KintaiUpdateController extends Controller
{
    public function __construct()
    {
        // ミドルウェアを適用するメソッドを指定
        $this->middleware(['FindByKintai', 'CommentOperationAllAvailable'])->only(['comment_update']);
    }

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
}
