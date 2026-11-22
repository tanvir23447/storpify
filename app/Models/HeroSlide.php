<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title', 'title_bn', 'subtitle', 'subtitle_bn',
        'description', 'description_bn', 'desktop_image', 'mobile_image',
        'bg_image', 'bg_color', 'overlay_opacity', 'text_color',
        'primary_button_text', 'primary_button_url',
        'secondary_button_text', 'secondary_button_url',
        'sort_order', 'is_active', 'starts_at', 'ends_at'
    ];

    protected $casts = [
        'overlay_opacity' => 'decimal:2',
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
            ->orderBy('sort_order');
    }
}
