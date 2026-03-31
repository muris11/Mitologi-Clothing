<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
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
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('hero-slides', 'public');
        }

        HeroSlide::create($data);

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
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image', '_token', '_method']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($heroSlide->image_url) {
                Storage::disk('public')->delete($heroSlide->image_url);
            }
            $data['image_url'] = $request->file('image')->store('hero-slides', 'public');
        }

        $heroSlide->update($data);

        FrontendCacheService::revalidate(['hero-slides', 'landing-page']);

        return redirect()->route('admin.beranda.hero-slides.index')->with('success', 'Slide berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->image_url) {
            Storage::disk('public')->delete($heroSlide->image_url);
        }
        
        $heroSlide->delete();

        FrontendCacheService::revalidate(['hero-slides', 'landing-page']);

        return redirect()->route('admin.beranda.hero-slides.index')->with('success', 'Slide berhasil dihapus.');
    }
}

