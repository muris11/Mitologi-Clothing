<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_returns_expected_structure(): void
    {
        $response = $this->getJson('/api/landing-page');

        $response->assertOk()
            ->assertJsonStructure([
                'hero_slides',
                'features',
                'testimonials',
                'materials',
                'portfolio_items',
                'order_steps',
                'cta' => ['title', 'subtitle', 'button_text', 'button_link'],
                'site_settings',
                'categories',
                'new_arrivals',
                'best_sellers',
                'team_members',
            ]);
    }

    public function test_products_endpoint_returns_paginated_data(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonStructure([
                'products',
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ]);
    }

    public function test_categories_endpoint_returns_data(): void
    {
        $response = $this->getJson('/api/categories');

        $response->assertOk();
    }

    public function test_site_settings_returns_data(): void
    {
        $response = $this->getJson('/api/site-settings');

        $response->assertOk();
    }
}
