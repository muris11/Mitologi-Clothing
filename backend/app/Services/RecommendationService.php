<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UserInteraction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    protected string $baseUrl;

    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.ai.url', env('AI_SERVICE_URL', 'http://localhost:8001/api'));
        $this->apiKey = config('services.ai.key');
    }

    public function buildTrainingPayload(int $months = 6): array
    {
        $products = Product::with('categories')
            ->select('id', 'title', 'handle', 'description', 'created_at')
            ->get()
            ->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => strip_tags($product->description ?? ''),
                    'category' => $product->categories->first()?->name ?? 'General',
                ];
            })
            ->values()
            ->toArray();

        $interactions = UserInteraction::select('user_id', 'product_id', 'type as action', 'score')
            ->where('created_at', '>=', Carbon::now()->subMonths($months))
            ->get()
            ->map(function ($interaction) {
                return [
                    'user_id' => $interaction->user_id,
                    'product_id' => $interaction->product_id,
                    'action' => $interaction->action,
                    'score' => $interaction->score,
                ];
            })
            ->values()
            ->toArray();

        return [
            'products' => $products,
            'interactions' => $interactions,
        ];
    }

    public function forUser(int $userId, int $limit = 10): array
    {
        if (! $this->apiKey || ! $this->baseUrl) {
            return [];
        }

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
                Log::warning('AI recommendation service unavailable: '.$e->getMessage());

                return [];
            }
        });
    }

    public function forProduct(int $productId, int $limit = 5): array
    {
        if (! $this->apiKey || ! $this->baseUrl) {
            return [];
        }

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
                Log::warning('AI recommendation service unavailable: '.$e->getMessage());

                return [];
            }
        });
    }

    public function train(): bool
    {
        if (! $this->apiKey || ! $this->baseUrl) {
            return false;
        }

        $payload = $this->buildTrainingPayload();

        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->timeout(60)
                ->post("{$this->baseUrl}/train", $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('AI training failed with status '.$response->status().': '.$response->body());

            return false;
        } catch (\Exception $e) {
            Log::error('AI training failed: '.$e->getMessage());

            return false;
        }
    }

    public function healthCheck(): bool
    {
        if (! $this->apiKey || ! $this->baseUrl) {
            return false;
        }

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
