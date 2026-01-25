<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        //
    }
    protected $policies = [
    Inmueble::class => InmueblePolicy::class,
    ];
}
