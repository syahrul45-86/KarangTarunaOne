<?php

namespace App\Http\Controllers\sekretaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SekretarisController extends Controller
{
    public function index()
    {
    $sekretaris = auth()->user();
    return view('sekretaris.dashboard', compact('sekretaris'));
    }
}
