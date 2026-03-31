<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class SpinController extends Controller
{

   public function index()
{
    $rtId = auth()->user()->rt_id; // RT sekretaris yang sedang login

    $anggota = User::where('rt_id', $rtId)->get();

    return view('sekretaris.spin.index', compact('anggota'));
}


}
