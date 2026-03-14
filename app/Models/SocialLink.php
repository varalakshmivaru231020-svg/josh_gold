<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class SocialLink extends Model
{
    protected static ?Collection $activeOrderedCache = null;

    protected $fillable = [
        'platform',
        'url',
        'handle',
        'icon',
        'is_active',
        'sort_order',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    protected static function booted(): void
    {
        static::saved(function () {
            static::$activeOrderedCache = null;
        });

        static::deleted(function () {
            static::$activeOrderedCache = null;
        });
    }

    public static function getActiveOrderedCached(): Collection
    {
        if (static::$activeOrderedCache === null) {
            static::$activeOrderedCache = static::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return static::$activeOrderedCache;
    }
}
