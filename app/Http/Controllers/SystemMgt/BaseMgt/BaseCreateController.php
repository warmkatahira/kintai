<?php

namespace App\Http\Controllers\SystemMgt\BaseMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BaseMgt\BaseCreateService;
use App\Models\Base;
use App\Http\Requests\BaseCreateRequest;

class BaseCreateController extends Controller
{
    public function index()
    {
        return view('base_mgt.create');
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
