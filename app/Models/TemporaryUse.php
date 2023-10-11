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
        'customer_working_time',
    ];
}
