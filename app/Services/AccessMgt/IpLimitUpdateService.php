<?php

namespace App\Services\AccessMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IpLimit;

class IpLimitUpdateService
{
    public function updateIpLimit($request)
    {
        IpLimit::getSpecify($request->ip_limit_id)->update([
            'ip' => $request->ip,
            'base_id' => $request->base_id,
            'note' => $request->note,
            'is_available' => $request->is_available,
        ]);
        return;
    }
}