<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'order_id',
        'amount',
        'remaining_amount',
        'commission_percentage',
        'status',
        'notes',
        'settled_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'settled_at' => 'datetime'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}