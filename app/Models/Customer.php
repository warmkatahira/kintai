<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'customer_id';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作するカラムを許可
    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_group_id',
        'base_id',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('customer_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($customer_id)
    {
        return self::where('customer_id', $customer_id);
    }
    // 指定した拠点のレコードを取得
    public static function getSpecifyBase($base_id)
    {
        return self::where('base_id', $base_id);
    }
    // 指定した荷主グループのレコードを取得
    public static function getSpecifyCustomerGroup($customer_group_id)
    {
        return self::where('customer_group_id', $customer_group_id)->orderBy('customer_id', 'asc');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
    // customer_groupsテーブルとのリレーション
    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id', 'customer_group_id');
    }
}
