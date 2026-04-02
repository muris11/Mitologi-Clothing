<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('sort_order', 'asc')->get();

        return view('admin.tentang-kami.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.tentang-kami.facilities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        Facility::create($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['landing-page']);

        return redirect()->route('admin.tentang-kami.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $facility = Facility::findOrFail($id);

        return view('admin.tentang-kami.facilities.edit', compact('facility'));
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image', '_token', '_method']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($facility->image) {
                if (Storage::disk('public')->exists($facility->image)) {
                    Storage::disk('public')->delete($facility->image);
                    Log::info('Facility image deleted during update', [
                        'facility_id' => $facility->id,
                        'image_path' => $facility->image,
                    ]);
                } else {
                    Log::warning('Facility image not found during update', [
                        'facility_id' => $facility->id,
                        'image_path' => $facility->image,
                    ]);
                }
            }
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        $facility->update($data);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['landing-page']);

        return redirect()->route('admin.tentang-kami.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        if ($facility->image) {
            try {
                if (Storage::disk('public')->exists($facility->image)) {
                    Storage::disk('public')->delete($facility->image);
                    Log::info('Facility image deleted', [
                        'facility_id' => $facility->id,
                        'image_path' => $facility->image,
                    ]);
                } else {
                    Log::warning('Facility image not found for deletion', [
                        'facility_id' => $facility->id,
                        'image_path' => $facility->image,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to delete facility image', [
                    'facility_id' => $facility->id,
                    'image_path' => $facility->image,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $facility->delete();
        Log::info('Facility deleted from database', [
            'facility_id' => $id,
            'facility_name' => $facility->name,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['landing-page']);

        return redirect()->route('admin.tentang-kami.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
