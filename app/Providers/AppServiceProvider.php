<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(
            [
                'admin.layouts.navbar',
                'bendahara.layouts.navbar',
                'sekretaris.layouts.navbar',
                'anggota.layouts.navbar'
            ],
            \App\Http\View\Composers\NavbarComposer::class
        );
    }
}
