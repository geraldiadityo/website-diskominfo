<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * @method static void created(\Closure|string|array $callback)
 * @method static void updated(\Closure|string|array $callback)
 * @method static void deleted(\Closure|string|array $callback)
 */
trait ClearsCache
{
    protected static function bootClearsCache(): void
    {
        $clearCache = function ($model) {
            if (isset($model->cacheTag)) {
                Cache::tags([$model->cacheTag])->flush();
            }
        };

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
    }
}
