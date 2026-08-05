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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer(
            ['layouts.app', 'layouts.admin', 'recipes.*', 'admin.*'],
            function ($view) {
                $view->with('portalSettings', \App\Models\Setting::firstOrCreate());
            }
        );
    }
}
