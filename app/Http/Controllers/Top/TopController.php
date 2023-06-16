<?php

namespace App\Http\Controllers\Top;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kintai;

class TopController extends Controller
{
    public function index()
    {
        // 本日出勤中の勤怠の数を取得
        $now_begin_kintai_count = Kintai::getNowBeginKintai();
        // 昨日以前で退勤処理されていない勤怠の数を取得
        $no_finish_kintai_count = Kintai::getBeforeYesterdayNoFinishKintai();
        return view('top')->with([
            'now_begin_kintai_count' => $now_begin_kintai_count, 
            'no_finish_kintai_count' => $no_finish_kintai_count,
        ]);
    }
}
