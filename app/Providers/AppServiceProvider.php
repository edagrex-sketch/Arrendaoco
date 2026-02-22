<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Inmueble;
use App\Policies\InmueblePolicy;

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
        // Forzar URLs a usar APP_URL (necesario para ngrok)
        URL::forceRootUrl(config('app.url'));
        if (str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
    }
    protected $policies = [
    Inmueble::class => InmueblePolicy::class,
    ];
}
