<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecommendationService
{
    protected string $baseUrl;

    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.ai.url', env('AI_SERVICE_URL', 'http://localhost:8001/api'));
        $this->apiKey = config('services.ai.key', env('RECOMMENDER_API_KEY', 'mitologi-secret-key'));
    }

    public function forUser(int $userId, int $limit = 10): array
    {
        return \Illuminate\Support\Facades\Cache::remember("recommendations_user_{$userId}_{$limit}", 300, function () use ($userId, $limit) {
            try {
                $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                    ->timeout(10)
                    ->get("{$this->baseUrl}/recommendations/user/{$userId}", [
                        'limit' => $limit,
                    ]);

                if ($response->successful()) {
                    return $response->json('data', []);
                }

                return [];
            } catch (\Exception $e) {
                \Log::warning('AI recommendation service unavailable: '.$e->getMessage());

                return [];
            }
        });
    }

    public function forProduct(int $productId, int $limit = 5): array
    {
        return \Illuminate\Support\Facades\Cache::remember("recommendations_product_{$productId}_{$limit}", 300, function () use ($productId, $limit) {
            try {
                $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                    ->timeout(10)
                    ->get("{$this->baseUrl}/recommendations/product/{$productId}", [
                        'limit' => $limit,
                    ]);

                if ($response->successful()) {
                    return $response->json('data', []);
                }

                return [];
            } catch (\Exception $e) {
                \Log::warning('AI recommendation service unavailable: '.$e->getMessage());

                return [];
            }
        });
    }

    public function train(): bool
    {
        // Fetch Products
        $products = \App\Models\Product::with('categories')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'description' => strip_tags($product->description ?? ''),
                'category' => $product->categories->first()?->name ?? 'General',
            ];
        })->values()->toArray();

        // Fetch Interactions (Using paid orders)
        $interactions = \App\Models\OrderItem::whereHas('order', function ($query) {
            $query->whereNotNull('paid_at');
        })->with('order')->get()->map(function ($item) {
            return [
                'user_id' => $item->order->user_id,
                'product_id' => $item->product_id,
                'action' => 'purchase',
            ];
        })->filter(function ($item) {
            return ! is_null($item['user_id']);
        })->values()->toArray();

        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->timeout(60)
                ->post("{$this->baseUrl}/train", [
                    'products' => $products,
                    'interactions' => $interactions,
                ]);

            if ($response->successful()) {
                return true;
            }

            \Log::error('AI training failed with status '.$response->status().': '.$response->body());

            return false;
        } catch (\Exception $e) {
            \Log::error('AI training failed: '.$e->getMessage());

            return false;
        }
    }

    public function healthCheck(): bool
    {
        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->timeout(5)
                ->get("{$this->baseUrl}/health");

            return $response->successful() && $response['status'] === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }
}
