<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'staff_id', 'action', 'module', 'record_id', 'old_value', 'new_value', 'ip_address', 'user_agent', 'created_at'
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
        'created_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Helper to log staff actions.
     */
    public static function record(string $action, string $module, ?string $recordId = null, ?array $oldValue = null, ?array $newValue = null): self
    {
        return self::create([
            'staff_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
