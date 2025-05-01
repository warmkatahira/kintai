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

    // 特別稼働時間を更新
    public function updateSpecialWorkingTime($kintai_id, $special_working_time)
    {
        Kintai::getSpecify($kintai_id)->update([
            'special_working_time' => $special_working_time,
        ]);
        return;
    }
}