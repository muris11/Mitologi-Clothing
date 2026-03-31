<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn(Category $category) => $this->formatCategory($category));

        return response()->json($categories);
    }

    public function show(string $handle): JsonResponse
    {
        $category = Category::query()
            ->where('handle', $handle)
            ->where('is_active', true)
            ->withCount('products')
            ->first();

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($this->formatCategory($category));
    }

    private function formatCategory(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'handle' => $category->handle,
            'description' => $category->description,
            'image' => $category->image,
            'products_count' => $category->products_count ?? 0,
        ];
    }
}
