<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);

        return view('admin.beranda.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.beranda.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['avatar']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['testimonials', 'landing-page']);

        return redirect()->route('admin.beranda.testimonials.index')->with('success', 'Testimonial berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.beranda.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['avatar', '_token', '_method']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar_url) {
                if (Storage::disk('public')->exists($testimonial->avatar_url)) {
                    Storage::disk('public')->delete($testimonial->avatar_url);
                    Log::info('Deleted old avatar', ['avatar_url' => $testimonial->avatar_url, 'testimonial_id' => $testimonial->id]);
                } else {
                    Log::warning('Avatar file not found for deletion', ['avatar_url' => $testimonial->avatar_url, 'testimonial_id' => $testimonial->id]);
                }
            }
            $data['avatar_url'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['testimonials', 'landing-page']);

        return redirect()->route('admin.beranda.testimonials.index')->with('success', 'Testimonial berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->avatar_url) {
            if (Storage::disk('public')->exists($testimonial->avatar_url)) {
                try {
                    Storage::disk('public')->delete($testimonial->avatar_url);
                    Log::info('Successfully deleted testimonial avatar', [
                        'avatar_url' => $testimonial->avatar_url,
                        'testimonial_id' => $testimonial->id,
                        'testimonial_name' => $testimonial->name,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to delete testimonial avatar', [
                        'avatar_url' => $testimonial->avatar_url,
                        'testimonial_id' => $testimonial->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::warning('Testimonial avatar file not found for deletion', [
                    'avatar_url' => $testimonial->avatar_url,
                    'testimonial_id' => $testimonial->id,
                ]);
            }
        }

        $testimonial->delete();
        Log::info('Testimonial deleted from database', [
            'testimonial_id' => $testimonial->id,
            'testimonial_name' => $testimonial->name,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['testimonials', 'landing-page']);

        return redirect()->route('admin.beranda.testimonials.index')->with('success', 'Testimonial berhasil dihapus.');
    }
}
