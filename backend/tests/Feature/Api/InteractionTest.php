<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_store_interactions_with_bearer_token(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $token = $user->createToken('smoke-test')->plainTextToken;

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
            ->postJson('/api/interactions/batch', [
                'interactions' => [
                    [
                        'productId' => $product->id,
                        'action' => 'view',
                        'score' => 1,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('data.count', 1);

        $this->assertDatabaseHas('user_interactions', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'type' => 'view',
            'score' => 1,
        ]);
    }
}
