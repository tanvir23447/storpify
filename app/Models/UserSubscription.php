<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'order_id', 'starts_at', 'ends_at',
        'status', 'total_downloads', 'is_auto_renew'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_auto_renew' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(SubscriptionDownloadLog::class, 'subscription_id');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(SubscriptionRenewal::class, 'user_subscription_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && now()->lte($this->ends_at);
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || now()->gt($this->ends_at);
    }

    public function remainingDays(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return (int)max(0, now()->diffInDays($this->ends_at, false));
    }

    /**
     * Verify if current subscription allows downloading this specific file.
     */
    public function canAccessFile(SubscriptionFile $file): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        $plan = $this->plan;
        if (!$plan) {
            return false;
        }

        if ($plan->all_categories_access) {
            return true;
        }

        // Check if category is allowed in plan
        $hasCategory = $plan->categories()->where('subscription_categories.id', $file->category_id)->exists();
        if ($hasCategory) {
            return true;
        }

        // Check if file is directly assigned to plan
        return $plan->files()->where('subscription_files.id', $file->id)->exists();
    }

    /**
     * Check if user is within download limits.
     */
    public function canDownload(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        $plan = $this->plan;
        if (!$plan) {
            return false;
        }

        if ($plan->download_limit_total > 0 && $this->total_downloads >= $plan->download_limit_total) {
            return false;
        }

        if ($plan->download_limit_daily > 0) {
            $todayDownloads = $this->downloadLogs()
                ->whereDate('downloaded_at', today())
                ->count();
            if ($todayDownloads >= $plan->download_limit_daily) {
                return false;
            }
        }

        return true;
    }
}
