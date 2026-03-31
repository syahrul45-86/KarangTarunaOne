<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bendaharas extends Model
{
    use HasFactory;
    protected $fillable = [
        'rt_id',
        'tanggal',
        'keterangan',
        'saldo_awal',
        'pemasukan',
        'pengeluaran',
        'saldo_akhir'
    ];

    // relasi ke tabel rts
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }
}
