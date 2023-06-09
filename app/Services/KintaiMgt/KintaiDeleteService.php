<?php

namespace App\Services\KintaiMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kintai;

class KintaiDeleteService
{
    // 勤怠を削除
    public function deleteKintai($kintai_id)
    {
        Kintai::getSpecify($kintai_id)->delete();
        return;
    }
}