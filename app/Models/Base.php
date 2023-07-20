<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'base_id';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作するカラムを許可
    protected $fillable = [
        'base_id',
        'base_name',
        'is_add_rest_available',
        'rest_related_select_mode',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('base_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($base_id)
    {
        return self::where('base_id', $base_id);
    }
}
