<?php

namespace App\Http\Controllers\SystemMgt\AccessMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccessMgt\IpLimitUpdateService;
use App\Models\IpLimit;
use Carbon\CarbonImmutable;
use App\Http\Requests\IpLimitCreateRequest;
use App\Models\Base;

class IpLimitUpdateController extends Controller
{
    public function index(Request $request)
    {
        // IPを取得
        $ip_limit = IpLimit::getSpecify($request->ip_limit_id)->first();
        // 拠点を全て取得
        $bases = Base::getAll()->get();
        return view('access_mgt.update')->with([
            'ip_limit' => $ip_limit,
            'bases' => $bases,
        ]);
    }

    public function update(IpLimitCreateRequest $request)
    {
        // インスタンス化
        $IpLimitUpdateService = new IpLimitUpdateService;
        // レコードを変更
        $IpLimitUpdateService->updateIpLimit($request);
        return redirect()->route('access_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'IPを更新しました。',
        ]);
    }
}
