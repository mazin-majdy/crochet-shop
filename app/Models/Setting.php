<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'label', 'description', 'sort'];

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Core helpers
    |─────────────────────────────────────────────────────────────────────────
    */

    /**
     * Get one setting value by key.
     * Uses a 24-hour cache to avoid repeated DB queries.
     *
     * Usage:  Setting::get('site.name')
     *         Setting::get('contact.whatsapp', '970500000000')
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", 86400, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            if (!$row) return $default;

            // Cast booleans
            if ($row->type === 'boolean') {
                return (bool) $row->value;
            }

            return $row->value ?? $default;
        });
    }

    /**
     * Set (upsert) a setting value and clear its cache.
     *
     * Usage:  Setting::set('site.name', 'لمسة خيط')
     */
    public static function set(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        Cache::forget("setting:{$key}");
    }

    /**
     * Get all settings in a group as key→value array.
     * Uses a single cached query per group.
     *
     * Usage:  Setting::group('contact')  →  ['whatsapp' => '...', 'email' => '...']
     */
    public static function group(string $group): array
    {
        return Cache::remember("settings_group:{$group}", 86400, function () use ($group) {
            return static::where('group', $group)
                ->orderBy('sort')
                ->pluck('value', 'key')
                ->mapWithKeys(fn ($v, $k) => [str_replace("{$group}.", '', $k) => $v])
                ->toArray();
        });
    }

    /**
     * Bulk-save an array of key→value pairs and flush group cache.
     *
     * Usage:  Setting::saveGroup('site', $request->only([...]))
     */
    public static function saveGroup(string $group, array $data): void
    {
        foreach ($data as $key => $value) {
            $fullKey = str_contains($key, '.') ? $key : "{$group}.{$key}";
            static::where('key', $fullKey)->update(['value' => $value]);
            Cache::forget("setting:{$fullKey}");
        }
        Cache::forget("settings_group:{$group}");
    }

    /**
     * Flush ALL settings cache.
     */
    public static function flushCache(): void
    {
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting:{$key}");
        }
        foreach (['site','contact','social','payment','appearance'] as $g) {
            Cache::forget("settings_group:{$g}");
        }
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Accessors
    |─────────────────────────────────────────────────────────────────────────
    */

    public function getCastedValueAttribute(): mixed
    {
        return match ($this->type) {
            'boolean' => (bool) $this->value,
            default   => $this->value,
        };
    }

    public function getGroupLabelAttribute(): string
    {
        return match ($this->group) {
            'site'       => 'الموقع العام',
            'contact'    => 'بيانات التواصل',
            'social'     => 'التواصل الاجتماعي',
            'payment'    => 'معلومات الدفع',
            'appearance' => 'المظهر والتخصيص',
            default      => $this->group,
        };
    }

    public function getGroupIconAttribute(): string
    {
        return match ($this->group) {
            'site'       => 'bi-globe2',
            'contact'    => 'bi-telephone-fill',
            'social'     => 'bi-share-fill',
            'payment'    => 'bi-credit-card-fill',
            'appearance' => 'bi-palette-fill',
            default      => 'bi-gear-fill',
        };
    }
}
