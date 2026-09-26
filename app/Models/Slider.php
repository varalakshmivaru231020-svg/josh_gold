<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    // Public pages a slider can be placed on: key => label shown in the admin dropdown
    public const PAGES = [
        'home'         => 'Home',
        'live-rates'   => 'Live Rates',
        'about-us'     => 'About Us',
        'services'     => 'Services',
        'bank-details' => 'Bank Details',
        'gallery'      => 'Gallery',
        'news'         => 'News & Updates',
        'contact-us'   => 'Contact Us',
    ];

    protected $fillable = [
        'page',
        'title',
        'subtitle',
        'description',
        'image',
        'button_text',
        'button_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // The slider page key for the page being viewed, or null for pages without sliders
    public static function keyForCurrentRoute(): ?string
    {
        return match (true) {
            request()->routeIs('home')                => 'home',
            request()->routeIs('live-rates')          => 'live-rates',
            request()->routeIs('about-us')            => 'about-us',
            request()->routeIs('services.index')      => 'services',
            request()->routeIs('bank-details')        => 'bank-details',
            request()->routeIs('gallery')             => 'gallery',
            request()->routeIs('news', 'blog.*')      => 'news',
            request()->routeIs('contact')             => 'contact-us',
            default                                   => null,
        };
    }

    // Not named forPage(): Eloquent's paginate() calls a builder method of that name for the page number
    public function scopeOnPage($query, string $page)
    {
        return $query->where('page', $page);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
