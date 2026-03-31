<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

// Form login (GET /)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Proses login (POST /)
Route::post('/', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
