<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupCampaign extends Model
{
    protected $fillable = [
        'title', 'title_bn', 'description', 'description_bn',
        'offer_image', 'discount_text', 'coupon_code',
        'button_text', 'button_url', 'bg_color', 'text_color',
        'display_delay_sec', 'show_on_homepage', 'show_on_products',
        'show_on_all', 'frequency', 'is_active', 'starts_at', 'ends_at',
        'priority', 'view_count', 'click_count'
    ];

    protected $casts = [
        'show_on_homepage' => 'boolean',
        'show_on_products' => 'boolean',
        'show_on_all' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderByDesc('priority');
    }
}
