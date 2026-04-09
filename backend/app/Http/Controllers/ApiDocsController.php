<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ApiDocsController extends Controller
{
    /**
     * Display API documentation as HTML (Tailwind CSS styled)
     */
    public function index(): View
    {
        $endpoints = $this->getEndpoints();
        $baseUrl = config('app.url').'/api/v1';

        return view('api-docs', compact('endpoints', 'baseUrl'));
    }

    /**
     * Get API documentation as JSON
     */
    public function json(): JsonResponse
    {
        return response()->json([
            'name' => 'Mitologi Clothing API',
            'version' => '1.0.0',
            'base_url' => config('app.url').'/api/v1',
            'documentation_url' => config('app.url').'/api/docs',
            'endpoints' => $this->getEndpoints(),
        ]);
    }

    /**
     * Get all API endpoints definition
     */
    private function getEndpoints(): array
    {
        return [
            'public' => [
                'title' => 'Public API (No Authentication Required)',
                'icon' => 'globe',
                'description' => 'These endpoints are accessible without authentication',
                'routes' => [
                    [
                        'method' => 'GET',
                        'path' => '/landing-page',
                        'name' => 'landing-page',
                        'description' => 'Get landing page with hero slides, products, testimonials, team members',
                        'auth' => false,
                        'params' => [],
                        'response' => [
                            'hero_slides' => 'array - Carousel slides',
                            'features' => 'array - Feature highlights',
                            'testimonials' => 'array - Customer reviews',
                            'materials' => 'array - Available materials',
                            'products' => 'array - Featured products',
                            'team_members' => 'array - Team structure',
                            'site_settings' => 'object - Site configuration',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/site-settings',
                        'name' => 'site-settings',
                        'description' => 'Get site configuration and settings',
                        'auth' => false,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/products',
                        'name' => 'products.index',
                        'description' => 'List all products with filtering, sorting, and pagination',
                        'auth' => false,
                        'params' => [
                            ['name' => 'q', 'type' => 'string', 'required' => false, 'description' => 'Search query (title, description)'],
                            ['name' => 'category', 'type' => 'string', 'required' => false, 'description' => 'Category handle filter'],
                            ['name' => 'sortKey', 'type' => 'string', 'required' => false, 'default' => 'RELEVANCE', 'options' => ['RELEVANCE', 'PRICE', 'CREATED_AT', 'BEST_SELLING'], 'description' => 'Sort field'],
                            ['name' => 'reverse', 'type' => 'boolean', 'required' => false, 'default' => 'false', 'description' => 'Reverse sort order'],
                            ['name' => 'minPrice', 'type' => 'number', 'required' => false, 'description' => 'Minimum price filter'],
                            ['name' => 'maxPrice', 'type' => 'number', 'required' => false, 'description' => 'Maximum price filter'],
                            ['name' => 'page', 'type' => 'integer', 'required' => false, 'default' => '1', 'description' => 'Page number'],
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '20', 'description' => 'Items per page (max 100)'],
                            ['name' => 'ids', 'type' => 'string', 'required' => false, 'description' => 'Comma-separated product IDs for specific lookup'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/products/best-sellers',
                        'name' => 'products.best-sellers',
                        'description' => 'Get best-selling products',
                        'auth' => false,
                        'params' => [
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '8', 'description' => 'Number of products to return'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/products/new-arrivals',
                        'name' => 'products.new-arrivals',
                        'description' => 'Get recently added products',
                        'auth' => false,
                        'params' => [
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '8', 'description' => 'Number of products to return'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/products/{handle}',
                        'name' => 'products.show',
                        'description' => 'Get detailed product information including variants and options',
                        'auth' => false,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Product URL handle (slug)'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/products/{handle}/reviews',
                        'name' => 'products.reviews',
                        'description' => 'Get product reviews and ratings',
                        'auth' => false,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Product URL handle'],
                            ['name' => 'page', 'type' => 'integer', 'required' => false, 'default' => '1', 'description' => 'Page number for pagination'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/products/{id}/recommendations',
                        'name' => 'products.recommendations',
                        'description' => 'Get AI-powered product recommendations based on product ID',
                        'auth' => false,
                        'params' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Product ID (numeric only)'],
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '5', 'description' => 'Number of recommendations'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/categories',
                        'name' => 'categories.index',
                        'description' => 'List all product categories',
                        'auth' => false,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/categories/{handle}',
                        'name' => 'categories.show',
                        'description' => 'Get category details with products',
                        'auth' => false,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Category URL handle'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/materials',
                        'name' => 'materials.index',
                        'description' => 'List all available materials',
                        'auth' => false,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/order-steps',
                        'name' => 'order-steps.index',
                        'description' => 'Get order process steps (cara pemesanan)',
                        'auth' => false,
                        'params' => [
                            ['name' => 'type', 'type' => 'string', 'required' => false, 'description' => 'Filter by step type'],
                            ['name' => 'grouped', 'type' => 'boolean', 'required' => false, 'default' => 'false', 'description' => 'Group steps by category'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/collections',
                        'name' => 'collections.index',
                        'description' => 'List all product collections',
                        'auth' => false,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/collections/{handle}',
                        'name' => 'collections.show',
                        'description' => 'Get collection details',
                        'auth' => false,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Collection handle'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/collections/{handle}/products',
                        'name' => 'collections.products',
                        'description' => 'Get products in a collection',
                        'auth' => false,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Collection handle'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/menus/{handle}',
                        'name' => 'menus.show',
                        'description' => 'Get navigation menu by handle',
                        'auth' => false,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Menu handle (e.g., main-menu, footer-menu)'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/pages',
                        'name' => 'pages.index',
                        'description' => 'List all CMS pages',
                        'auth' => false,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/pages/{handle}',
                        'name' => 'pages.show',
                        'description' => 'Get CMS page content (tentang-kami, layanan, kontak, etc.)',
                        'auth' => false,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Page handle (tentang-kami, layanan, kontak, kebijakan-privasi, kebijakan-pengembalian, syarat-ketentuan)'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/portfolios',
                        'name' => 'portfolios.index',
                        'description' => 'List all portfolio items',
                        'auth' => false,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/portfolios/{slug}',
                        'name' => 'portfolios.show',
                        'description' => 'Get portfolio item details',
                        'auth' => false,
                        'params' => [
                            ['name' => 'slug', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Portfolio item slug'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/team-members/{id}/photo',
                        'name' => 'team-members.photo',
                        'description' => 'Serve team member photo (bypass Windows symlink issues)',
                        'auth' => false,
                        'params' => [
                            ['name' => 'id', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Team member ID'],
                        ],
                    ],
                ],
            ],

            'cart' => [
                'title' => 'Cart API (Session-based)',
                'icon' => 'shopping-cart',
                'description' => 'Cart operations with session-based authentication. Use X-Cart-Id or X-Session-Id headers.',
                'routes' => [
                    [
                        'method' => 'POST',
                        'path' => '/cart',
                        'name' => 'cart.create',
                        'description' => 'Create a new cart with session ID',
                        'auth' => false,
                        'headers' => ['X-Session-Id (optional)'],
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/cart',
                        'name' => 'cart.show',
                        'description' => 'Get current cart with items and totals',
                        'auth' => false,
                        'headers' => ['X-Cart-Id', 'X-Session-Id'],
                        'params' => [
                            ['name' => 'id', 'type' => 'string', 'required' => false, 'description' => 'Cart session ID (alternative to header)'],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/cart/items',
                        'name' => 'cart.add-item',
                        'description' => 'Add item to cart',
                        'auth' => false,
                        'headers' => ['X-Cart-Id', 'X-Session-Id'],
                        'params' => [
                            ['name' => 'merchandiseId', 'type' => 'string', 'required' => true, 'description' => 'Product variant ID'],
                            ['name' => 'quantity', 'type' => 'integer', 'required' => true, 'description' => 'Quantity to add'],
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/cart/items/{id}',
                        'name' => 'cart.update-item',
                        'description' => 'Update cart item quantity',
                        'auth' => false,
                        'headers' => ['X-Cart-Id', 'X-Session-Id'],
                        'params' => [
                            ['name' => 'id', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Cart line item ID'],
                            ['name' => 'merchandiseId', 'type' => 'string', 'required' => true, 'description' => 'Product variant ID'],
                            ['name' => 'quantity', 'type' => 'integer', 'required' => true, 'description' => 'New quantity'],
                        ],
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/cart/items/{id}',
                        'name' => 'cart.remove-item',
                        'description' => 'Remove item from cart',
                        'auth' => false,
                        'headers' => ['X-Cart-Id', 'X-Session-Id'],
                        'params' => [
                            ['name' => 'id', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Cart line item ID'],
                        ],
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/cart/clear',
                        'name' => 'cart.clear',
                        'description' => 'Clear all items from cart',
                        'auth' => false,
                        'headers' => ['X-Cart-Id', 'X-Session-Id'],
                        'params' => [],
                    ],
                ],
            ],

            'auth' => [
                'title' => 'Authentication',
                'icon' => 'lock',
                'description' => 'User authentication and password management',
                'routes' => [
                    [
                        'method' => 'POST',
                        'path' => '/auth/register',
                        'name' => 'auth.register',
                        'description' => 'Register new user account',
                        'auth' => false,
                        'params' => [
                            ['name' => 'name', 'type' => 'string', 'required' => true, 'description' => 'Full name'],
                            ['name' => 'email', 'type' => 'string', 'required' => true, 'description' => 'Valid email address'],
                            ['name' => 'password', 'type' => 'string', 'required' => true, 'description' => 'Password (min 8 characters)'],
                            ['name' => 'password_confirmation', 'type' => 'string', 'required' => true, 'description' => 'Confirm password'],
                            ['name' => 'phone', 'type' => 'string', 'required' => false, 'description' => 'Phone number'],
                        ],
                        'response' => ['user' => 'object', 'token' => 'string'],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/auth/login',
                        'name' => 'auth.login',
                        'description' => 'User login with optional cart merge',
                        'auth' => false,
                        'headers' => ['X-Cart-Id (optional) - Merges guest cart on login'],
                        'params' => [
                            ['name' => 'email', 'type' => 'string', 'required' => true, 'description' => 'Registered email'],
                            ['name' => 'password', 'type' => 'string', 'required' => true, 'description' => 'Password'],
                        ],
                        'response' => ['user' => 'object', 'token' => 'string'],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/auth/logout',
                        'name' => 'auth.logout',
                        'description' => 'Logout current user',
                        'auth' => true,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/auth/user',
                        'name' => 'auth.user',
                        'description' => 'Get current authenticated user',
                        'auth' => true,
                        'params' => [],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/auth/forgot-password',
                        'name' => 'auth.forgot-password',
                        'description' => 'Request password reset link via email',
                        'auth' => false,
                        'params' => [
                            ['name' => 'email', 'type' => 'string', 'required' => true, 'description' => 'Registered email address'],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/auth/reset-password',
                        'name' => 'auth.reset-password',
                        'description' => 'Reset password with token from email',
                        'auth' => false,
                        'params' => [
                            ['name' => 'token', 'type' => 'string', 'required' => true, 'description' => 'Reset token from email'],
                            ['name' => 'email', 'type' => 'string', 'required' => true, 'description' => 'Registered email'],
                            ['name' => 'password', 'type' => 'string', 'required' => true, 'description' => 'New password (min 8 chars)'],
                            ['name' => 'password_confirmation', 'type' => 'string', 'required' => true, 'description' => 'Confirm new password'],
                        ],
                    ],
                ],
            ],

            'profile' => [
                'title' => 'Profile & Addresses',
                'icon' => 'user',
                'description' => 'User profile management and address book',
                'routes' => [
                    [
                        'method' => 'GET',
                        'path' => '/profile',
                        'name' => 'profile.show',
                        'description' => 'Get user profile information',
                        'auth' => true,
                        'params' => [],
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/profile',
                        'name' => 'profile.update',
                        'description' => 'Update user profile',
                        'auth' => true,
                        'params' => [
                            ['name' => 'name', 'type' => 'string', 'required' => false, 'description' => 'Full name'],
                            ['name' => 'email', 'type' => 'string', 'required' => false, 'description' => 'Email address'],
                            ['name' => 'phone', 'type' => 'string', 'required' => false, 'description' => 'Phone number'],
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/profile/password',
                        'name' => 'profile.update-password',
                        'description' => 'Change password',
                        'auth' => true,
                        'params' => [
                            ['name' => 'current_password', 'type' => 'string', 'required' => true, 'description' => 'Current password'],
                            ['name' => 'password', 'type' => 'string', 'required' => true, 'description' => 'New password (min 8 chars)'],
                            ['name' => 'password_confirmation', 'type' => 'string', 'required' => true, 'description' => 'Confirm new password'],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/profile/avatar',
                        'name' => 'profile.update-avatar',
                        'description' => 'Update profile avatar image',
                        'auth' => true,
                        'params' => [
                            ['name' => 'avatar', 'type' => 'file', 'required' => true, 'description' => 'Avatar image file (jpg, png)'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/profile/addresses',
                        'name' => 'profile.addresses.index',
                        'description' => 'List all user addresses',
                        'auth' => true,
                        'params' => [],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/profile/addresses',
                        'name' => 'profile.addresses.store',
                        'description' => 'Add new address',
                        'auth' => true,
                        'params' => [
                            ['name' => 'label', 'type' => 'string', 'required' => true, 'description' => 'Address label (Home, Office, etc)'],
                            ['name' => 'recipient_name', 'type' => 'string', 'required' => true, 'description' => 'Recipient full name'],
                            ['name' => 'phone', 'type' => 'string', 'required' => true, 'description' => 'Contact phone'],
                            ['name' => 'address', 'type' => 'string', 'required' => true, 'description' => 'Full street address'],
                            ['name' => 'city', 'type' => 'string', 'required' => true, 'description' => 'City name'],
                            ['name' => 'postal_code', 'type' => 'string', 'required' => true, 'description' => 'Postal code'],
                            ['name' => 'is_default', 'type' => 'boolean', 'required' => false, 'default' => 'false', 'description' => 'Set as default address'],
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/profile/addresses/{address}',
                        'name' => 'profile.addresses.update',
                        'description' => 'Update existing address',
                        'auth' => true,
                        'params' => [
                            ['name' => 'address', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Address ID'],
                            ['name' => 'label', 'type' => 'string', 'required' => false, 'description' => 'Address label'],
                            ['name' => 'recipient_name', 'type' => 'string', 'required' => false, 'description' => 'Recipient name'],
                            ['name' => 'phone', 'type' => 'string', 'required' => false, 'description' => 'Contact phone'],
                            ['name' => 'address', 'type' => 'string', 'required' => false, 'description' => 'Street address'],
                            ['name' => 'city', 'type' => 'string', 'required' => false, 'description' => 'City'],
                            ['name' => 'postal_code', 'type' => 'string', 'required' => false, 'description' => 'Postal code'],
                            ['name' => 'is_default', 'type' => 'boolean', 'required' => false, 'description' => 'Set as default'],
                        ],
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/profile/addresses/{address}',
                        'name' => 'profile.addresses.destroy',
                        'description' => 'Delete address',
                        'auth' => true,
                        'params' => [
                            ['name' => 'address', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Address ID'],
                        ],
                    ],
                ],
            ],

            'orders' => [
                'title' => 'Orders & Checkout',
                'icon' => 'box',
                'description' => 'Order management and checkout process',
                'routes' => [
                    [
                        'method' => 'POST',
                        'path' => '/checkout',
                        'name' => 'checkout.process',
                        'description' => 'Process checkout and create order',
                        'auth' => true,
                        'params' => [
                            ['name' => 'cartId', 'type' => 'string', 'required' => true, 'description' => 'Cart session ID'],
                            ['name' => 'addressId', 'type' => 'integer', 'required' => true, 'description' => 'Shipping address ID'],
                            ['name' => 'shippingCost', 'type' => 'number', 'required' => true, 'description' => 'Shipping cost amount'],
                            ['name' => 'paymentMethod', 'type' => 'string', 'required' => true, 'options' => ['midtrans', 'bank_transfer', 'cod'], 'description' => 'Payment method'],
                            ['name' => 'notes', 'type' => 'string', 'required' => false, 'description' => 'Order notes/comments'],
                        ],
                        'response' => ['orderNumber' => 'string', 'snapToken' => 'string (Midtrans)', 'redirectUrl' => 'string'],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/orders',
                        'name' => 'orders.index',
                        'description' => 'List user orders with pagination',
                        'auth' => true,
                        'params' => [],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/orders/{orderNumber}',
                        'name' => 'orders.show',
                        'description' => 'Get order details',
                        'auth' => true,
                        'params' => [
                            ['name' => 'orderNumber', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Order number (ORD-2024-XXXX)'],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/orders/{orderNumber}/pay',
                        'name' => 'orders.pay',
                        'description' => 'Process payment for order',
                        'auth' => true,
                        'params' => [
                            ['name' => 'orderNumber', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Order number'],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/orders/{orderNumber}/confirm-payment',
                        'name' => 'orders.confirm-payment',
                        'description' => 'Confirm payment with proof upload',
                        'auth' => true,
                        'params' => [
                            ['name' => 'orderNumber', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Order number'],
                            ['name' => 'payment_proof', 'type' => 'file', 'required' => true, 'description' => 'Payment proof image'],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/orders/{orderNumber}/request-refund',
                        'name' => 'orders.request-refund',
                        'description' => 'Request order refund',
                        'auth' => true,
                        'params' => [
                            ['name' => 'orderNumber', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Order number'],
                            ['name' => 'reason', 'type' => 'string', 'required' => true, 'description' => 'Refund reason'],
                        ],
                    ],
                ],
            ],

            'wishlist' => [
                'title' => 'Wishlist',
                'icon' => 'heart',
                'description' => 'User wishlist management',
                'routes' => [
                    [
                        'method' => 'GET',
                        'path' => '/wishlist',
                        'name' => 'wishlist.index',
                        'description' => 'Get user wishlist items',
                        'auth' => true,
                        'params' => [],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/wishlist/{productId}',
                        'name' => 'wishlist.store',
                        'description' => 'Add product to wishlist',
                        'auth' => true,
                        'params' => [
                            ['name' => 'productId', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Product ID'],
                        ],
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/wishlist/{productId}',
                        'name' => 'wishlist.destroy',
                        'description' => 'Remove product from wishlist',
                        'auth' => true,
                        'params' => [
                            ['name' => 'productId', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Product ID'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/wishlist/check/{productId}',
                        'name' => 'wishlist.check',
                        'description' => 'Check if product is in wishlist',
                        'auth' => true,
                        'params' => [
                            ['name' => 'productId', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Product ID'],
                        ],
                        'response' => ['inWishlist' => 'boolean'],
                    ],
                ],
            ],

            'reviews' => [
                'title' => 'Product Reviews',
                'icon' => 'star',
                'description' => 'Product review management (auth required for write)',
                'routes' => [
                    [
                        'method' => 'POST',
                        'path' => '/products/{handle}/reviews',
                        'name' => 'reviews.store',
                        'description' => 'Submit product review',
                        'auth' => true,
                        'params' => [
                            ['name' => 'handle', 'type' => 'string', 'required' => true, 'location' => 'path', 'description' => 'Product handle'],
                            ['name' => 'rating', 'type' => 'integer', 'required' => true, 'description' => 'Rating (1-5)'],
                            ['name' => 'comment', 'type' => 'string', 'required' => true, 'description' => 'Review text'],
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/reviews/{review}',
                        'name' => 'reviews.update',
                        'description' => 'Update existing review',
                        'auth' => true,
                        'params' => [
                            ['name' => 'review', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Review ID'],
                            ['name' => 'rating', 'type' => 'integer', 'required' => false, 'description' => 'New rating (1-5)'],
                            ['name' => 'comment', 'type' => 'string', 'required' => false, 'description' => 'New comment'],
                        ],
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/reviews/{review}',
                        'name' => 'reviews.destroy',
                        'description' => 'Delete review',
                        'auth' => true,
                        'params' => [
                            ['name' => 'review', 'type' => 'integer', 'required' => true, 'location' => 'path', 'description' => 'Review ID'],
                        ],
                    ],
                ],
            ],

            'recommendations' => [
                'title' => 'AI Recommendations',
                'icon' => 'robot',
                'description' => 'AI-powered product recommendations and ML tracking',
                'routes' => [
                    [
                        'method' => 'GET',
                        'path' => '/recommendations',
                        'name' => 'recommendations.user',
                        'description' => 'Get AI-personalized recommendations for user (Naive Bayes ML)',
                        'auth' => true,
                        'params' => [
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '10', 'description' => 'Number of recommendations'],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/interactions/batch',
                        'name' => 'interactions.batch',
                        'description' => 'Track user interactions for ML training (view, cart, purchase)',
                        'auth' => true,
                        'params' => [
                            ['name' => 'interactions', 'type' => 'array', 'required' => true, 'description' => 'Array of interaction objects'],
                        ],
                        'body_example' => [
                            'interactions' => [
                                ['productId' => 1, 'action' => 'view', 'score' => 1],
                                ['productId' => 2, 'action' => 'cart', 'score' => 3],
                                ['productId' => 3, 'action' => 'purchase', 'score' => 5],
                            ],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/ml/export-data',
                        'name' => 'ml.export-data',
                        'description' => 'Export training data for ML service (internal use)',
                        'auth' => false,
                        'internal' => true,
                        'params' => [
                            ['name' => 'months', 'type' => 'integer', 'required' => false, 'default' => '6', 'description' => 'Data history months'],
                        ],
                    ],
                ],
            ],

            'chatbot' => [
                'title' => 'AI Chatbot',
                'icon' => 'comments',
                'description' => 'Groq/Llama AI chatbot for customer service',
                'routes' => [
                    [
                        'method' => 'POST',
                        'path' => '/chatbot',
                        'name' => 'chatbot.chat',
                        'description' => 'Chat with AI assistant',
                        'auth' => false,
                        'params' => [
                            ['name' => 'message', 'type' => 'string', 'required' => true, 'description' => 'User message'],
                            ['name' => 'history', 'type' => 'array', 'required' => false, 'description' => 'Chat history for context'],
                        ],
                        'body_example' => [
                            'message' => 'What are your best sellers?',
                            'history' => [
                                ['role' => 'user', 'content' => 'Hello'],
                                ['role' => 'assistant', 'content' => 'Hi! How can I help?'],
                            ],
                        ],
                        'response' => ['reply' => 'string', 'products' => 'array|null'],
                    ],
                ],
            ],

            'admin' => [
                'title' => 'Admin API',
                'icon' => 'cog',
                'description' => 'Administrative endpoints (requires admin role)',
                'routes' => [
                    [
                        'method' => 'GET',
                        'path' => '/admin/reports/top-products',
                        'name' => 'admin.reports.top-products',
                        'description' => 'Get top selling products report',
                        'auth' => true,
                        'admin' => true,
                        'params' => [
                            ['name' => 'days', 'type' => 'integer', 'required' => false, 'default' => '30', 'description' => 'Report period in days'],
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '10', 'description' => 'Number of products'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/admin/reports/trending',
                        'name' => 'admin.reports.trending',
                        'description' => 'Get trending products report',
                        'auth' => true,
                        'admin' => true,
                        'params' => [
                            ['name' => 'days', 'type' => 'integer', 'required' => false, 'default' => '7', 'description' => 'Trend period in days'],
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '10', 'description' => 'Number of products'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/admin/reports/stock-recommendations',
                        'name' => 'admin.reports.stock',
                        'description' => 'Get stock recommendations (low stock + high demand)',
                        'auth' => true,
                        'admin' => true,
                        'params' => [
                            ['name' => 'threshold', 'type' => 'integer', 'required' => false, 'default' => '10', 'description' => 'Low stock threshold'],
                            ['name' => 'limit', 'type' => 'integer', 'required' => false, 'default' => '10', 'description' => 'Number of products'],
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/admin/ml/status',
                        'name' => 'admin.ml.status',
                        'description' => 'Get ML service health and training status',
                        'auth' => true,
                        'admin' => true,
                        'params' => [],
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/admin/ml/retrain',
                        'name' => 'admin.ml.retrain',
                        'description' => 'Trigger manual ML model retraining',
                        'auth' => true,
                        'admin' => true,
                        'params' => [],
                    ],
                    ],
                ],
            ],

            'webhooks' => [
                'title' => 'Webhooks',
                'icon' => 'webhook',
                'description' => 'Webhook endpoints for external services',
                'routes' => [
                    [
                        'method' => 'POST',
                        'path' => '/checkout/notification',
                        'name' => 'checkout.notification',
                        'description' => 'Midtrans payment notification webhook',
                        'auth' => false,
                        'webhook' => true,
                        'params' => [
                            ['name' => 'transaction_status', 'type' => 'string', 'required' => true, 'description' => 'Payment status'],
                            ['name' => 'order_id', 'type' => 'string', 'required' => true, 'description' => 'Order number'],
                            ['name' => 'signature_key', 'type' => 'string', 'required' => true, 'description' => 'Midtrans signature'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
