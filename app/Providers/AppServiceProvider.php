<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- Bagian penting 1

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
        // Bagian penting 2: Paksa HTTPS kalau di Railway (Production)
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}