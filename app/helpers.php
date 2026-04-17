<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get a setting value anywhere in the app (Blade, controllers, etc.)
     *
     * Usage:
     *   setting('site.name')                       → 'لمسة خيط'
     *   setting('contact.whatsapp', '970500000000') → with fallback
     *   setting('site.maintenance')                → true / false
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}
