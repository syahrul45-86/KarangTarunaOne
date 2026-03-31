<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AbsensiAdmin extends Model

    {
    use HasFactory;

    protected $table = 'absensi_admin';

    protected $fillable = [
        'user_id',
        'rt_id',
        'tanggal',
        'waktu',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }
}
