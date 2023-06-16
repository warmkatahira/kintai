<?php

namespace App\Http\Controllers\SystemMgt\AccessMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccessMgt\IPLimitCreateService;
use App\Models\IpLimit;
use Carbon\CarbonImmutable;

class AccessMgtController extends Controller
{
    public function index()
    {
        // IP情報を取得
        $ip_limits = IpLimit::getAll()->get();
        return view('access_mgt.index')->with([
            'ip_limits' => $ip_limits,
        ]);
    }
}
