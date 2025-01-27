<?php

namespace App\Http\Controllers\SystemMgt\AccessMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccessMgt\IpLimitCreateService;
use App\Models\IpLimit;
use Carbon\CarbonImmutable;
use App\Http\Requests\IpLimitCreateRequest;
use App\Models\Base;

class IpLimitCreateController extends Controller
{
    public function index()
    {
        // 拠点を全て取得
        $bases = Base::getAll()->get();
        return view('access_mgt.create')->with([
            'bases' => $bases,
        ]);
    }

    public function create(IpLimitCreateRequest $request)
    {
        // インスタンス化
        $IpLimitCreateService = new IpLimitCreateService;
        // レコードを変更
        $IpLimitCreateService->createIpLimit($request);
        return redirect()->route('access_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'IPを追加しました。',
        ]);
    }
}
