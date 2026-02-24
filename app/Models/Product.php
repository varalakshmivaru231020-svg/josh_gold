<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'category',
        'book_icon',
        'cover_image',
        'description',
        'price',
        'stock',
        'images',
        'is_active'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFirstImageAttribute()
    {
        $images = $this->images;
        return $images ? $images[0] : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}