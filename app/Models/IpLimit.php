<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpLimit extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'ip_limit_id';
    // 操作するカラムを許可
    protected $fillable = [
        'ip',
        'note',
        'is_available',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('ip_limit_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($ip_limit_id)
    {
        return self::where('ip_limit_id', $ip_limit_id);
    }
}
