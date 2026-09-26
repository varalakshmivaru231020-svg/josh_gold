<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'banner_image', 'banner_title', 'is_published', 'show_in_footer'];

    protected $casts = [
        'is_published'   => 'boolean',
        'show_in_footer' => 'boolean',
    ];

    // Enable route model binding by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Published pages that should be linked from the website footer
    public function scopeForFooter($query)
    {
        return $query->where('is_published', true)->where('show_in_footer', true)->orderBy('title');
    }

    public function sections()
    {
        return $this->hasMany(PageSection::class)->ordered();
    }
}
