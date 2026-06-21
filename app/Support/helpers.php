<?php

use App\Models\SiteSetting;

if (! function_exists('setting')) {
    /**
     * Retrieve a site setting value by key with an optional default.
     *
     * Usage in Blade: {{ setting('site_name', 'RPD') }}
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            $value = SiteSetting::get($key, $default);
        } catch (\Throwable $e) {
            // During migrations/installation the table may not exist yet.
            return $default;
        }

        return $value === null || $value === '' ? $default : $value;
    }
}

if (! function_exists('setting_asset')) {
    /**
     * Build a public storage URL for a setting that stores a file path.
     */
    function setting_asset(string $key, ?string $default = null): ?string
    {
        $path = setting($key);

        if (blank($path)) {
            return $default;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }
}
