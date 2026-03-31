<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rt extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_rt',
        'rw',
    ];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class, 'rt_id');
    }

    // relasi ke tabel bendaharas

    public function bendaharas()
    {
        return $this->hasMany(Bendaharas::class, 'rt_id');
    }

    // relasi ke tabel setting
    public function setting()
    {
        return $this->hasOne(SettingRT::class, 'rt_id');
    }

}
