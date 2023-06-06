<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'holiday';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作するカラムを許可
    protected $fillable = [
        'holiday',
        'note',
        'is_national_holiday',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('holiday', 'asc');
    }
}
