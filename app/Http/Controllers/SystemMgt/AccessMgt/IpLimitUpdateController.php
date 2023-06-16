<?php

namespace App\Http\Controllers\SystemMgt\AccessMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccessMgt\IPLimitUpdateService;
use App\Models\IpLimit;
use Carbon\CarbonImmutable;
use App\Http\Requests\IpLimitCreateRequest;

class IpLimitUpdateController extends Controller
{
    public function index(Request $request)
    {
        // IPを取得
        $ip_limit = IpLimit::getSpecify($request->ip_limit_id)->first();
        return view('access_mgt.update')->with([
            'ip_limit' => $ip_limit,
        ]);
    }

    public function update(IpLimitCreateRequest $request)
    {
        // インスタンス化
        $IPLimitUpdateService = new IPLimitUpdateService;
        // レコードを変更
        $IPLimitUpdateService->updateIpLimit($request);
        return redirect()->route('access_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'IPを更新しました。',
        ]);
    }
}
