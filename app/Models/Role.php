<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'role_id';
    // 操作するカラムを許可
    protected $fillable = [
        'role_name',
        'is_kintai_mgt_func_available',
        'is_base_check_available',
        'is_kintai_operation_available',
        'is_employee_mgt_available',
        'is_employee_operation_available',
        'is_base_mgt_available',
        'is_manual_punch_available',
        'is_customer_mgt_func_available',
        'is_kintai_close_available',
        'is_download_func_available',
        'is_accounting_func_available',
        'is_system_mgt_func_available',
        'is_user_mgt_available',
        'is_role_mgt_available',
        'is_holiday_mgt_available',
        'is_access_mgt_available',
        'is_lock_kintai_operation_available',
        'is_all_kintai_operation_available',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('role_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($role_id)
    {
        return self::where('role_id', $role_id);
    }
    // usersテーブルとのリレーション
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
