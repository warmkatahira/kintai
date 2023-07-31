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
        $no_finish_kintai_count = Kintai::getBeforeYesterdayNoFinishKintai()->count();
        // 昨日以前で退勤処理されていない勤怠の出勤日を取得
        $no_finish_kintais = Kintai::getBeforeYesterdayNoFinishKintai()
                ->select('work_day')
                ->orderBy('work_day', 'asc')
                ->groupBy('work_day')
                ->get();
        // レコードがあれば情報を取得
        if(!$no_finish_kintais->isEmpty()){
            $no_finish_from = $no_finish_kintais->first()->work_day;
            $no_finish_to = $no_finish_kintais->last()->work_day;
        }
        return view('top')->with([
            'now_begin_kintai_count' => $now_begin_kintai_count,
            'no_finish_kintai_count' => $no_finish_kintai_count,
            'no_finish_from' => isset($no_finish_from) ? $no_finish_from : '',
            'no_finish_to' => isset($no_finish_to) ? $no_finish_to : '',
        ]);
    }
}
