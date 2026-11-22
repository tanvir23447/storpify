<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'title_bn', 'slug', 'description', 'description_bn',
        'thumbnail', 'file_path', 'storage_bucket', 'file_size',
        'file_type', 'version', 'download_count', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SubscriptionCategory::class, 'category_id');
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_files', 'file_id', 'plan_id');
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(SubscriptionDownloadLog::class, 'file_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
