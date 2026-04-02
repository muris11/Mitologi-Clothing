<?php

namespace App\Observers;

class LandingPageDataObserver
{
    /**
     * Clear the landing page cache when any related model is saved or deleted.
     */
    public function saved($model): void
    {
        $this->clearCache();
    }

    public function deleted($model): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('api.landing_page_data');
        \Illuminate\Support\Facades\Cache::forget('api.site_settings');

        // Flush product listing caches (pattern-based)
        // Since Redis is used, we can flush by prefix
        try {
            $redis = \Illuminate\Support\Facades\Cache::getStore();
            if (method_exists($redis, 'getRedis')) {
                $prefix = config('cache.prefix', 'laravel_cache').':';
                $keys = $redis->getRedis()->keys($prefix.'api.products.*');
                foreach ($keys as $key) {
                    $redis->getRedis()->del($key);
                }
            }
        } catch (\Exception $e) {
            // Fallback: if Redis not available, just log it
            \Illuminate\Support\Facades\Log::info('Product cache clear skipped: '.$e->getMessage());
        }
    }
}
