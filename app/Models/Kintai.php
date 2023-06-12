<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;
use App\Services\CommonService;
use Illuminate\Support\Facades\Auth;

class Kintai extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'kintai_id';
    // 操作するカラムを許可
    protected $fillable = [
        'employee_id',
        'work_day',
        'begin_time',
        'finish_time',
        'begin_time_adj',
        'finish_time_adj',
        'out_time',
        'return_time',
        'out_time_adj',
        'return_time_adj',
        'is_out_enabled',
        'rest_time',
        'no_rest_time',
        'add_rest_time',
        'comment',
        'out_return_time',
        'working_time',
        'over_time',
        'is_early_worked',
        'is_modified',
        'is_manual_punched',
        'base_checked_at',
        'locked_at',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('kintai_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($kintai_id)
    {
        return self::where('kintai_id', $kintai_id);
    }
    // employeesテーブルとのリレーション
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
    // 拠点確認がNullの自拠点勤怠数を取得
    public function countNoBaseCheckKintai($date)
    {
        // インスタンス化
        $CommonService = new CommonService;
        // 月初・月末の日付を取得
        $start_end_of_month = $CommonService->getStartEndOfMonth(CarbonImmutable::parse($date));
        // 拠点確認がNullの自拠点勤怠数を取得
        return self::whereDate('work_day', '>=', $start_end_of_month['start'])
                    ->whereDate('work_day', '<=', $start_end_of_month['end'])
                    ->whereHas('employee.base', function ($query) {
                        $query->where('base_id', Auth::user()->base_id);
                    })
                    ->whereNull('base_checked_at')
                    ->count();
    }
    // 昨日以前で退勤処理されていない勤怠の数を取得
    public static function getBeforeYesterdayNoFinishKintai()
    {
        // 昨日の日付を取得
        $yesterday = CarbonImmutable::yesterday();
        // 自拠点で退勤時間がNullで昨日以前の勤怠数を取得
        return self::whereHas('employee.base', function ($query) {
                    $query->where('base_id', Auth::user()->base_id);
                })
                ->whereNull('finish_time')
                ->whereDate('work_day', '<=', $yesterday)
                ->count();
    }
}
