<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'code', 'name', 'name_bn', 'account_type',
        'account_numbers', 'instructions', 'instructions_bn',
        'qr_code', 'logo', 'sort_order', 'is_active', 'fee_percentage'
    ];

    protected $casts = [
        'account_numbers' => 'array',
        'is_active' => 'boolean',
        'fee_percentage' => 'decimal:2',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Get the primary display number or formatted numbers.
     */
    public function getPrimaryNumberAttribute(): ?string
    {
        if (is_array($this->account_numbers) && count($this->account_numbers) > 0) {
            $first = $this->account_numbers[0];
            return is_array($first) ? ($first['number'] ?? '') : (string)$first;
        }
        return null;
    }
}
