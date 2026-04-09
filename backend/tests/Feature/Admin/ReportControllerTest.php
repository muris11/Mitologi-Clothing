<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_top_products_uses_interaction_type_column(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['title' => 'Purchased Product']);

        UserInteraction::create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'type' => 'purchase',
            'score' => 5,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/top-products');

        $response->assertOk();
        $this->assertSame('Purchased Product', $response->json('0.title'));
    }

    public function test_admin_stock_recommendations_uses_variant_stock(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['title' => 'Low Stock Product']);

        ProductVariant::query()->where('product_id', $product->id)->delete();

        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 4,
            'reserved_stock' => 1,
        ]);

        UserInteraction::create([
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'type' => 'purchase',
            'score' => 5,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/reports/stock-recommendations?threshold=5');

        $response->assertOk();
        $this->assertSame('Low Stock Product', $response->json('0.title'));
    }
}
