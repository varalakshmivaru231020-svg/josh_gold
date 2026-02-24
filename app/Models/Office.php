<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'address',
        'phone',
        'mobile',
        'email',
        'website',
        'icon',
        'image',
        'color',
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
}
