<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryCompany extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'temporary_company_id';
    // 操作するカラムを許可
    protected $fillable = [
        'temporary_company_name',
        'hourly_rate',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('temporary_company_id', 'asc');
    }
}
