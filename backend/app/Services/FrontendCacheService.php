<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Calls the Next.js on-demand revalidation webhook to purge
 * frontend cache tags after admin CRUD operations.
 */
class FrontendCacheService
{
    /**
     * Revalidate the given cache tags on the Next.js frontend.
     *
     * @param string[] $tags  Cache tag names (e.g. ['products', 'categories'])
     */
    public static function revalidate(array $tags): void
    {
        $baseUrl = config('services.nextjs.url');
        $secret  = config('services.nextjs.revalidation_secret');

        if (!$baseUrl || !$secret) {
            Log::warning('FrontendCacheService: Missing Next.js URL or revalidation secret in config.');
            return;
        }

        $url = rtrim($baseUrl, '/') . '/api/revalidate?secret=' . urlencode($secret);

        try {
            Http::timeout(5)->post($url, ['tags' => $tags]);
        } catch (\Throwable $e) {
            // Fire-and-forget: don't let frontend revalidation failures break admin flows
            Log::warning('FrontendCacheService: Failed to revalidate tags', [
                'tags'  => $tags,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
