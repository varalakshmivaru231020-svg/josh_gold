<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class agent extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'referral_code',
        'referred_by',
        'bank_details',
        'upi_id',
        'total_earnings',
        'settled_amount',
        'pending_amount',
        'otp',
        'otp_expires_at',
        'mobile_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'mobile_verified_at' => 'datetime',
        'otp_expires_at'     => 'datetime',
        'bank_details'       => 'array',
    ];

    /**
     * Distributor commissions
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Distributor referrals
     */
    public function referrals()
    {
        return $this->hasMany(self::class, 'referred_by', 'referral_code');
    }

    /**
     * Distributor settlements
     */
    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    /**
     * Generate unique referral code
     */
    public function generateReferralCode(): string
    {
        do {
            $prefix = strtoupper(substr(preg_replace('/\s+/', '', $this->name), 0, 3));
            $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
            $code = $prefix . $random;
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }
}
