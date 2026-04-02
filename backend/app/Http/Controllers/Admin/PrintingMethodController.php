<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintingMethod;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
            $slug = $originalSlug.'-'.$count;
            $count++;
        }
        $data['slug'] = $slug;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('printing_methods', 'public');
        }

        PrintingMethod::create($data);

        Cache::forget('api.landing_page_data_v2');
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
                $slug = $originalSlug.'-'.$count;
                $count++;
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($method->image) {
                if (Storage::disk('public')->exists($method->image)) {
                    Storage::disk('public')->delete($method->image);
                    Log::info('Old image deleted for printing method', [
                        'method_id' => $method->id,
                        'image_path' => $method->image,
                    ]);
                } else {
                    Log::warning('Old image not found for deletion', [
                        'method_id' => $method->id,
                        'image_path' => $method->image,
                    ]);
                }
            }
            $data['image'] = $request->file('image')->store('printing_methods', 'public');
        }

        $method->update($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['landing-page']);

        return redirect()->route('admin.layanan.printing-methods.index')->with('success', 'Teknik Printing berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $method = PrintingMethod::findOrFail($id);

        if ($method->image) {
            try {
                if (Storage::disk('public')->exists($method->image)) {
                    Storage::disk('public')->delete($method->image);
                    Log::info('Image deleted for printing method', [
                        'method_id' => $method->id,
                        'method_name' => $method->name,
                        'image_path' => $method->image,
                    ]);
                } else {
                    Log::warning('Image file not found for deletion', [
                        'method_id' => $method->id,
                        'method_name' => $method->name,
                        'image_path' => $method->image,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to delete printing method image', [
                    'method_id' => $method->id,
                    'image_path' => $method->image,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $method->delete();
        Log::info('Printing method deleted from database', [
            'method_id' => $method->id,
            'method_name' => $method->name,
            'method_slug' => $method->slug,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['landing-page']);

        return redirect()->route('admin.layanan.printing-methods.index')->with('success', 'Teknik Printing berhasil dihapus.');
    }
}
