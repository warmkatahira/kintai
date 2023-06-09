<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // 主キーカラムを変更
    protected $primaryKey = 'role_id';
    // 操作するカラムを許可
    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'email',
        'password',
        'role_id',
        'base_id',
        'status',
        'last_login_at',
    ];
    // 全て取得
    public static function getAll()
    {
        return self::orderBy('id', 'asc');
    }
    // 指定されたレコードを取得
    public static function getSpecify($id)
    {
        return self::where('id', $id);
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
