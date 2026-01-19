<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\PageController;

// ------------------------
// Public Routes   routes/web.php 
// ------------------------
Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('admin/orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('admin.orders.invoice');

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Order Tracking
Route::get('/track-order', [ProductController::class, 'trackOrder'])->name('track.order');
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
        Route::get('/bank-details', [App\Http\Controllers\Distributor\AuthController::class, 'showBankDetailsForm'])->name('bank-details');
        Route::post('/bank-details', [App\Http\Controllers\Distributor\AuthController::class, 'saveBankDetails']);
        Route::post('/logout', [App\Http\Controllers\Agent\AuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/referrals', [App\Http\Controllers\Agent\DashboardController::class, 'referrals'])->name('referrals');
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

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/stats', [App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('dashboard.stats');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Admin\AuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Admin\AuthController::class, 'updateProfile'])->name('profile.update');

        // Products
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->except(['show']);
        Route::get('/products/{product}/remove-image/{index}', [App\Http\Controllers\Admin\ProductController::class, 'removeImage'])->name('products.remove-image');

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
    });
});

// ------------------------
// Redirect root registration to agent registration
// ------------------------
Route::get('/register', function () {
    return redirect()->route('agent.register');
});

// ------------------------
// CMS Pages catch-all (LAST ROUTE)
// ------------------------
Route::get('/{page}', [App\Http\Controllers\PageController::class, 'show'])
    ->where('page', '[A-Za-z0-9\-\_]+')
    ->name('pages.show');
