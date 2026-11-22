<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductLicense extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'variant_id', 'user_id',
        'license_key', 'max_activations', 'current_activations',
        'expires_at', 'status'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activations(): HasMany
    {
        return $this->hasMany(LicenseActivation::class, 'license_id');
    }

    public static function generateKey(string $prefix = 'STORP'): string
    {
        $segments = [];
        for ($i = 0; $i < 4; $i++) {
            $segments[] = strtoupper(bin2hex(random_bytes(2)));
        }
        return $prefix . '-' . implode('-', $segments);
    }
}
