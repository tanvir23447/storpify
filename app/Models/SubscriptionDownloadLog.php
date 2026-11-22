<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionDownloadLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'subscription_id', 'file_id', 'ip_address', 'user_agent', 'downloaded_at'
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(SubscriptionFile::class, 'file_id');
    }
}
