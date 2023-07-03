<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;

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
        'is_available',
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
    // 指定した拠点の従業員を取得
    public static function getSpecifyBase($base_id)
    {
        return self::where('base_id', $base_id);
    }
    // 出勤打刻が可能な対象を取得
    Public function punch_begin_targets()
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 今日の日付の勤怠が無い従業員
        return $this->hasMany(Kintai::class, 'employee_id', 'employee_id')
                ->where('work_day', $nowDate->format('Y-m-d'))
                ->orderBy('employee_no');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
    // employee_categoriesテーブルとのリレーション
    public function employee_category()
    {
        return $this->belongsTo(EmployeeCategory::class, 'employee_category_id', 'employee_category_id');
    }
}
