<?php

namespace App\Http\Controllers\SystemMgt\AccessMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccessMgt\IpLimitCreateService;
use App\Models\IpLimit;
use Carbon\CarbonImmutable;

class AccessMgtController extends Controller
{
    public function index()
    {
        // IP情報を取得
        $ip_limits = IpLimit::getAll()->get();
        // 拠点毎の登録数を取得
        $ip_limit_by_base = IpLimit::getRegisterByBase()->get();
        return view('access_mgt.index')->with([
            'ip_limits' => $ip_limits,
            'ip_limit_by_base' => $ip_limit_by_base,
        ]);
    }
}
