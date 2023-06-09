<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCategory extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'employee_category_id';
    // 操作するカラムを許可
    protected $fillable = [
        'employee_category_name',
        'is_no_rest_available',
        'is_add_rest_available',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('employee_category_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($employee_category_id)
    {
        return self::where('employee_category_id', $employee_category_id);
    }
}
