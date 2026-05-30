<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinAbsensi extends Model
{
    use HasFactory;

    protected $table = 'izin_absensi';

    protected $fillable = [
        'user_id',
        'form_id',
        'alasan',
        'foto_path',
        'status',
        'reviewed_by',
        'catatan_reviewer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function form()
    {
        return $this->belongsTo(AbsensiForm::class, 'form_id');
    }
}
