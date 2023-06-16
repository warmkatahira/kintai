<?php

namespace App\Services\BaseMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Base;


class BaseUpdateService
{
    public function updateBase($request)
    {
        Base::getSpecify($request->base_id)->update([
            'base_name' => $request->base_name,
            'is_add_rest_available' => $request->is_add_rest_available,
        ]);
        return;
    }
}