<?php

namespace App\Services\KintaiMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kintai;

class KintaiUpdateService
{
    // コメントを更新
    public function updateComment($kintai_id, $comment)
    {
        Kintai::getSpecify($kintai_id)->update([
            'comment' => $comment,
        ]);
        return;
    }

    // 拠点確認日時を更新
    public function updateBaseCheck($chk, $nowDate)
    {
        Kintai::whereIn('kintai_id', $chk)->update([
            'base_checked_at' => $nowDate,
        ]);
        return;
    }
}