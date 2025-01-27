<?php

namespace App\Http\Controllers\SystemMgt\AccessMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccessMgt\IpLimitDeleteService;
use App\Models\IpLimit;
use Carbon\CarbonImmutable;
use App\Http\Requests\IpLimitDeleteRequest;

class IpLimitDeleteController extends Controller
{
    public function delete(IpLimitDeleteRequest $request)
    {
        // インスタンス化
        $IpLimitDeleteService = new IpLimitDeleteService;
        // レコードを変更
        $IpLimitDeleteService->deleteIpLimit($request);
        return redirect()->route('access_mgt.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'IPを削除しました。',
        ]);
    }
}
