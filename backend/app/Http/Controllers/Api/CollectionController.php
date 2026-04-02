<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    use \App\Traits\FormatsProduct;

    public function index(): JsonResponse
    {
        $collections = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $formatted = $collections->map(fn ($c) => $this->formatCollection($c));

        $all = [
            'handle' => '',
            'title' => 'All',
            'description' => 'All products',
            'seo' => ['title' => 'All', 'description' => 'All products'],
            'path' => '/search',
            'updatedAt' => now()->toISOString(),
        ];

        return $this->successResponse(array_merge([$all], $formatted->toArray()));
    }

    public function show(string $handle): JsonResponse
    {
        $collection = Category::where('handle', $handle)->first();

        if (! $collection) {
            return $this->notFoundResponse('Collection');
        }

        return $this->successResponse($this->formatCollection($collection));
    }

    public function products(string $handle, Request $request): JsonResponse
    {
        $collection = Category::where('handle', $handle)->first();

        if (! $collection) {
            return $this->successResponse([]);
        }

        $query = $collection->products()
            ->with(['variants', 'options', 'images'])
            ->where('is_hidden', false);

        $sortKey = $request->input('sortKey', 'RELEVANCE');
        $reverse = filter_var($request->input('reverse', false), FILTER_VALIDATE_BOOLEAN);

        switch ($sortKey) {
            case 'PRICE':
                $query->orderBy(
                    Product::selectRaw('MIN(price)')
                        ->from('product_variants')
                        ->whereColumn('product_variants.product_id', 'products.id'),
                    $reverse ? 'desc' : 'asc'
                );
                break;
            case 'CREATED_AT':
                $query->orderBy('products.created_at', $reverse ? 'desc' : 'asc');
                break;
            default:
                $query->orderBy('products.created_at', 'desc');
        }

        $products = $query->get();

        $formatted = $products->map(fn ($p) => $this->formatProduct($p));

        return $this->successResponse($formatted);
    }

    private function formatCollection(Category $category): array
    {
        return [
            'handle' => $category->handle,
            'title' => $category->name,
            'description' => $category->description ?? '',
            'seo' => [
                'title' => $category->seo_title ?? $category->name,
                'description' => $category->seo_description ?? $category->description ?? '',
            ],
            'path' => '/search/'.$category->handle,
            'updatedAt' => $category->updated_at?->toISOString(),
        ];
    }
}
