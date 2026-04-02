<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order', 'asc')->get();

        return view('admin.beranda.hero-slides.index', compact('slides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.beranda.hero-slides.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('HeroSlide store request received', [
            'has_image_file' => $request->hasFile('image'),
            'has_image_input' => $request->has('image'),
            'all_inputs' => $request->except(['image']),
            'files_keys' => array_keys($request->allFiles()),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,webp|max:5120',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            Log::info('Processing uploaded image', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'is_valid' => $file->isValid(),
                'error' => $file->getError(),
            ]);

            // Store the image as-is (JavaScript cropper already processed it to 1920x1080)
            $filename = 'hero-slides/'.uniqid().'.webp';
            $storedPath = $file->storeAs('', $filename, 'public');
            $data['image_url'] = $storedPath;

            Log::info('Hero slide image uploaded', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $data['image_url'],
                'filename' => $filename,
                'storage_disk' => 'public',
                'size_bytes' => $file->getSize(),
            ]);
        } else {
            Log::warning('No image file found in request', [
                'request_files' => $request->allFiles(),
                'request_input' => $request->all(),
            ]);
        }

        $heroSlide = HeroSlide::create($data);

        Log::info('Hero slide created', [
            'slide_id' => $heroSlide->id,
            'image_url' => $heroSlide->image_url,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['hero-slides', 'landing-page']);

        return redirect()->route('admin.beranda.hero-slides.index')->with('success', 'Slide berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.beranda.hero-slides.edit', compact('heroSlide'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeroSlide $heroSlide)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image', '_token', '_method']);
        $data['is_active'] = $request->has('is_active');

        // Handle delete image request
        if ($request->has('delete_image') && $request->delete_image == '1') {
            if ($heroSlide->image_url) {
                $disk = Storage::disk('public');
                if ($disk->exists($heroSlide->image_url)) {
                    $deleted = $disk->delete($heroSlide->image_url);
                    Log::info('Hero slide image deleted via checkbox', [
                        'slide_id' => $heroSlide->id,
                        'image_path' => $heroSlide->image_url,
                        'deleted' => $deleted,
                    ]);
                } else {
                    Log::warning('Hero slide image file not found when deleting via checkbox', [
                        'slide_id' => $heroSlide->id,
                        'image_path' => $heroSlide->image_url,
                    ]);
                }
            }
            $data['image_url'] = null;
        }
        // Handle upload new image
        elseif ($request->hasFile('image')) {
            $file = $request->file('image');

            // Delete old image if exists
            if ($heroSlide->image_url) {
                $disk = Storage::disk('public');
                if ($disk->exists($heroSlide->image_url)) {
                    $deleted = $disk->delete($heroSlide->image_url);
                    Log::info('Old hero slide image deleted before upload', [
                        'slide_id' => $heroSlide->id,
                        'image_path' => $heroSlide->image_url,
                        'deleted' => $deleted,
                    ]);
                }
            }

            // Store the new image as-is (JavaScript cropper already processed it)
            $filename = 'hero-slides/'.uniqid().'.webp';
            $data['image_url'] = $file->storeAs('', $filename, 'public');

            Log::info('Hero slide image updated', [
                'slide_id' => $heroSlide->id,
                'stored_path' => $data['image_url'],
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        $heroSlide->update($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['hero-slides', 'landing-page']);

        return redirect()->route('admin.beranda.hero-slides.index')->with('success', 'Slide berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSlide $heroSlide)
    {
        Log::info('Deleting hero slide', [
            'slide_id' => $heroSlide->id,
            'title' => $heroSlide->title,
            'image_url' => $heroSlide->image_url,
        ]);

        // Delete image file if exists
        if ($heroSlide->image_url) {
            $disk = Storage::disk('public');

            if ($disk->exists($heroSlide->image_url)) {
                $deleted = $disk->delete($heroSlide->image_url);
                Log::info('Hero slide image deletion result', [
                    'slide_id' => $heroSlide->id,
                    'image_path' => $heroSlide->image_url,
                    'deleted' => $deleted,
                ]);

                if (! $deleted) {
                    Log::error('Failed to delete hero slide image', [
                        'slide_id' => $heroSlide->id,
                        'image_path' => $heroSlide->image_url,
                    ]);
                }
            } else {
                Log::warning('Hero slide image file not found in storage', [
                    'slide_id' => $heroSlide->id,
                    'image_path' => $heroSlide->image_url,
                ]);
            }
        }

        // Delete from database
        $deleted = $heroSlide->delete();

        Log::info('Hero slide deleted from database', [
            'slide_id' => $heroSlide->id,
            'deleted' => $deleted,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['hero-slides', 'landing-page']);

        return redirect()->route('admin.beranda.hero-slides.index')->with('success', 'Slide berhasil dihapus.');
    }
}
