<?php

namespace App\Services\AccessMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IPLimit;


class IPLimitCreateService
{
    public function createIpLimit($request)
    {
        IPLimit::create([
            'ip' => $request->ip,
            'note' => $request->note,
            'is_available' => $request->is_available,
        ]);
        return;
    }
}