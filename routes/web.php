<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\LiveRatesController;
use App\Http\Controllers\RatePagesController;

// ------------------------
// Public Routes   routes/web.php
// ------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', function() {
    return redirect()->route('admin.login');
})->name('login');

// Live Metal Rates (public)
Route::get('/live-rates', [LiveRatesController::class, 'index'])->name('live-rates');
Route::get('/live-rates/history', [LiveRatesController::class, 'history'])->middleware('throttle:120,1')->name('live-rates.history');

// Main-menu rate pages (gold, silver, charts, Gold Pulse, cities)
Route::get('/gold-rate-today', [RatePagesController::class, 'gold'])->name('rates.gold');
Route::get('/silver-rate-today', [RatePagesController::class, 'silver'])->name('rates.silver');
Route::get('/charts', [RatePagesController::class, 'charts'])->name('rates.charts');
Route::get('/gold-pulse', [RatePagesController::class, 'pulse'])->name('rates.pulse');
Route::get('/gold-rate', [RatePagesController::class, 'cities'])->name('rates.cities');
Route::get('/gold-rate/{state}/{city}', [RatePagesController::class, 'city'])
    ->where(['state' => '[a-z0-9-]+', 'city' => '[a-z0-9-]+'])->name('rates.city');

// Practice & Offices Pages
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
Route::get('/services', [HomeController::class, 'services'])->name('services.index');
Route::redirect('/practice-areas', '/services', 301);
Route::redirect('/contact', '/contact-us', 301);
Route::get('/offices', [HomeController::class, 'offices'])->name('offices.index');

// Bank Details (public, managed from admin)
Route::get('/bank-details', [HomeController::class, 'bankDetails'])->name('bank-details');

// Old news addresses now live at /updates (see the Updates routes below)
Route::redirect('/news', '/updates', 301);
Route::redirect('/blog', '/updates', 301);
Route::redirect('/blog/{slug}', '/updates/{slug}', 301);

// Photo Gallery Page
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');

// ------------------------
// Admin Routes
// ------------------------
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

        // Pages CRUD
        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{id}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');

        // Page Sections
        Route::post('/pages/{pageId}/sections', [App\Http\Controllers\Admin\PageSectionController::class, 'store'])->name('pages.sections.store');
        Route::get('/page-sections/{id}/edit', [App\Http\Controllers\Admin\PageSectionController::class, 'edit'])->name('pages.sections.edit');
        Route::put('/page-sections/{id}', [App\Http\Controllers\Admin\PageSectionController::class, 'update'])->name('pages.sections.update');
        Route::delete('/page-sections/{id}', [App\Http\Controllers\Admin\PageSectionController::class, 'destroy'])->name('pages.sections.destroy');
        Route::post('/page-sections/update-order', [App\Http\Controllers\Admin\PageSectionController::class, 'updateOrder'])->name('pages.sections.update-order');

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Admin\AuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Admin\AuthController::class, 'updateProfile'])->name('profile.update');

        // Site Content Management
        Route::prefix('site-content')->name('site-content.')->group(function () {
            // Sliders
            Route::get('/sliders', [App\Http\Controllers\Admin\SiteContentController::class, 'sliders'])->name('sliders');
            Route::get('/sliders/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createSlider'])->name('sliders.create');
            Route::post('/sliders', [App\Http\Controllers\Admin\SiteContentController::class, 'storeSlider'])->name('sliders.store');
            Route::get('/sliders/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editSlider'])->name('sliders.edit');
            Route::put('/sliders/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateSlider'])->name('sliders.update');
            Route::delete('/sliders/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteSlider'])->name('sliders.delete');

            // About Section
            Route::get('/about', [App\Http\Controllers\Admin\SiteContentController::class, 'aboutSection'])->name('about');
            Route::put('/about', [App\Http\Controllers\Admin\SiteContentController::class, 'updateAboutSection'])->name('about.update');

            // Call To Action banner (above the footer)
            Route::get('/cta', [App\Http\Controllers\Admin\CtaController::class, 'edit'])->name('cta');
            Route::put('/cta', [App\Http\Controllers\Admin\CtaController::class, 'update'])->name('cta.update');

            // Site Settings
            Route::get('/settings', [App\Http\Controllers\Admin\SiteContentController::class, 'siteSettings'])->name('settings');
            Route::put('/settings', [App\Http\Controllers\Admin\SiteContentController::class, 'updateSiteSettings'])->name('settings.update');

            // Updates (news posts on /updates; stored in the blogs table)
            Route::get('/blogs', [App\Http\Controllers\Admin\SiteContentController::class, 'blogs'])->name('blogs');
            Route::get('/blogs/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createBlog'])->name('blogs.create');
            Route::post('/blogs', [App\Http\Controllers\Admin\SiteContentController::class, 'storeBlog'])->name('blogs.store');
            Route::get('/blogs/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editBlog'])->name('blogs.edit');
            Route::put('/blogs/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateBlog'])->name('blogs.update');
            Route::delete('/blogs/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteBlog'])->name('blogs.delete');

            // Contact Settings
            Route::get('/contact-settings', [App\Http\Controllers\Admin\SiteContentController::class, 'contactSettings'])->name('contact-settings');
            Route::put('/contact-settings', [App\Http\Controllers\Admin\SiteContentController::class, 'updateContactSettings'])->name('contact-settings.update');

            // Contact Messages
            Route::get('/contact-messages', [App\Http\Controllers\Admin\SiteContentController::class, 'contactMessages'])->name('contact-messages');
            Route::get('/contact-messages/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'viewContactMessage'])->name('contact-messages.view');
            Route::delete('/contact-messages/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteContactMessage'])->name('contact-messages.delete');

            // Services
            Route::get('/services', [App\Http\Controllers\Admin\SiteContentController::class, 'services'])->name('services');
            Route::get('/services/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createService'])->name('services.create');
            Route::post('/services', [App\Http\Controllers\Admin\SiteContentController::class, 'storeService'])->name('services.store');
            Route::get('/services/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editService'])->name('services.edit');
            Route::put('/services/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateService'])->name('services.update');
            Route::delete('/services/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteService'])->name('services.delete');

            // Offices
            Route::get('/offices', [App\Http\Controllers\Admin\SiteContentController::class, 'offices'])->name('offices');
            Route::get('/offices/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createOffice'])->name('offices.create');
            Route::post('/offices', [App\Http\Controllers\Admin\SiteContentController::class, 'storeOffice'])->name('offices.store');
            Route::get('/offices/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editOffice'])->name('offices.edit');
            Route::put('/offices/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateOffice'])->name('offices.update');
            Route::delete('/offices/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteOffice'])->name('offices.delete');

            // Social Links
            Route::get('/social-links', [App\Http\Controllers\Admin\SiteContentController::class, 'socialLinks'])->name('social-links');
            Route::get('/social-links/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createSocialLink'])->name('social-links.create');
            Route::post('/social-links', [App\Http\Controllers\Admin\SiteContentController::class, 'storeSocialLink'])->name('social-links.store');
            Route::get('/social-links/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editSocialLink'])->name('social-links.edit');
            Route::put('/social-links/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateSocialLink'])->name('social-links.update');
            Route::delete('/social-links/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteSocialLink'])->name('social-links.delete');

            // Gallery Photos
            Route::get('/gallery', [App\Http\Controllers\Admin\SiteContentController::class, 'galleryPhotos'])->name('gallery');
            Route::get('/gallery/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createGalleryPhoto'])->name('gallery.create');
            Route::post('/gallery', [App\Http\Controllers\Admin\SiteContentController::class, 'storeGalleryPhoto'])->name('gallery.store');
            Route::get('/gallery/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editGalleryPhoto'])->name('gallery.edit');
            Route::put('/gallery/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateGalleryPhoto'])->name('gallery.update');
            Route::delete('/gallery/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteGalleryPhoto'])->name('gallery.delete');
        });

        // Bank Details
        Route::resource('bank-accounts', App\Http\Controllers\Admin\BankAccountController::class)
            ->except(['show']);

        // ── Metal Rates (Live Rates Module) ────────────────────
        Route::resource('metal-rates', App\Http\Controllers\Admin\MetalRateController::class)
            ->except(['show']);
        Route::get('/metal-rates-history', [App\Http\Controllers\Admin\MetalRateController::class, 'history'])
            ->name('metal-rates.history');
        Route::delete('/metal-rates-sample', [App\Http\Controllers\Admin\MetalRateController::class, 'clearSample'])
            ->name('metal-rates.clear-sample');

        // Cities and footer
        require __DIR__ . '/admin-content.php';

        // Collections (category pages)
        require __DIR__ . '/admin-collections.php';
    });
});

// Collections: one page per category (gold coins, gold bars, jewellery…), managed in Admin → Collections
Route::get('/collections/{slug}', [App\Http\Controllers\CollectionController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')->name('collections.show');

// Updates (news managed in Admin → Updates)
Route::get('/updates', [HomeController::class, 'blogIndex'])->name('updates');
Route::get('/updates/{slug}', [HomeController::class, 'blogShow'])->name('updates.show');

// Contact Page
Route::get('/contact-us', [HomeController::class, 'contactPage'])->name('contact');
Route::post('/contact-us', [HomeController::class, 'submitContact'])->name('contact.submit');

// Sitemap: a page for visitors and an XML file for search engines
Route::get('/sitemap', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'xml'])->name('sitemap.xml');

// Water Filtration Project
Route::view('/water-filtration', 'water-filtration')->name('water-filtration');
Route::view('/water-filtration-kannada', 'water-filtration-kannada')->name('water-filtration-kannada');
Route::view('/anti-conversion-law', 'anti-conversion-law')->name('anti-conversion-law');
Route::view('/sc-st-act-1989', 'sc-st-act-1989')->name('sc-st-act-1989');
Route::view('/letter-to-pm-modi', 'letter-to-pm-modi')->name('letter-to-pm-modi');
Route::view('/letter-to-cm-karnataka', 'letter-to-cm-karnataka')->name('letter-to-cm-karnataka');

// ------------------------
// CMS Pages catch-all (LAST ROUTE)
// ------------------------
Route::get('/{page}', [App\Http\Controllers\PageController::class, 'show'])
    ->where('page', '[A-Za-z0-9\-\_]+')
    ->name('pages.show');
