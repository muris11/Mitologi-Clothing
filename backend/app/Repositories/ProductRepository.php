<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Product Repository
 *
 * Handles all database operations for Product model.
 */
class ProductRepository extends BaseRepository
{
    /**
     * Cache key prefix
     */
    protected const CACHE_PREFIX = 'products.';

    /**
     * Cache duration in seconds (1 hour)
     */
    protected const CACHE_DURATION = 3600;

    /**
     * Relations to eager load by default
     */
    protected array $defaultRelations = ['variants', 'options', 'images', 'categories'];

    /**
     * Constructor
     */
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    /**
     * Get all products with default relations
     */
    public function allWithRelations(): Collection
    {
        return Cache::remember(
            self::CACHE_PREFIX.'all',
            self::CACHE_DURATION,
            fn () => $this->all($this->defaultRelations)
        );
    }

    /**
     * Find product by handle
     *
     * @param  string  $handle  Product handle/slug
     */
    public function findByHandle(string $handle): ?Product
    {
        return Cache::remember(
            self::CACHE_PREFIX.'handle.'.$handle,
            self::CACHE_DURATION,
            fn () => $this->model
                ->with($this->defaultRelations)
                ->where('handle', $handle)
                ->first()
        );
    }

    /**
     * Search products by keyword
     *
     * @param  string  $query  Search query
     */
    public function search(string $query): Collection
    {
        return $this->model
            ->with(['variants', 'images'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('tags', 'like', "%{$query}%");
            })
            ->where('is_hidden', false)
            ->get();
    }

    /**
     * Get products by category ID
     *
     * @param  int  $categoryId  Category ID
     */
    public function getByCategory(int $categoryId): Collection
    {
        return Cache::remember(
            self::CACHE_PREFIX.'category.'.$categoryId,
            self::CACHE_DURATION,
            fn () => $this->model
                ->with(['variants', 'images'])
                ->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                })
                ->where('is_hidden', false)
                ->get()
        );
    }

    /**
     * Get featured products
     *
     * @param  int  $limit  Number of products to return
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return Cache::remember(
            self::CACHE_PREFIX.'featured.'.$limit,
            self::CACHE_DURATION,
            fn () => $this->model
                ->with(['variants', 'images'])
                ->where('is_hidden', false)
                ->whereNotNull('featured_image')
                ->latest()
                ->limit($limit)
                ->get()
        );
    }

    /**
     * Get new arrivals
     *
     * @param  int  $limit  Number of products to return
     */
    public function getNewArrivals(int $limit = 8): Collection
    {
        return Cache::remember(
            self::CACHE_PREFIX.'new.'.$limit,
            self::CACHE_DURATION,
            fn () => $this->model
                ->with(['variants', 'images'])
                ->where('is_hidden', false)
                ->latest()
                ->limit($limit)
                ->get()
        );
    }

    /**
     * Get best selling products
     *
     * @param  int  $limit  Number of products to return
     */
    public function getBestSellers(int $limit = 8): Collection
    {
        return Cache::remember(
            self::CACHE_PREFIX.'bestsellers.'.$limit,
            self::CACHE_DURATION,
            fn () => $this->model
                ->with(['variants', 'images'])
                ->where('is_hidden', false)
                ->withCount('interactions')
                ->orderBy('interactions_count', 'desc')
                ->limit($limit)
                ->get()
        );
    }

    /**
     * Clear product cache
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_PREFIX.'all');
        Cache::forget(self::CACHE_PREFIX.'featured');
        Cache::forget(self::CACHE_PREFIX.'new');
        Cache::forget(self::CACHE_PREFIX.'bestsellers');
    }
}
