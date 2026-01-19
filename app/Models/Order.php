<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'discount',
        'total',
        'referral_code',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'shipping_address',
        'country',
        'state',
        'city',
        'pincode',
        'payment_method',
        'payment_id',
        'payment_status',
        'order_status',
        'tracking_id',
        'tracking_url',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    public function generateOrderNumber()
    {
        return 'ORD' . date('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6));
    }
}