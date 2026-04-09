<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiDocsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test API docs HTML page loads successfully
     */
    public function test_api_docs_page_loads(): void
    {
        $response = $this->get('/api/docs');

        $response->assertStatus(200)
            ->assertViewIs('api-docs')
            ->assertSee('API Documentation')
            ->assertSee('Mitologi Clothing REST API');
    }

    /**
     * Test API docs JSON endpoint returns valid JSON
     */
    public function test_api_docs_json_returns_valid_response(): void
    {
        $response = $this->getJson('/api/docs/json');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'version',
                'base_url',
                'documentation_url',
                'endpoints' => [
                    'public',
                    'cart',
                    'auth',
                    'profile',
                    'orders',
                    'wishlist',
                    'reviews',
                    'recommendations',
                    'chatbot',
                    'admin',
                    'webhooks',
                ],
            ])
            ->assertJson([
                'name' => 'Mitologi Clothing API',
                'version' => '1.0.0',
            ]);
    }

    /**
     * Test API docs JSON contains endpoint details
     */
    public function test_api_docs_contains_endpoint_details(): void
    {
        $response = $this->getJson('/api/docs/json');

        $response->assertStatus(200);

        $data = $response->json();

        // Check public endpoints exist
        $this->assertArrayHasKey('routes', $data['endpoints']['public']);
        $this->assertNotEmpty($data['endpoints']['public']['routes']);

        // Check auth endpoints exist
        $this->assertArrayHasKey('routes', $data['endpoints']['auth']);
        $this->assertNotEmpty($data['endpoints']['auth']['routes']);

        // Check a specific endpoint has required fields
        $firstEndpoint = $data['endpoints']['public']['routes'][0];
        $this->assertArrayHasKey('method', $firstEndpoint);
        $this->assertArrayHasKey('path', $firstEndpoint);
        $this->assertArrayHasKey('name', $firstEndpoint);
        $this->assertArrayHasKey('description', $firstEndpoint);
    }

    /**
     * Test API docs HTML contains method badges
     */
    public function test_api_docs_html_contains_method_badges(): void
    {
        $response = $this->get('/api/docs');

        $response->assertStatus(200)
            ->assertSee('GET')
            ->assertSee('POST')
            ->assertSee('PUT')
            ->assertSee('DELETE');
    }

    /**
     * Test API docs HTML contains authentication indicators
     */
    public function test_api_docs_shows_authentication_info(): void
    {
        $response = $this->get('/api/docs');

        $response->assertStatus(200)
            ->assertSee('Authentication')
            ->assertSee('Bearer Token')
            ->assertSee('X-Cart-Id')
            ->assertSee('X-Session-Id');
    }
}
