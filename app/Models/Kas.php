<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';

    protected $fillable = [
        'rt_id',
        'jenis',
        'nominal',
        'keterangan',
        'tanggal',
        'saldo_awal',
        'saldo_akhir'
    ];

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }
}
