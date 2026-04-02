<?php

namespace Tests\Feature;

use App\Http\Middleware\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class SanitizeInputTest extends TestCase
{
    private SanitizeInput $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new SanitizeInput;
    }

    public function test_strips_html_tags_from_string_input(): void
    {
        $request = Request::create('/test', 'POST', [
            'name' => '<script>alert("xss")</script>Test User',
            'comment' => '<b>Bold</b> review with <img src=x onerror=alert(1)>',
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertEquals('alert("xss")Test User', $req->input('name'));
            $this->assertEquals('Bold review with ', $req->input('comment'));

            return new Response;
        });
    }

    public function test_preserves_password_fields(): void
    {
        $request = Request::create('/test', 'POST', [
            'password' => '<strong>P@ssw0rd!</strong>',
            'password_confirmation' => '<strong>P@ssw0rd!</strong>',
            'current_password' => '<em>OldPass</em>',
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertEquals('<strong>P@ssw0rd!</strong>', $req->input('password'));
            $this->assertEquals('<strong>P@ssw0rd!</strong>', $req->input('password_confirmation'));
            $this->assertEquals('<em>OldPass</em>', $req->input('current_password'));

            return new Response;
        });
    }

    public function test_preserves_description_html_field(): void
    {
        $request = Request::create('/test', 'POST', [
            'description_html' => '<p>Rich <strong>HTML</strong> content</p>',
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertEquals('<p>Rich <strong>HTML</strong> content</p>', $req->input('description_html'));

            return new Response;
        });
    }

    public function test_sanitizes_nested_arrays(): void
    {
        $request = Request::create('/test', 'POST', [
            'shipping' => [
                'name' => '<script>xss</script>John',
                'address' => 'Jl. Normal No. 1',
            ],
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertEquals('xssJohn', $req->input('shipping.name'));
            $this->assertEquals('Jl. Normal No. 1', $req->input('shipping.address'));

            return new Response;
        });
    }

    public function test_leaves_non_string_values_untouched(): void
    {
        $request = Request::create('/test', 'POST', [
            'quantity' => 5,
            'active' => true,
            'price' => 150000.50,
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertEquals(5, $req->input('quantity'));
            $this->assertTrue($req->input('active'));
            $this->assertEquals(150000.50, $req->input('price'));

            return new Response;
        });
    }

    public function test_skips_get_requests(): void
    {
        $request = Request::create('/test', 'GET', [
            'search' => '<script>alert(1)</script>test',
        ]);

        $this->middleware->handle($request, function ($req) {
            // GET requests should be skipped - input not sanitized
            $this->assertEquals('<script>alert(1)</script>test', $req->input('search'));

            return new Response;
        });
    }
}
