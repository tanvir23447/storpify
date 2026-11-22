<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'name_bn', 'slug', 'sku', 'product_type',
        'short_description', 'short_description_bn',
        'full_description', 'full_description_bn',
        'thumbnail', 'image_gallery', 'demo_url', 'video_preview_url',
        'regular_price', 'sale_price', 'is_featured', 'status',
        'version', 'update_info', 'download_instructions',
        'download_limit', 'requires_license',
        'seo_title', 'seo_description',
        'view_count', 'download_count', 'sales_count', 'avg_rating', 'review_count',
        'created_by', 'approved_by',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'requires_license' => 'boolean',
        'image_gallery' => 'array',
        'avg_rating' => 'decimal:2',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category', 'product_id', 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProductFile::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_relations', 'product_id', 'tag_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Computed attributes
    public function getEffectivePriceAttribute(): float
    {
        return (float)($this->sale_price !== null && $this->sale_price < $this->regular_price
            ? $this->sale_price
            : $this->regular_price);
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->regular_price > 0 && $this->sale_price !== null && $this->sale_price < $this->regular_price) {
            return (int)round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }
        return 0;
    }
}
