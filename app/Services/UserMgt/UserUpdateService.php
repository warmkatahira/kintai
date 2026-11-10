<?php

namespace App\Services\UserMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class UserUpdateService
{
    public function updateUser($request)
    {
        User::getSpecify($request->id)->update([
            'base_id' => $request->base_id,
            'user_id' => $request->user_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'employee_no' => $request->employee_no,
            'no_ip_check' => $request->no_ip_check,
            'status' => $request->status,
        ]);
        return;
    }

    // 拠点のユーザーのIPチェックなしを一括更新
    public function updateNoIpCheckByBase($base_id, $no_ip_check)
    {
        return User::where('base_id', $base_id)->update([
            'no_ip_check' => $no_ip_check,
        ]);
    }
}
