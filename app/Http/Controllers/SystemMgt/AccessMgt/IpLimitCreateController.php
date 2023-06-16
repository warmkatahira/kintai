<?php

namespace App\Http\Controllers\SystemMgt\AccessMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccessMgt\IPLimitCreateService;
use App\Models\IpLimit;
use Carbon\CarbonImmutable;
use App\Http\Requests\IpLimitCreateRequest;

class IpLimitCreateController extends Controller
{
    public function index()
    {
        return view('access_mgt.create');
    }

    public function create(IpLimitCreateRequest $request)
    {
        // インスタンス化
        $IPLimitCreateService = new IPLimitCreateService;
        // レコードを変更
        $IPLimitCreateService->createIpLimit($request);
        return redirect()->route('access_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'IPを追加しました。',
        ]);
    }
}
