<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionOrder extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'plan_id', 'amount', 'currency',
        'payment_status', 'payment_method_id', 'method_code',
        'transaction_id', 'sender_number', 'proof_file',
        'verified_by', 'verified_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public static function generateOrderNumber(): string
    {
        return 'SUB' . date('ymd') . strtoupper(bin2hex(random_bytes(2)));
    }
}
