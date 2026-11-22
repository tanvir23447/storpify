<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['level', 'channel', 'message', 'context', 'created_at'];

    protected $casts = [
        'context' => 'array',
        'created_at' => 'datetime',
    ];

    public static function log(string $message, string $level = 'info', ?array $context = null, string $channel = 'app'): self
    {
        return self::create([
            'level' => $level,
            'channel' => $channel,
            'message' => $message,
            'context' => $context,
            'created_at' => now(),
        ]);
    }
}
