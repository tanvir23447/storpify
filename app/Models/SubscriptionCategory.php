<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubscriptionCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'name_bn', 'slug', 'description', 'description_bn',
        'featured_image', 'icon', 'sort_order', 'is_active',
        'seo_title', 'seo_description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function files(): HasMany
    {
        return $this->hasMany(SubscriptionFile::class, 'category_id')->where('is_active', true);
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_categories', 'category_id', 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
