<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;
    // 主キーカラムを変更
    protected $primaryKey = 'work_shift_id';
    // 操作するカラムを許可
    protected $fillable = [
        'work_shift_name',
        'morning_break_start_time',
        'lunch_break_start_time',
        'afternoon_break_start_time',
    ];
}
