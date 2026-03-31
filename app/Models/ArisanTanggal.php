<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArisanTanggal extends Model
{
    protected $table = 'arisan_tanggal';

    protected $fillable = [
        'arisan_tahun_id',
        'tanggal'
    ];

    // Tanggal ini milik tahun tertentu
    public function tahun()
    {
        return $this->belongsTo(ArisanTahun::class, 'arisan_tahun_id');
    }

    // Banyak anggota yang membayar pada tanggal ini
    public function catatan()
    {
        return $this->hasMany(CatatanArisan::class, 'tanggal_id');
    }
}
