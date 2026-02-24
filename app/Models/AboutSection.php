<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $table = 'about_section';

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'image',
        'hero_image',
        'id_card_image',
        'years_experience',
        'designation',
        'name_kn',
        'qualifications',
        'tagline',
        'stat_courts',
        'stat_books',
        'stat_offices',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
