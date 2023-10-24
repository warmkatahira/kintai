<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryUse extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'temporary_use_id';
    // 操作するカラムを許可
    protected $fillable = [
        'temporary_company_id',
        'base_id',
        'date',
        'customer_id',
        'people',
        'working_time',
        'hourly_rate',
        'register_user',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('temporary_use_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($temporary_use_id)
    {
        return self::where('temporary_use_id', $temporary_use_id);
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
    // customersテーブルとのリレーション
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
    // temporary_companiesテーブルとのリレーション
    public function temporary_company()
    {
        return $this->belongsTo(TemporaryCompany::class, 'temporary_company_id', 'temporary_company_id');
    }
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'register_user', 'id');
    }
}
