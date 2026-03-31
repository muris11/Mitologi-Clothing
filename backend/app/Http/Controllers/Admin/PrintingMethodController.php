<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintingMethod;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrintingMethodController extends Controller
{
  public function index()
  {
    $methods = PrintingMethod::orderBy('sort_order', 'asc')->get();
    return view('admin.layanan.printing-methods.index', compact('methods'));
  }

  public function create()
  {
    return view('admin.layanan.printing-methods.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'image' => 'nullable|image|max:2048',
      'pros' => 'nullable|array',
      'price_range' => 'nullable|string|max:255',
      'sort_order' => 'integer|min:0',
    ]);

    $data = $request->except(['image']);
    $data['is_active'] = $request->has('is_active');
    $data['pros'] = $request->pros ?? [];

    // Auto-generate slug from name
    $slug = Str::slug($request->name);
    $originalSlug = $slug;
    $count = 1;
    while (PrintingMethod::where('slug', $slug)->exists()) {
      $slug = $originalSlug . '-' . $count;
      $count++;
    }
    $data['slug'] = $slug;

    if ($request->hasFile('image')) {
      $data['image'] = $request->file('image')->store('printing_methods', 'public');
    }

    PrintingMethod::create($data);

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.layanan.printing-methods.index')->with('success', 'Teknik Printing berhasil ditambahkan.');
  }

  public function edit($id)
  {
    $method = PrintingMethod::findOrFail($id);
    return view('admin.layanan.printing-methods.edit', compact('method'));
  }

  public function update(Request $request, $id)
  {
    $method = PrintingMethod::findOrFail($id);

    $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'image' => 'nullable|image|max:2048',
      'pros' => 'nullable|array',
      'price_range' => 'nullable|string|max:255',
      'sort_order' => 'integer|min:0',
    ]);

    $data = $request->except(['image', '_token', '_method']);
    $data['is_active'] = $request->has('is_active');
    $data['pros'] = $request->pros ?? [];

    // Re-generate slug if name changed
    if ($request->name !== $method->name) {
      $slug = Str::slug($request->name);
      $originalSlug = $slug;
      $count = 1;
      while (PrintingMethod::where('slug', $slug)->where('id', '!=', $id)->exists()) {
        $slug = $originalSlug . '-' . $count;
        $count++;
      }
      $data['slug'] = $slug;
    }

    if ($request->hasFile('image')) {
      if ($method->image) {
        Storage::disk('public')->delete($method->image);
      }
      $data['image'] = $request->file('image')->store('printing_methods', 'public');
    }

    $method->update($data);

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.layanan.printing-methods.index')->with('success', 'Teknik Printing berhasil diperbarui.');
  }

  public function destroy($id)
  {
    $method = PrintingMethod::findOrFail($id);

    if ($method->image) {
      Storage::disk('public')->delete($method->image);
    }

    $method->delete();

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.layanan.printing-methods.index')->with('success', 'Teknik Printing berhasil dihapus.');
  }
}

