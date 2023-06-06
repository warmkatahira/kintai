<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KintaiDetail extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'kintai_detail_id';
    // 操作するカラムを許可
    protected $fillable = [
        'kintai_id',
        'customer_id',
        'customer_working_time',
        'is_supported',
    ];
}
