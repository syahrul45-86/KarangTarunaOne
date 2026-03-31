<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index()
    {
         $anggota = auth()->user();
        return view('anggota.dashboard', compact('anggota'));
        // pastikan ada file resources/views/superadmin/dashboard.blade.php
    }
}
