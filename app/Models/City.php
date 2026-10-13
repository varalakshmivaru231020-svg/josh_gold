<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;

// A city with a gold rate page at /gold-rate/{state_slug}/{slug}. Read through App\Support\RateBook.
class City extends Model
{
    public const CACHE_KEY = 'cities.active';

    // Active cities for the current request (also kept in the app cache until a city is saved or deleted)
    protected static ?array $activeCache = null;

    protected $fillable = [
        'name',
        'state',
        'state_slug',
        'slug',
        'show_in_menu',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'show_in_menu' => 'boolean',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
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

    // URL path of the city page: state-slug/city-slug
    public function getKeyPathAttribute(): string
    {
        return $this->state_slug . '/' . $this->slug;
    }

    /**
     * Active cities in order, as plain arrays: [['key' => 'state/city', 'name', 'state', 'menu' => bool], …].
     * Cached forever; saving or deleting a city clears it.
     */
    public static function activeCached(): array
    {
        if (static::$activeCache !== null) {
            return static::$activeCache;
        }

        try {
            return static::$activeCache = Cache::rememberForever(self::CACHE_KEY, fn () => static::active()
                ->ordered()
                ->get()
                ->map(fn (City $c) => [
                    'key'   => $c->key_path,
                    'name'  => $c->name,
                    'state' => $c->state,
                    'menu'  => $c->show_in_menu,
                ])
                ->all());
        } catch (QueryException $e) {
            // The cities table does not exist yet (code deployed before "php artisan migrate"):
            // show no cities rather than breaking every page. Nothing is cached.
            report($e);

            return [];
        }
    }
}
