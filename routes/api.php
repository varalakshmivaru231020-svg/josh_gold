<?php

use App\Http\Controllers\Api\MetalRateController;
use App\Http\Middleware\RatesApiToken;
use Illuminate\Support\Facades\Route;

// Gold/silver rate updates (morning + afternoon). Prefixed with /api.
Route::get('/rates/latest', [MetalRateController::class, 'latest'])
    ->middleware('throttle:60,1')->name('api.rates.latest');

Route::post('/rates', [MetalRateController::class, 'store'])
    ->middleware([RatesApiToken::class, 'throttle:30,1'])->name('api.rates.store');
