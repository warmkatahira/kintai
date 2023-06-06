<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'employee_id';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作するカラムを許可
    protected $fillable = [
        'employee_no',
        'base_id',
        'employee_last_name',
        'employee_first_name',
        'employee_category_id',
        'monthly_workable_time',
        'over_time_start',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('employee_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($employee_id)
    {
        return self::where('employee_id', $employee_id);
    }
}
