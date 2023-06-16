<?php

namespace App\Http\Controllers\SystemMgt\BaseMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BaseMgt\BaseUpdateService;
use App\Models\Base;
use App\Http\Requests\BaseUpdateRequest;

class BaseUpdateController extends Controller
{
    public function index(Request $request)
    {
        // 拠点を取得
        $base = Base::getSpecify($request->base_id)->first();
        return view('base_mgt.update')->with([
            'base' => $base,
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
