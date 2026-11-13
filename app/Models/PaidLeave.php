<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidLeave extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'paid_leave_id';
    // 操作するカラムを許可
    protected $fillable = [
        'employee_no',
        'paid_leave_date',
        'paid_leave_days',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('paid_leave_date', 'asc')->orderBy('employee_no', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($paid_leave_id)
    {
        return self::where('paid_leave_id', $paid_leave_id);
    }
    // employeesテーブルとのリレーション
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_no', 'employee_no');
    }
    // CSVのヘッダーを定義
    public static function csvHeader()
    {
        return [
            '従業員番号',
            '有給取得日',
            '取得日数',
        ];
    }
}
