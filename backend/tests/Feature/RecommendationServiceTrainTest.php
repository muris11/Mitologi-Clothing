<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\UserInteraction;
use App\Services\RecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecommendationServiceTrainTest extends TestCase
{
    use RefreshDatabase;

    public function test_train_sends_same_interaction_dataset_shape_as_export_endpoint(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['title' => 'Training Product']);

        UserInteraction::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'type' => 'cart',
            'score' => 3,
        ]);

        Http::fake([
            '*' => Http::response(['data' => null, 'message' => 'ok'], 200),
        ]);

        $service = app(RecommendationService::class);

        $result = $service->train();

        $this->assertTrue($result);

        Http::assertSent(function ($request) use ($product, $user) {
            $payload = $request->data();

            return $request->method() === 'POST'
                && str_contains($request->url(), '/train')
                && ($payload['interactions'][0]['user_id'] ?? null) === $user->id
                && ($payload['interactions'][0]['product_id'] ?? null) === $product->id
                && ($payload['interactions'][0]['action'] ?? null) === 'cart'
                && ($payload['interactions'][0]['score'] ?? null) === 3;
        });
    }
}
