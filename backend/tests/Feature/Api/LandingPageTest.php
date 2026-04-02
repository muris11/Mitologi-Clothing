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
                'data' => [
                    'heroSlides',
                    'features',
                    'testimonials',
                    'materials',
                    'portfolioItems',
                    'orderSteps',
                    'cta' => ['title', 'subtitle', 'buttonText', 'buttonLink'],
                    'siteSettings',
                    'categories',
                    'newArrivals',
                    'bestSellers',
                    'teamMembers',
                ],
            ]);
    }

    public function test_products_endpoint_returns_paginated_data(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'products',
                    'pagination' => ['total', 'perPage', 'currentPage', 'lastPage'],
                ],
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
