<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class IdcardController extends Controller
{
    public function index()
{
    $users = User::where('rt_id', Auth::user()->rt_id)->get();

    return view('admin.idcard.index', compact('users'));
}
}
