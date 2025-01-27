<?php

namespace App\Services\AccessMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IpLimit;

class IpLimitDeleteService
{
    public function deleteIpLimit($request)
    {
        IpLimit::getSpecify($request->ip_limit_id)->delete();
        return;
    }
}