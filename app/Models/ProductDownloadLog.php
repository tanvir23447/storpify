<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDownloadLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'order_id', 'product_id', 'variant_id', 'file_id',
        'ip_address', 'user_agent', 'downloaded_at'
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(ProductFile::class, 'file_id');
    }
}
