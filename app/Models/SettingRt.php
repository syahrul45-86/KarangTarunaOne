<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingRT extends Model
{
    protected $table = 'setting_rt';

    protected $fillable = [
        'rt_id',
        'iuran_arisan',
        'denda_absensi',
        'denda_arisan',
    ];

    // Relasi ke tabel RT
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }
}
