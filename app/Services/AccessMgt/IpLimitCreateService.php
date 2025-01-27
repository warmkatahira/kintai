<?php

namespace App\Services\AccessMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IpLimit;

class IpLimitCreateService
{
    public function createIpLimit($request)
    {
        IpLimit::create([
            'ip' => $request->ip,
            'base_id' => $request->base_id,
            'note' => $request->note,
            'is_available' => $request->is_available,
        ]);
        return;
    }
}