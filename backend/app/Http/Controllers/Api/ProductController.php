<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserInteraction;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    use \App\Traits\FormatsProduct;

    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'api.products.v2.'.md5(json_encode($request->query()));
        $cacheTtl = 600;

        $result = Cache::remember($cacheKey, $cacheTtl, function () use ($request) {
            $query = Product::with(['variants', 'options', 'images', 'categories'])
                ->withAvg(['reviews' => fn ($q) => $q->where('is_visible', true)], 'rating')
                ->withCount(['reviews' => fn ($q) => $q->where('is_visible', true)])
                ->where('is_hidden', false);

            if ($ids = $request->input('ids')) {
                $query->whereIn('id', explode(',', $ids));
            }

            if ($search = $request->input('q')) {
                $query->where(function ($q) use ($search) {
                    $searchLower = strtolower($search);
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$searchLower}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"]);
                });
            }

            if ($categoryHandle = $request->input('category')) {
                $query->whereHas('categories', function ($q) use ($categoryHandle) {
                    $q->where('handle', $categoryHandle);
                });
            }

            if ($minPrice = $request->input('minPrice')) {
                $query->whereHas('variants', function ($q) use ($minPrice) {
                    $q->where('price', '>=', $minPrice);
                });
            }

            if ($maxPrice = $request->input('maxPrice')) {
                $query->whereHas('variants', function ($q) use ($maxPrice) {
                    $q->where('price', '<=', $maxPrice);
                });
            }

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
                    $query->orderBy('created_at', $reverse ? 'desc' : 'asc');
                    break;
                case 'BEST_SELLING':
                    $query->withCount('interactions')
                        ->orderBy('interactions_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            $limit = $request->input('limit', 20);
            $paginator = $query->paginate($limit);

            return [
                'products' => collect($paginator->items())->map(fn (Product $p) => $this->formatProduct($p)),
                'pagination' => [
                    'total' => $paginator->total(),
                    'perPage' => $paginator->perPage(),
                    'currentPage' => $paginator->currentPage(),
                    'lastPage' => $paginator->lastPage(),
                ],
            ];
        });

        return $this->successResponse($result);
    }

    public function show(string $handle): JsonResponse
    {
        $product = Product::with(['variants.selectedOptions', 'options', 'images', 'categories'])
            ->withAvg(['reviews' => fn ($q) => $q->where('is_visible', true)], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->where('is_visible', true)])
            ->where('handle', $handle)
            ->first();

        if (! $product) {
            return $this->notFoundResponse('Product');
        }

        if ($user = auth('sanctum')->user()) {
            UserInteraction::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'type' => 'view',
                'score' => 1,
            ]);
        }

        return $this->successResponse($this->formatProduct($product));
    }

    public function recommendations(int $id, RecommendationService $service): JsonResponse
    {
        $product = Product::find($id);

        if (! $product) {
            return $this->notFoundResponse('Product');
        }

        $baseQuery = Product::with(['variants', 'options', 'images', 'categories'])
            ->withAvg(['reviews' => fn ($q) => $q->where('is_visible', true)], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->where('is_visible', true)])
            ->where('is_hidden', false);

        $aiRecs = $service->forProduct($id);

        if (! empty($aiRecs)) {
            $products = (clone $baseQuery)->whereIn('id', $aiRecs)->get();
        } else {
            $categoryIds = $product->categories->pluck('id');
            $products = (clone $baseQuery)
                ->where('id', '!=', $id)
                ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
                ->limit(8)
                ->get();

            if ($products->isEmpty()) {
                $products = (clone $baseQuery)
                    ->where('id', '!=', $id)
                    ->inRandomOrder()
                    ->limit(8)
                    ->get();
            }
        }

        return $this->successResponse($products->map(fn (Product $p) => $this->formatProduct($p)));
    }

    public function newArrivals(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        $products = Product::with(['variants.selectedOptions', 'options', 'images', 'categories'])
            ->where('is_hidden', false)
            ->where('created_at', '>=', now()->subDays(60))
            ->withSum('interactions as interaction_score', 'score')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return $this->successResponse($products->map(fn (Product $p) => $this->formatProduct($p)));
    }

    public function bestSellers(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        $products = Product::with(['variants.selectedOptions', 'options', 'images', 'categories'])
            ->where('is_hidden', false)
            ->withSum('interactions as interaction_score', 'score')
            ->orderByDesc('interaction_score')
            ->limit($limit)
            ->get();

        return $this->successResponse($products->map(fn (Product $p) => $this->formatProduct($p)));
    }
}
