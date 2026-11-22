<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionalBanner extends Model
{
    protected $fillable = [
        'title', 'title_bn', 'subtitle', 'subtitle_bn',
        'banner_image', 'link_url', 'banner_type', 'badge_text',
        'discount_text', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
