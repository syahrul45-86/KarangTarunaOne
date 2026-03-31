<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SuperadminController extends Controller
{
    public function index()
    {
        $superadmin = auth()->user();
        return view('superadmin.dashboard', compact('superadmin'));
    }

}
