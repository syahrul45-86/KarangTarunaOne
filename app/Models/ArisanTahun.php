<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArisanTahun extends Model
{
    protected $table = 'arisan_tahun';

    protected $fillable = [
        'tahun',
    ];

    // Relasi ke tanggal-tanggal arisan dalam tahun ini
   public function tanggal()
    {
        return $this->hasMany(ArisanTanggal::class, 'arisan_tahun_id');
    }

}
