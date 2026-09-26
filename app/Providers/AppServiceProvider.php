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

        // Sliders for the page being viewed (Home renders its own inside the page)
        \Illuminate\Support\Facades\View::composer('layouts.lawfirm', function ($view) {
            $key = \App\Models\Slider::keyForCurrentRoute();
            $view->with('pageSlides', ($key && $key !== 'home')
                ? \App\Models\Slider::active()->onPage($key)->ordered()->get()
                : collect());
        });

        // Offices and pages managed in admin, shown in the site footer
        \Illuminate\Support\Facades\View::composer('layouts.partials.footer', function ($view) {
            $view->with('footerOffices', \App\Models\Office::active()->ordered()->get());
            $view->with('footerPages', \App\Models\Page::forFooter()->get(['id', 'title', 'slug']));
        });
    }
}
