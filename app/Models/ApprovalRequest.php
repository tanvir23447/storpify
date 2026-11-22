<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalRequest extends Model
{
    protected $fillable = [
        'model_type', 'model_id', 'requested_by', 'action_type',
        'original_data', 'proposed_data', 'status', 'reviewed_by',
        'reviewed_at', 'comments'
    ];

    protected $casts = [
        'original_data' => 'array',
        'proposed_data' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
