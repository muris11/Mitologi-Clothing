<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\FrontendCacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->only(['name', 'slug', 'description', 'is_active']);
        $data['handle'] = $data['slug']; // Sync handle with slug for consistency
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['categories', 'landing-page', 'products']);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->only(['name', 'slug', 'description']);
        $data['handle'] = $data['slug'];
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($category->image) {
                if (Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                    Log::info('Category old image deleted', ['path' => $category->image, 'category_id' => $category->id]);
                } else {
                    Log::warning('Category old image not found for deletion', ['path' => $category->image, 'category_id' => $category->id]);
                }
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['categories', 'landing-page', 'products']);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $categoryId = $category->id;
            $categoryName = $category->name;

            if ($category->image) {
                if (Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                    Log::info('Category image deleted', ['path' => $category->image, 'category_id' => $categoryId, 'category_name' => $categoryName]);
                } else {
                    Log::warning('Category image not found for deletion', ['path' => $category->image, 'category_id' => $categoryId, 'category_name' => $categoryName]);
                }
            }

            $category->delete();
            Log::info('Category deleted from database', ['category_id' => $categoryId, 'category_name' => $categoryName]);

            Cache::forget('api.landing_page_data_v2');
            FrontendCacheService::revalidate(['categories', 'landing-page', 'products']);

            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete category', ['category_id' => $category->id, 'error' => $e->getMessage()]);

            return redirect()->route('admin.categories.index')->with('error', 'Gagal menghapus kategori: '.$e->getMessage());
        }
    }
}
