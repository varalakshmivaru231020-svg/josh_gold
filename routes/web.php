<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PageController;

// ------------------------
// Public Routes   routes/web.php
// ------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', function() {
    return redirect()->route('admin.login');
})->name('login');


Route::get('admin/orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('admin.orders.invoice');

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Purchase Route
Route::get('/purchase', function() {
    return view('purchase');
})->name('purchase');

// Order Tracking
Route::get('/track-order', [ProductController::class, 'trackOrder'])->name('track.order');

// Practice & Offices Pages
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
Route::get('/practice-areas', [HomeController::class, 'services'])->name('services.index');
Route::get('/offices', [HomeController::class, 'offices'])->name('offices.index');

// Testimonials Page
Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('testimonials');

// Photo Gallery Page
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');

Route::post('/checkReferral', [ProductController::class, 'checkReferral'])->name('checkReferral');

// Checkout Routes
Route::get('/checkout/{product}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/create-payment', [OrderController::class, 'createPayment'])->name('orders.create-payment');
Route::post('/verify-payment', [OrderController::class, 'verifyPayment'])->name('orders.verify-payment');

// Shipping routes
Route::get('/orders/{order}/shipping', [OrderController::class, 'showShippingForm'])->name('orders.shipping');
Route::post('/orders/{order}/shipping', [OrderController::class, 'updateShipping'])->name('orders.update-shipping');

// Success page
Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');

// ------------------------
// Agent Routes
// ------------------------
Route::prefix('agent')->name('agent.')->group(function () {

    // Guest routes
    Route::middleware('guest:agent')->group(function () {
        Route::get('/register', [App\Http\Controllers\Agent\AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [App\Http\Controllers\Agent\AuthController::class, 'register']);
        Route::post('/send-otp', [App\Http\Controllers\Agent\AuthController::class, 'sendOtp'])->name('send.otp');
        Route::get('/login', [App\Http\Controllers\Agent\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Agent\AuthController::class, 'login']);
        Route::get('/forgot-password', [App\Http\Controllers\Agent\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password/send-otp', [App\Http\Controllers\Agent\AuthController::class, 'sendForgotPasswordOtp'])->name('forgot-password.sendOtp');
        Route::get('/verify-otp', [App\Http\Controllers\Agent\AuthController::class, 'showVerifyOtpForm'])->name('verify-otp');
        Route::post('/verify-otp', [App\Http\Controllers\Agent\AuthController::class, 'verifyOtp'])->name('verify-otp.submit');
        Route::get('/resend-otp', [App\Http\Controllers\Agent\AuthController::class, 'resendOtp'])->name('resend-otp');
        Route::get('/reset-password', [App\Http\Controllers\Agent\AuthController::class, 'showResetPasswordForm'])->name('reset-password');
        Route::post('/reset-password', [App\Http\Controllers\Agent\AuthController::class, 'resetPassword'])->name('reset-password.submit');
    });
});

// ------------------------
// Distributor Routes
// ------------------------
Route::prefix('agent')->name('agent.')->group(function () {

    // Authenticated routes
    Route::middleware('auth:agent')->group(function () {
        // Route::get('/bank-details', [App\Http\Controllers\Distributor\AuthController::class, 'showBankDetailsForm'])->name('bank-details');
        // Route::post('/bank-details', [App\Http\Controllers\Distributor\AuthController::class, 'saveBankDetails']);
        Route::post('/logout', [App\Http\Controllers\Agent\AuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/commissions', [App\Http\Controllers\Agent\DashboardController::class, 'commissions'])->name('commissions');
        Route::get('/share-referral', [App\Http\Controllers\Agent\DashboardController::class, 'shareReferral'])->name('share.referral');
        Route::get('/profile', [App\Http\Controllers\Agent\DashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Agent\DashboardController::class, 'updateProfile'])->name('profile.update');
    });
});

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
        Route::get('/stats', [App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('dashboard.stats');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Admin\AuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Admin\AuthController::class, 'updateProfile'])->name('profile.update');

        // Products
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->except(['show']);
        Route::get('/products/{product}/remove-image/{index}', [App\Http\Controllers\Admin\ProductController::class, 'removeImage'])->name('products.remove-image');
        Route::get('/products/{product}/remove-cover', [App\Http\Controllers\Admin\ProductController::class, 'removeCover'])->name('products.remove-cover');

        // Orders
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');

        // Agents & Commissions
        Route::get('/agents', [App\Http\Controllers\Admin\AgentController::class, 'index'])->name('agents.index');
        Route::get('/agents/{agent}', [App\Http\Controllers\Admin\AgentController::class, 'show'])->name('agents.show');
        Route::put('/agents/{agent}', [App\Http\Controllers\Admin\AgentController::class, 'update'])->name('agents.update');
        Route::get('/commissions', [App\Http\Controllers\Admin\AgentController::class, 'commissions'])->name('commissions.index');
        Route::put('/commissions/{commission}/status', [App\Http\Controllers\Admin\AgentController::class, 'updateCommissionStatus'])->name('commissions.update-status');
        Route::get('/agents/{agent}/pending-commissions', [App\Http\Controllers\Admin\AgentController::class, 'getPendingCommissions'])->name('agents.pending-commissions');

        // Settlements
        Route::resource('settlements', App\Http\Controllers\Admin\SettlementController::class)->except(['edit', 'update']);
        Route::put('/settlements/{settlement}/status', [App\Http\Controllers\Admin\SettlementController::class, 'updateStatus'])->name('settlements.update-status');

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

            // Featured Book
            Route::get('/featured-book', [App\Http\Controllers\Admin\SiteContentController::class, 'featuredBook'])->name('featured-book');
            Route::put('/featured-book', [App\Http\Controllers\Admin\SiteContentController::class, 'updateFeaturedBook'])->name('featured-book.update');

            // Testimonial Videos
            Route::get('/testimonials', [App\Http\Controllers\Admin\SiteContentController::class, 'testimonialVideos'])->name('testimonials');
            Route::get('/testimonials/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createTestimonialVideo'])->name('testimonials.create');
            Route::post('/testimonials', [App\Http\Controllers\Admin\SiteContentController::class, 'storeTestimonialVideo'])->name('testimonials.store');
            Route::get('/testimonials/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editTestimonialVideo'])->name('testimonials.edit');
            Route::put('/testimonials/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateTestimonialVideo'])->name('testimonials.update');
            Route::delete('/testimonials/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteTestimonialVideo'])->name('testimonials.delete');

            // Order Flow
            Route::get('/order-flow', [App\Http\Controllers\Admin\SiteContentController::class, 'orderFlow'])->name('order-flow');
            Route::get('/order-flow/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createOrderFlowStep'])->name('order-flow.create');
            Route::post('/order-flow', [App\Http\Controllers\Admin\SiteContentController::class, 'storeOrderFlowStep'])->name('order-flow.store');
            Route::get('/order-flow/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editOrderFlowStep'])->name('order-flow.edit');
            Route::put('/order-flow/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateOrderFlowStep'])->name('order-flow.update');
            Route::delete('/order-flow/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteOrderFlowStep'])->name('order-flow.delete');

            // WhatsApp Flow
            Route::get('/whatsapp-flow', [App\Http\Controllers\Admin\SiteContentController::class, 'whatsappFlow'])->name('whatsapp-flow');
            Route::get('/whatsapp-flow/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createWhatsappFlowStep'])->name('whatsapp-flow.create');
            Route::post('/whatsapp-flow', [App\Http\Controllers\Admin\SiteContentController::class, 'storeWhatsappFlowStep'])->name('whatsapp-flow.store');
            Route::get('/whatsapp-flow/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editWhatsappFlowStep'])->name('whatsapp-flow.edit');
            Route::put('/whatsapp-flow/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateWhatsappFlowStep'])->name('whatsapp-flow.update');
            Route::delete('/whatsapp-flow/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteWhatsappFlowStep'])->name('whatsapp-flow.delete');

            // Promo Banners
            Route::get('/promo-banners', [App\Http\Controllers\Admin\SiteContentController::class, 'promoBanners'])->name('promo-banners');
            Route::get('/promo-banners/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createPromoBanner'])->name('promo-banners.create');
            Route::post('/promo-banners', [App\Http\Controllers\Admin\SiteContentController::class, 'storePromoBanner'])->name('promo-banners.store');
            Route::get('/promo-banners/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editPromoBanner'])->name('promo-banners.edit');
            Route::put('/promo-banners/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updatePromoBanner'])->name('promo-banners.update');
            Route::delete('/promo-banners/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deletePromoBanner'])->name('promo-banners.delete');

            // Site Settings
            Route::get('/settings', [App\Http\Controllers\Admin\SiteContentController::class, 'siteSettings'])->name('settings');
            Route::put('/settings', [App\Http\Controllers\Admin\SiteContentController::class, 'updateSiteSettings'])->name('settings.update');

            // Blogs
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

            // Stats
            Route::get('/stats', [App\Http\Controllers\Admin\SiteContentController::class, 'stats'])->name('stats');
            Route::get('/stats/create', [App\Http\Controllers\Admin\SiteContentController::class, 'createStat'])->name('stats.create');
            Route::post('/stats', [App\Http\Controllers\Admin\SiteContentController::class, 'storeStat'])->name('stats.store');
            Route::get('/stats/{id}/edit', [App\Http\Controllers\Admin\SiteContentController::class, 'editStat'])->name('stats.edit');
            Route::put('/stats/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'updateStat'])->name('stats.update');
            Route::delete('/stats/{id}', [App\Http\Controllers\Admin\SiteContentController::class, 'deleteStat'])->name('stats.delete');



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

            // Beyond the Bar Section
            Route::get('/beyond-bar', [App\Http\Controllers\Admin\SiteContentController::class, 'beyondBar'])->name('beyond-bar');
            Route::put('/beyond-bar', [App\Http\Controllers\Admin\SiteContentController::class, 'updateBeyondBar'])->name('beyond-bar.update');
        });
    });
});

// ------------------------
// Redirect root registration to agent registration
// ------------------------
Route::get('/register', function () {
    return redirect()->route('agent.register');
});

// Blog Routes
Route::get('/blog', [HomeController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [HomeController::class, 'blogShow'])->name('blog.show');

// Contact Page
Route::get('/contact-us', [HomeController::class, 'contactPage'])->name('contact');
Route::post('/contact-us', [HomeController::class, 'submitContact'])->name('contact.submit');

// Water Filtration Project
Route::view('/water-filtration', 'water-filtration')->name('water-filtration');
Route::view('/water-filtration-kannada', 'water-filtration-kannada')->name('water-filtration-kannada');
Route::view('/letter-to-pm-modi', 'letter-to-pm-modi')->name('letter-to-pm-modi');
Route::view('/letter-to-cm-karnataka', 'letter-to-cm-karnataka')->name('letter-to-cm-karnataka');

// ------------------------
// CMS Pages catch-all (LAST ROUTE)
// ------------------------
Route::get('/{page}', [App\Http\Controllers\PageController::class, 'show'])
    ->where('page', '[A-Za-z0-9\-\_]+')
    ->name('pages.show');
