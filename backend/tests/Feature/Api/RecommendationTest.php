<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private $products;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->products = Product::factory()->count(15)->create([
            'is_hidden' => false,
            'available_for_sale' => true,
        ]);
        
        // Add random interactions
        foreach ($this->products as $product) {
            $product->interactions()->create([
                'user_id' => $this->user->id,
                'type' => 'view',
                'score' => rand(1, 5)
            ]);
        }
    }

    public function test_guest_cannot_access_recommendations()
    {
        $response = $this->getJson('/api/recommendations');
        $response->assertStatus(401);
    }

    public function test_auth_user_receives_recommendations()
    {
        $response = $this->actingAs($this->user)->getJson('/api/recommendations');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'recommendations' => [
                '*' => [
                    'id',
                    'handle',
                    'title',
                    'tags',
                    'availableForSale',
                    'priceRange',
                    'featuredImage'
                ]
            ]
        ]);
        
        // Default limit is 10
        $this->assertCount(10, $response->json('recommendations'));
    }

    public function test_auth_user_receives_limited_recommendations()
    {
        $response = $this->actingAs($this->user)->getJson('/api/recommendations?limit=3');
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('recommendations'));
    }
}
