<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'is_published'];

    // Enable route model binding by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
