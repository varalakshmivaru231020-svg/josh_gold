<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialVideo extends Model
{
    protected $fillable = [
        'title',
        'youtube_url',
        'youtube_id',
        'thumbnail',
        'author_name',
        'author_designation',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Extract YouTube ID from URL
            if ($model->youtube_url) {
                $model->youtube_id = self::extractYoutubeId($model->youtube_url);
            }
        });
    }

    public static function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return $this->youtube_id ? "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg" : null;
    }

    public function getEmbedUrlAttribute()
    {
        return $this->youtube_id ? "https://www.youtube.com/embed/{$this->youtube_id}" : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
