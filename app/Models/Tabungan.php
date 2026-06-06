<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'rt_id',
        'user_id',
        'jenis_transaksi',
        'nominal',
        'tanggal',
        'keterangan',
    ];

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
