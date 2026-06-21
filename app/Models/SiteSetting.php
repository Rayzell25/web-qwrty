<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    /** @use HasFactory<\Database\Factories\SiteSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('site_settings');

        static::saved($flush);
        static::deleted($flush);
    }

    /**
     * Return all settings as a key => value collection (cached).
     *
     * @return array<string, string|null>
     */
    public static function allKeyed(): array
    {
        return Cache::rememberForever('site_settings', function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a single setting value with an optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::allKeyed()[$key] ?? $default;
    }
}
