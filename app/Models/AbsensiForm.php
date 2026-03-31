<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiForm extends Model
{
    use HasFactory;

    protected $table = 'absensi_forms';

    protected $fillable = [
        'judul',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'qr_token',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'form_id');
    }

    public function denda()
    {
        return $this->hasMany(Denda::class, 'form_id');
    }
}
