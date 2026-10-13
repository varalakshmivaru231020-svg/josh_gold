<?php

// Admin routes for knowledge articles, cities and the footer.
// Included from routes/web.php inside the admin group: names are prefixed "admin."
// and every route requires an admin login.

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\KnowledgeController;
use Illuminate\Support\Facades\Route;

// Knowledge articles (/knowledge): admin.knowledge.index|create|store|edit|update|destroy
Route::resource('knowledge', KnowledgeController::class)
    ->except(['show'])
    ->parameters(['knowledge' => 'article'])
    ->whereNumber('article');

// Cities with a gold rate page: admin.cities.index|create|store|edit|update|destroy
Route::resource('cities', CityController::class)
    ->except(['show'])
    ->whereNumber('city');

// Footer: link columns, headings, about text and note
Route::prefix('footer')->name('footer.')->controller(FooterController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::put('/settings', 'updateSettings')->name('settings.update');
    Route::put('/links/order', 'updateOrder')->name('links.order');
    Route::get('/links/create', 'create')->name('links.create');
    Route::post('/links', 'store')->name('links.store');
    Route::get('/links/{link}/edit', 'edit')->whereNumber('link')->name('links.edit');
    Route::put('/links/{link}', 'update')->whereNumber('link')->name('links.update');
    Route::delete('/links/{link}', 'destroy')->whereNumber('link')->name('links.destroy');
});
