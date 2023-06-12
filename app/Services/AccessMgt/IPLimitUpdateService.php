<?php

namespace App\Services\AccessMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IPLimit;


class IPLimitUpdateService
{
    public function updateIpLimit($request)
    {
        IPLimit::getSpecify($request->ip_limit_id)->update([
            'ip' => $request->ip,
            'note' => $request->note,
            'is_available' => $request->is_available,
        ]);
        return;
    }
}