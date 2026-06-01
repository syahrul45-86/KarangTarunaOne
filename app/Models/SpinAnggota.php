<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpinAnggota extends Model
{
    protected $table = 'spin_anggota';

    protected $fillable = [
        'rt_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }
}
