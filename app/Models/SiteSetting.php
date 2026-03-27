<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    //
    protected $fillable = [
        'key',
        'value',
        'group'
    ];

    public static function getSetting(string $key, $default = null)
    {
        return Cache::rememberForever("site_setting_{$key}", function () use ($key, $default) {
            return self::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget("site_setting_{$setting->key}");
        });

        static::deleted(function ($setting) {
            Cache::forget("site_setting_{$setting->key}");
        });
    }
}
