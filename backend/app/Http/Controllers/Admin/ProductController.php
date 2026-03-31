<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\VariantOption;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $query = Product::with('categories')->latest();

    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('handle', 'like', "%{$search}%");
      });
    }

    if ($request->filled('category')) {
      $query->whereHas('categories', function ($q) use ($request) {
        $q->where('categories.id', $request->category);
      });
    }

    $products = $query->paginate(10)->withQueryString();
    $categories = Category::orderBy('name')->get();
    return view('admin.products.index', compact('products', 'categories'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = Category::orderBy('name')->get();
    return view('admin.products.create', compact('categories'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'handle' => 'required|string|max:255|unique:products,handle',
      'price' => 'nullable|numeric|min:0',
      'stock' => 'nullable|integer|min:0',
      'image' => 'nullable|image|max:2048', // 2MB Max
      'gallery.*' => 'image|max:2048',
    ]);

    $data = $request->only(['title', 'handle', 'description', 'available_for_sale']);

    if ($request->hasFile('image')) {
      $data['featured_image'] = $request->file('image')->store('products', 'public');
    }

    $product = Product::create($data);

    if ($request->has('category_id')) {
      $product->categories()->sync([$request->category_id]);
    }

    // Handle Variants
    if ($request->boolean('has_variants')) {
      if ($request->has('variants') && is_array($request->variants)) {
        foreach ($request->variants as $variantData) {
          // Decode options if it's a JSON string
          $options = isset($variantData['options']) && is_string($variantData['options'])
            ? json_decode($variantData['options'], true)
            : ($variantData['options'] ?? []);

          $newVariant = $product->variants()->create([
            'title' => $variantData['name'],
            'price' => $variantData['price'],
            'stock' => $variantData['stock'],
            'options' => $options,
            'sku' => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8)),
          ]);
          $this->syncVariantOptions($newVariant);
        }
      }
    } else {
      // Create Default Variant if no variants provided (fallback)
      $product->variants()->create([
        'title' => 'Default Title',
        'price' => $request->price ?? 0,
        'stock' => $request->stock ?? 0,
        'sku' => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8)),
      ]);
    }

    // Handle Gallery
    if ($request->hasFile('gallery')) {
      foreach ($request->file('gallery') as $file) {
        $path = $file->store('products/gallery', 'public');
        $product->images()->create([
          'url' => $path,
          'sort_order' => 0,
        ]);
      }
    }

    FrontendCacheService::revalidate(['products', 'best-sellers', 'new-arrivals', 'categories']);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
  }

  public function show(Product $product)
  {
    $product->load(['variants', 'reviews.user' => function ($query) {
      $query->latest();
    }]);

    return view('admin.products.show', compact('product'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Product $product)
  {
    $categories = Category::all();
    return view('admin.products.edit', compact('product', 'categories'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Product $product)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'handle' => 'required|string|max:255|unique:products,handle,' . $product->id,
      'price' => 'nullable|numeric|min:0',
      'stock' => 'nullable|integer|min:0',
      'image' => 'nullable|image|max:2048',
      'gallery.*' => 'image|max:2048',
    ]);

    $data = $request->only(['title', 'handle', 'description']);
    $data['available_for_sale'] = $request->has('available_for_sale');

    if ($request->hasFile('image')) {
      // Delete old image if exists
      if ($product->featured_image) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($product->featured_image);
      }
      $data['featured_image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    if ($request->has('category_id')) {
      $product->categories()->sync([$request->category_id]);
    } else {
      $product->categories()->detach();
    }

    // Handle Variants
    // We strictly check the explicit "has_variants" flag from the form
    if ($request->boolean('has_variants')) {
      $currentVariantIds = [];

      if ($request->has('variants') && is_array($request->variants)) {
        foreach ($request->variants as $variantData) {
          $options = isset($variantData['options']) && is_string($variantData['options'])
            ? json_decode($variantData['options'], true)
            : ($variantData['options'] ?? []);

          if (isset($variantData['id'])) {
            $currentVariantIds[] = $variantData['id'];
            $product->variants()->where('id', $variantData['id'])->update([
              'title' => $variantData['name'],
              'price' => $variantData['price'],
              'stock' => $variantData['stock'],
              'options' => $options,
            ]);
          } else {
            // Create new variant handling
            $newVariant = $product->variants()->create([
              'title' => $variantData['name'],
              'price' => $variantData['price'],
              'stock' => $variantData['stock'],
              'options' => $options,
              'sku' => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8)),
            ]);
            $currentVariantIds[] = $newVariant->id;
          }

          // Sync variant_options table for proper API serialization
          $variantId = $variantData['id'] ?? $newVariant->id ?? null;
          if ($variantId) {
            $variant = $product->variants()->find($variantId);
            if ($variant) {
              $this->syncVariantOptions($variant);
            }
          }
        }
      }

      // Delete removed variants
      $product->variants()->whereNotIn('id', $currentVariantIds)->delete();
    } else {
      // Single Product Mode
      // Delete all existing variants (including any previous multi-variants)
      $product->variants()->delete();

      // Create one default variant
      $product->variants()->create([
        'title' => 'Default Title',
        'price' => $request->price ?? 0,
        'stock' => $request->stock ?? 0,
        'sku' => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8)),
      ]);
    }

    // Handle Gallery Deletions
    if ($request->has('deleted_images') && is_array($request->deleted_images)) {
      $imagesToDelete = \App\Models\ProductImage::whereIn('id', $request->deleted_images)
        ->where('product_id', $product->id)
        ->get();

      foreach ($imagesToDelete as $img) {
        if ($img instanceof \App\Models\ProductImage) {
          // Delete from storage
          \Illuminate\Support\Facades\Storage::disk('public')->delete($img->url);
          $img->delete();
        }
      }
    }

    // Handle Gallery Uploads
    if ($request->hasFile('gallery')) {
      foreach ($request->file('gallery') as $file) {
        $path = $file->store('products/gallery', 'public');
        $product->images()->create([
          'url' => $path,
          'sort_order' => 0,
        ]);
      }
    }

    FrontendCacheService::revalidate(['products', 'best-sellers', 'new-arrivals', 'categories']);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product)
  {
    if ($product->featured_image) {
      \Illuminate\Support\Facades\Storage::disk('public')->delete($product->featured_image);
    }

    $product->variants()->delete();
    $product->delete();

    FrontendCacheService::revalidate(['products', 'best-sellers', 'new-arrivals', 'categories']);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
  }

  /**
   * Parse variant title (e.g. "S / Black") and sync variant_options table.
   */
  private function syncVariantOptions($variant): void
  {
    // Delete existing options for this variant
    VariantOption::where('variant_id', $variant->id)->delete();

    $title = $variant->title ?? '';
    if (!$title || $title === 'Default Title') {
      return;
    }

    $parts = array_map('trim', explode('/', $title));
    $optionNames = ['Ukuran', 'Warna', 'Material', 'Style'];

    foreach ($parts as $i => $part) {
      VariantOption::create([
        'variant_id' => $variant->id,
        'name' => $optionNames[$i] ?? 'Option ' . ($i + 1),
        'value' => $part,
      ]);
    }
  }
}
