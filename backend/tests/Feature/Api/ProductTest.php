<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.products');
    }

    public function test_can_filter_products_by_ids()
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->take(2)->pluck('id')->implode(',');

        $response = $this->getJson("/api/products?ids={$ids}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data.products');
    }

    public function test_can_search_products()
    {
        Product::factory()->create(['title' => 'Unique Shirt']);
        Product::factory()->create(['title' => 'Pants']);

        $response = $this->getJson('/api/products?q=Unique');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.products')
            ->assertJsonFragment(['title' => 'Unique Shirt']);
    }

    public function test_best_sellers_uses_cart_interactions_in_ranking(): void
    {
        $user = User::factory()->create();
        $topProduct = Product::factory()->create([
            'title' => 'Top Product',
            'is_hidden' => false,
        ]);
        $otherProduct = Product::factory()->create([
            'title' => 'Other Product',
            'is_hidden' => false,
        ]);

        UserInteraction::create([
            'user_id' => $user->id,
            'product_id' => $topProduct->id,
            'type' => 'cart',
            'score' => 3,
        ]);

        UserInteraction::create([
            'user_id' => $user->id,
            'product_id' => $otherProduct->id,
            'type' => 'view',
            'score' => 1,
        ]);

        UserInteraction::create([
            'user_id' => $user->id,
            'product_id' => $otherProduct->id,
            'type' => 'view',
            'score' => 1,
        ]);

        $response = $this->getJson('/api/products/best-sellers?limit=2');

        $response->assertOk();
        $this->assertSame('Top Product', $response->json('data.0.title'));
    }
}
