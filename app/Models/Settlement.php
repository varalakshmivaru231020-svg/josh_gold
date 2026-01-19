<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'amount',
        'payment_method',
        'notes',
        'transaction_details',
        'status',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_details' => 'array',
        'processed_at' => 'datetime'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function processor()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }
}