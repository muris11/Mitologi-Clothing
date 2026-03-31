<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
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
        Storage::disk('public')->delete($facility->image);
      }
      $data['image'] = $request->file('image')->store('facilities', 'public');
    }

    $facility->update($data);

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.tentang-kami.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
  }

  public function destroy($id)
  {
    $facility = Facility::findOrFail($id);

    if ($facility->image) {
      Storage::disk('public')->delete($facility->image);
    }

    $facility->delete();

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.tentang-kami.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
  }
}

