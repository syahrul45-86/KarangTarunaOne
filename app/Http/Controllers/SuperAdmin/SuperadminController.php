<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
    public function index()
    {
        $superadmin = auth()->user();

        $totalRt      = Rt::count();
        $totalAdmin   = User::where('role', 'admin')->count();
        $totalAnggota = User::where('role', 'anggota')->count();
        $totalUser    = User::count();

        // Daftar RT terbaru
        $rtList = Rt::withCount(['users' => function($q){
            $q->where('role', 'anggota');
        }])->latest()->take(6)->get();

        return view('superadmin.dashboard', compact(
            'superadmin', 'totalRt', 'totalAdmin', 'totalAnggota', 'totalUser', 'rtList'
        ));
    }
}
