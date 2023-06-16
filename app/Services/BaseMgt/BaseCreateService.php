<?php

namespace App\Services\BaseMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Base;

class BaseCreateService
{
    public function createBase($request)
    {
        Base::create([
            'base_id' => $request->base_id,
            'base_name' => $request->base_name,
            'is_add_rest_available' => $request->is_add_rest_available,
        ]);
        return;
    }
}