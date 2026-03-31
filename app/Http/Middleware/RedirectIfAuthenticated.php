<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            $user = Auth::user();

            switch ($user->role) {
                case 'superadmin':
                    return redirect()->route('superadmin.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'bendahara':
                    return redirect()->route('bendahara.dashboard');
                case 'sekretaris':
                    return redirect()->route('sekretaris.dashboard');
                case 'anggota':
                    return redirect()->route('anggota.dashboard');
            }
        }

        return $next($request);
    }
}
