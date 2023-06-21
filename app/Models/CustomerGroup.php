<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'customer_group_id';
    // 操作するカラムを許可
    protected $fillable = [
        'base_id',
        'customer_group_name',
        'customer_group_sort_order',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('customer_group_sort_order', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($customer_group_id)
    {
        return self::where('customer_group_id', $customer_group_id);
    }
    // 指定した拠点のレコードを取得
    public static function getSpecifyBase($base_id)
    {
        return self::where('base_id', $base_id)->orderBy('customer_group_sort_order', 'asc');
    }
    // customersテーブルとのリレーション
    Public function customers()
    {
        // Customerモデルのデータを引っ張てくる
        return $this->hasMany(Customer::class, 'customer_group_id', 'customer_group_id')->orderBy('customer_sort_order', 'asc');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
}
