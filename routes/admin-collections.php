<?php

// Admin routes for collections (category pages).
// Included from routes/web.php inside the admin group: names are prefixed "admin."
// and every route requires an admin login.

use App\Http\Controllers\Admin\CollectionController;
use Illuminate\Support\Facades\Route;

// Collections (/collections/{slug} and the home "Shop by category" tiles):
// admin.collections.index|create|store|edit|update|destroy
Route::resource('collections', CollectionController::class)
    ->except(['show'])
    ->whereNumber('collection');
