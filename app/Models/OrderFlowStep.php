<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFlowStep extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'step_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('step_number', 'asc');
    }
}
