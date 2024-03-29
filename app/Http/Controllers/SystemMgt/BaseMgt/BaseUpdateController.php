<?php

namespace App\Http\Controllers\SystemMgt\BaseMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BaseMgt\BaseUpdateService;
use App\Models\Base;
use App\Http\Requests\BaseUpdateRequest;
use App\Enums\BaseEnum;

class BaseUpdateController extends Controller
{
    public function index(Request $request)
    {
        // 拠点を取得
        $base = Base::getSpecify($request->base_id)->first();
        // 休憩関連取得モードを取得
        $rest_related_select_mode = BaseEnum::REST_RELATED_SELECT_MODE_LIST;
        return view('base_mgt.update')->with([
            'base' => $base,
            'rest_related_select_mode' => $rest_related_select_mode,
        ]);
    }

    public function update(BaseUpdateRequest $request)
    {
        // インスタンス化
        $BaseUpdateService = new BaseUpdateService;
        // レコードを変更
        $BaseUpdateService->updateBase($request);
        return redirect()->route('base_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => '拠点を更新しました。',
        ]);
    }
}
