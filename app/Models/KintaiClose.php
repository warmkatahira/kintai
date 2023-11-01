<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KintaiClose extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'kintai_close_id';
    // 操作するカラムを許可
    protected $fillable = [
        'close_date',
        'base_id',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('kintai_close_id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($kintai_close_id)
    {
        return self::where('kintai_close_id', $kintai_close_id);
    }
    // kintai_close_employeesテーブルとのリレーション
    public function kintai_close_employees()
    {
        return $this->hasMany(KintaiCloseEmployee::class, 'kintai_close_id', 'kintai_close_id');
    }
}
