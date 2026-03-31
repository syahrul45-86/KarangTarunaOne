<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'form_id',
        'user_id',
        'waktu_absen',
        'status',
    ];

    public function form()
    {
        return $this->belongsTo(AbsensiForm::class, 'form_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
