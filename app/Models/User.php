<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image',
        'rt_id',
        'qr_code',
         'qr_token',
         'id_card_path'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    // 🔥 Tambahkan ini
    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }
    public function catatanArisan()
{
    return $this->hasMany(\App\Models\CatatanArisan::class, 'user_id');
}
}
