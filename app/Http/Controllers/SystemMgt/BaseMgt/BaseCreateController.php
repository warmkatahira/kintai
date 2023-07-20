<?php

namespace App\Http\Controllers\SystemMgt\BaseMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BaseMgt\BaseCreateService;
use App\Models\Base;
use App\Http\Requests\BaseCreateRequest;
use App\Enums\BaseEnum;

class BaseCreateController extends Controller
{
    public function index()
    {
        // 休憩関連取得モードを取得
        $rest_related_select_mode = BaseEnum::REST_RELATED_SELECT_MODE_LIST;
        return view('base_mgt.create')->with([
            'rest_related_select_mode' => $rest_related_select_mode,
        ]);
    }

    public function create(BaseCreateRequest $request)
    {
        // インスタンス化
        $BaseCreateService = new BaseCreateService;
        // レコードを変更
        $BaseCreateService->createBase($request);
        return redirect()->route('base_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => '拠点を追加しました。',
        ]);
    }
}
