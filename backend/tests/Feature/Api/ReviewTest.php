<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create(['role' => 'customer']);

        $this->product = Product::create([
            'title' => 'Test Product',
            'handle' => 'test-product',
            'description' => 'A test product',
            'is_hidden' => false,
        ]);
    }

    public function test_can_list_product_reviews(): void
    {
        ProductReview::create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'Produk kualitas sangat bagus!',
            'is_visible' => true,
        ]);

        $response = $this->getJson("/api/products/{$this->product->handle}/reviews");

        $response->assertOk()
            ->assertJsonStructure([
                'reviews',
                'summary' => ['average_rating', 'total_reviews', 'rating_breakdown'],
            ]);
    }

    public function test_review_requires_authentication(): void
    {
        $response = $this->postJson("/api/products/{$this->product->handle}/reviews", [
            'rating' => 5,
            'comment' => 'Produk sangat berkualitas tinggi!',
        ]);

        $response->assertUnauthorized();
    }

    public function test_review_validation_rejects_invalid_data(): void
    {
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson("/api/products/{$this->product->handle}/reviews", [
                'rating' => 10, // invalid: max 5
                'comment' => 'short', // invalid: min 10 chars
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating', 'comment']);
    }

    public function test_review_requires_purchase(): void
    {
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson("/api/products/{$this->product->handle}/reviews", [
                'rating' => 5,
                'comment' => 'Produk sangat berkualitas tinggi!',
            ]);

        // Should be forbidden because user hasn't purchased this product
        $response->assertForbidden();
    }
}
