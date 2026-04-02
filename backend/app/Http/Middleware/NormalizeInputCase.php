<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to normalize request input case
 * Accepts both snake_case and camelCase, converts to camelCase for backend
 */
class NormalizeInputCase
{
    /**
     * Fields that need case normalization
     * Maps snake_case → camelCase
     */
    protected array $fieldMappings = [
        // Cart fields
        'cart_session_id' => 'cartSessionId',
        'cart_id' => 'cartId',

        // User fields
        'first_name' => 'firstName',
        'last_name' => 'lastName',
        'avatar_url' => 'avatarUrl',
        'phone_number' => 'phoneNumber',

        // Address fields
        'address_line_1' => 'addressLine1',
        'address_line_2' => 'addressLine2',
        'postal_code' => 'postalCode',
        'is_primary' => 'isPrimary',
        'recipient_name' => 'recipientName',

        // Product fields
        'average_rating' => 'averageRating',
        'total_reviews' => 'totalReviews',
        'total_stock' => 'totalStock',
        'available_for_sale' => 'availableForSale',
        'featured_image' => 'featuredImage',

        // Order fields
        'order_number' => 'orderNumber',
        'shipping_cost' => 'shippingCost',
        // 'shipping_address' => 'shippingAddress',  // REMOVED - keep snake_case for checkout
        'product_title' => 'productTitle',
        'variant_title' => 'variantTitle',

        // Wishlist fields
        'is_wishlisted' => 'isWishlisted',
        'product_id' => 'productId',

        // Payment fields
        'payment_method' => 'paymentMethod',
        'payment_status' => 'paymentStatus',
        'transaction_id' => 'transactionId',

        // Content fields
        'created_at' => 'createdAt',
        'updated_at' => 'updatedAt',
        'deleted_at' => 'deletedAt',
        'published_at' => 'publishedAt',
        'hero_slides' => 'heroSlides',
        'order_steps' => 'orderSteps',
        'printing_methods' => 'printingMethods',
        'product_pricings' => 'productPricings',
        'portfolio_items' => 'portfolioItems',
        'team_members' => 'teamMembers',
        'new_arrivals' => 'newArrivals',
        'best_sellers' => 'bestSellers',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Normalize request inputs
        $normalized = $this->normalizeArray($request->all());

        // Replace request data with normalized values
        $request->replace($normalized);

        $response = $next($request);

        // Note: Output case conversion disabled - API now returns camelCase consistently
        // for both Next.js frontend and Flutter mobile app

        return $response;
    }

    /**
     * Recursively normalize array keys (snake_case → camelCase)
     */
    protected function normalizeArray(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            $newKey = $this->fieldMappings[$key] ?? $key;

            if (is_array($value)) {
                $normalized[$newKey] = $this->normalizeArray($value);
            } else {
                $normalized[$newKey] = $value;
            }
        }

        return $normalized;
    }

    /**
     * Recursively convert array keys to snake_case (for Flutter compatibility)
     */
    protected function convertToSnakeCase(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $newKey = $this->camelToSnake($key);

            if (is_array($value)) {
                $result[$newKey] = $this->convertToSnakeCase($value);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Convert camelCase to snake_case
     */
    protected function camelToSnake(string $input): string
    {
        // Don't convert if already snake_case
        if (str_contains($input, '_')) {
            return $input;
        }

        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
}
