<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KintaiCloseEmployee extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'kintai_close_employee_id';
    // 操作するカラムを許可
    protected $fillable = [
        'kintai_close_id',
        'employee_id',
        'employee_category_id',
        'is_available',
    ];
}
