<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'banner_image', 'banner_title', 'is_published'];

    // Enable route model binding by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
