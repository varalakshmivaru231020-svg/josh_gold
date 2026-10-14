<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// A post on the public Updates page (/updates), managed in Admin → Updates
class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = static::uniqueSlug($blog->title);
            }
        });
    }

    // URL slug from the title, made unique (slugs are a unique column): "gold-rates", "gold-rates-2", …
    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'update';
        $slug = $base;
        for ($i = 2; static::where('slug', $slug)->exists(); $i++) {
            $slug = $base . '-' . $i;
        }

        return $slug;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    public function getExcerptAttribute()
    {
        $text = html_entity_decode(strip_tags((string) $this->description), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return Str::limit(trim(preg_replace('/\s+/u', ' ', $text)), 150);
    }

    // Public URL of the cover image, or null when the post has none
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? versioned_asset('storage/' . $this->image) : null;
    }
}
