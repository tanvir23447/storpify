<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'name_bn', 'description', 'description_bn',
        'duration_type', 'duration_days', 'price', 'sale_price',
        'download_limit_daily', 'download_limit_total', 'device_limit',
        'display_image', 'is_featured', 'is_active', 'all_categories_access',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'all_categories_access' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionCategory::class, 'subscription_plan_categories', 'plan_id', 'category_id');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionFile::class, 'subscription_plan_files', 'plan_id', 'file_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float)($this->sale_price !== null && $this->sale_price < $this->price
            ? $this->sale_price
            : $this->price);
    }
}
