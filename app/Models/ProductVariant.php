<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'name_bn', 'description', 'sku',
        'regular_price', 'sale_price', 'download_limit', 'requires_license',
        'status', 'sort_order'
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'requires_license' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'variant_id');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float)($this->sale_price !== null && $this->sale_price < $this->regular_price
            ? $this->sale_price
            : $this->regular_price);
    }
}
