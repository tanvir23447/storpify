<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMetadata extends Model
{
    protected $fillable = [
        'page_type', 'page_identifier', 'meta_title', 'meta_description',
        'canonical_url', 'og_image', 'structured_data'
    ];

    protected $casts = [
        'structured_data' => 'array',
    ];
}
