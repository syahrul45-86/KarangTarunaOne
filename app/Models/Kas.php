<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';

    protected $fillable = [
        'rt_id',
        'user_id',
        'nominal',
        'keterangan',
        'tanggal',
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
