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
        \Illuminate\Support\Facades\View::composer(['layouts.lawfirm', 'layouts.app'], function ($view) {
            $view->with('socialLinks', \App\Models\SocialLink::getActiveOrderedCached());
        });
    }
}
