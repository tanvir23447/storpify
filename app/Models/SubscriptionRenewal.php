<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionRenewal extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_subscription_id', 'previous_plan_id', 'new_plan_id',
        'extended_days', 'amount_paid', 'renewed_at'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'renewed_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function previousPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'previous_plan_id');
    }

    public function newPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'new_plan_id');
    }
}
