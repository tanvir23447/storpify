<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'event_type', 'ip_address', 'severity', 'details', 'created_at'
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $eventType, string $severity = 'warning', ?array $details = null, ?int $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?: auth()->id(),
            'event_type' => $eventType,
            'ip_address' => request()->ip(),
            'severity' => $severity,
            'details' => $details,
            'created_at' => now(),
        ]);
    }
}
