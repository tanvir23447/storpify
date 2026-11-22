<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomepageSection extends Model
{
    protected $fillable = [
        'layout_id', 'section_type', 'title', 'title_bn', 'subtitle', 'subtitle_bn',
        'content', 'settings', 'is_active',
        'show_on_desktop', 'show_on_tablet', 'show_on_mobile', 'sort_order'
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'show_on_desktop' => 'boolean',
        'show_on_tablet' => 'boolean',
        'show_on_mobile' => 'boolean',
    ];

    public function layout(): BelongsTo
    {
        return $this->belongsTo(HomepageLayout::class, 'layout_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(HomepageRevision::class, 'section_id')->latest();
    }
}
