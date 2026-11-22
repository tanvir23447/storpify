<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'discount_type',
        'discount_value', 'min_spend', 'max_discount',
        'usage_limit', 'usage_count', 'user_limit',
        'starts_at', 'expires_at', 'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    public function isValidForAmount(float $amount, ?int $userId = null): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Coupon is inactive.'];
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'Coupon offer has not started yet.'];
        }

        if ($this->expires_at && now()->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'Coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached.'];
        }

        if ($amount < (float)$this->min_spend) {
            return ['valid' => false, 'message' => "Minimum spend of ৳{$this->min_spend} is required."];
        }

        if ($userId && $this->user_limit) {
            $userRedemptions = $this->redemptions()->where('user_id', $userId)->count();
            if ($userRedemptions >= $this->user_limit) {
                return ['valid' => false, 'message' => 'You have already redeemed this coupon.'];
            }
        }

        // Calculate discount
        $discount = 0.00;
        if ($this->discount_type === 'percentage') {
            $discount = ($amount * (float)$this->discount_value) / 100;
            if ($this->max_discount && $discount > (float)$this->max_discount) {
                $discount = (float)$this->max_discount;
            }
        } else {
            $discount = (float)$this->discount_value;
        }

        $discount = min($discount, $amount);

        return [
            'valid' => true,
            'discount' => round($discount, 2),
            'message' => 'Coupon applied successfully.',
        ];
    }
}
