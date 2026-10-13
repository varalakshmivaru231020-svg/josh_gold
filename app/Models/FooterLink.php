<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;

// A link in one of the website footer's link columns (layouts/partials/jb-footer)
class FooterLink extends Model
{
    public const CACHE_KEY = 'footer_links.active';

    // Footer link columns: column_key => default heading (the heading itself is a site setting)
    public const COLUMNS = [
        'rates'   => 'Rates',
        'company' => 'Company',
        'notices' => 'Notices & letters',
    ];

    // Defaults for the footer text kept in site_settings
    public const DEFAULT_ABOUT = 'Daily gold and silver rates, published each morning and evening, with clear pricing by purity and weight.';
    public const DEFAULT_NOTE  = 'Rates on this site are reference prices for the metal value only, based on our published morning and evening rates. Making charges and GST are extra. Please confirm the final price with our desk before you pay.';

    // Active links for the current request (also kept in the app cache until a link is saved or deleted)
    protected static ?array $activeCache = null;

    protected $fillable = [
        'column_key',
        'label',
        'url',
        'open_in_new_tab',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_active'       => 'boolean',
        'sort_order'      => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }

    public static function flushCache(): void
    {
        static::$activeCache = null;
        Cache::forget(self::CACHE_KEY);
    }

    // Link target for an href: site paths become full URLs on the current domain, other URLs are kept
    public function getHrefAttribute(): string
    {
        return url($this->url);
    }

    /**
     * Active links grouped by column, as plain arrays:
     * ['rates' => [['label', 'url', 'new_tab' => bool], …], 'company' => […], 'notices' => […]].
     * Cached forever; saving or deleting a link clears it.
     */
    public static function activeByColumnCached(): array
    {
        if (static::$activeCache !== null) {
            return static::$activeCache;
        }

        try {
            return static::$activeCache = Cache::rememberForever(self::CACHE_KEY, function () {
                $columns = array_fill_keys(array_keys(self::COLUMNS), []);
                foreach (static::active()->ordered()->get() as $link) {
                    if (isset($columns[$link->column_key])) {
                        $columns[$link->column_key][] = [
                            'label'   => $link->label,
                            'url'     => $link->url,
                            'new_tab' => $link->open_in_new_tab,
                        ];
                    }
                }

                return $columns;
            });
        } catch (QueryException $e) {
            // The footer_links table does not exist yet (code deployed before "php artisan migrate"):
            // show empty columns rather than breaking every page. Nothing is cached.
            report($e);

            return array_fill_keys(array_keys(self::COLUMNS), []);
        }
    }

    // Heading of a footer column (site setting footer_heading_{column}, falling back to the default)
    public static function heading(string $column): string
    {
        $heading = trim((string) SiteSetting::get('footer_heading_' . $column));

        return $heading !== '' ? $heading : (self::COLUMNS[$column] ?? ucfirst($column));
    }

    // Footer about text under the logo; an empty saved value hides it
    public static function about(): string
    {
        return trim((string) SiteSetting::get('footer_about', self::DEFAULT_ABOUT));
    }

    // Disclaimer note at the very bottom of the footer; an empty saved value hides it
    public static function note(): string
    {
        return trim((string) SiteSetting::get('footer_note', self::DEFAULT_NOTE));
    }
}
