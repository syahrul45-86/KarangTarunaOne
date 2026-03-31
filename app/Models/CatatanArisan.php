<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanArisan extends Model
{
    protected $table = 'catatan_arisan';

    protected $fillable = [
        'user_id',
        'tanggal_id',
        'sudah_bayar'
    ];

    // Relasi ke user/anggota
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke data tanggal arisan
    public function tanggal()
    {
        return $this->belongsTo(ArisanTanggal::class, 'tanggal_id');
    }
}
