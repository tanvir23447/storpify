<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseActivation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'license_id', 'domain', 'ip_address', 'activated_at', 'last_checked_at'
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'last_checked_at' => 'datetime',
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(ProductLicense::class, 'license_id');
    }
}
