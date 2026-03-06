<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /** Register custom application services or third-party bindings */
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** Configure global application behaviors and boot processes */
    }
}