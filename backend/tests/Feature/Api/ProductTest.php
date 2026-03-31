<?php

namespace Tests\Feature\Api;

use App\Models\Product;
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
            ->assertJsonCount(3, 'products');
    }

    public function test_can_filter_products_by_ids()
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->take(2)->pluck('id')->implode(',');

        $response = $this->getJson("/api/products?ids={$ids}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'products');
    }

    public function test_can_search_products()
    {
        Product::factory()->create(['title' => 'Unique Shirt']);
        Product::factory()->create(['title' => 'Pants']);

        $response = $this->getJson('/api/products?q=Unique');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'products')
            ->assertJsonFragment(['title' => 'Unique Shirt']);
    }
}
