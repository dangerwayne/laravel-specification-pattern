<?php

namespace DangerWayne\Specification\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheableSpecification
{
    public function getCachedResults($query, ?int $ttl = null)
    {
        if (! config('specification.cache.enabled')) {
            return $this->toQuery($query)->get();
        }

        $key = config('specification.cache.prefix').$this->getCacheKey();
        $ttl = $ttl ?? config('specification.cache.ttl');

        return Cache::remember($key, $ttl, function () use ($query) {
            return $this->toQuery($query)->get();
        });
    }

    public function clearCache(): void
    {
        $key = config('specification.cache.prefix').$this->getCacheKey();
        Cache::forget($key);
    }
}
