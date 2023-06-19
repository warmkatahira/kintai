<?php

namespace App\Services\KintaiMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kintai;

class BaseCheckService
{
    // 拠点確認ユーザーIDと拠点確認日時を更新
    public function updateBaseCheck($chk, $nowDate)
    {
        Kintai::whereIn('kintai_id', $chk)->update([
            'base_checked_id' => Auth::user()->id,
            'base_checked_at' => $nowDate,
        ]);
        return;
    }
}