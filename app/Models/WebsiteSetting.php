<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        $val = $setting->value;
        $decoded = json_decode($val, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $val;
    }

    public static function set(string $key, $value, string $group = 'general', bool $isPublic = false): void
    {
        $storedValue = is_array($value) ? json_encode($value) : (string)$value;
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'group' => $group, 'is_public' => $isPublic]
        );
    }
}
