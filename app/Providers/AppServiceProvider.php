<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; // Add this line

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
        // Correct way to register a view component:
        Blade::component('app-layout', 'layouts.app');
        Blade::component('application-logo', 'components.application-logo');
        Blade::component('nav-link', 'components.nav-link');
        Blade::component('dropdown', 'components.dropdown');
        Blade::component('dropdown-link', 'components.dropdown-link');
        Blade::component('responsive-nav-link', 'components.responsive-nav-link');
    }
}
