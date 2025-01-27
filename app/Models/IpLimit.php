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
        'base_id',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('updated_at', 'desc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($ip_limit_id)
    {
        return self::where('ip_limit_id', $ip_limit_id);
    }
    // basesとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
    // 拠点毎の登録数を取得
    public static function getRegisterByBase()
    {
        return self::where('is_available', 1)
                ->selectRaw('base_id, COUNT(*) as register_count')
                ->groupBy('base_id')
                ->orderBy('base_id', 'asc');
    }
}
