<?php

namespace App\Http\Controllers\KintaiMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\KintaiMgt\KintaiDeleteService;

class KintaiDeleteController extends Controller
{
    public function delete(Request $request)
    {
        // インスタンス化
        $KintaiDeleteService = new KintaiDeleteService;
        // 勤怠を削除
        $KintaiDeleteService->deleteKintai($request->kintai_id);
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '勤怠を削除しました。',
        ]);
    }
}
