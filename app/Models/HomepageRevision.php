<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageRevision extends Model
{
    protected $fillable = ['section_id', 'content', 'settings', 'published_by', 'note'];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(HomepageSection::class, 'section_id');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
