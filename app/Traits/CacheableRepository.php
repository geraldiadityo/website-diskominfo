<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Closure;

trait CacheableRepository
{
    protected function executeWithCache(string $key, Closure $callback)
    {
        $tag = $this->cacheTag ?? 'default_tag';
        $ttl = $this->cacheTtl ?? 3600;

        return Cache::tags([$tag])->remember($key, $ttl, $callback);
    }
}
