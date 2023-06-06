<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kintai extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'kintai_id';
    // 操作するカラムを許可
    protected $fillable = [
        'employee_no',
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
        'checked_at',
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
}
