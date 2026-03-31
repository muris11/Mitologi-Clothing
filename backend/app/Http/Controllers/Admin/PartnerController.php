<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
  public function index()
  {
    $partners = Partner::orderBy('sort_order', 'asc')->get();
    return view('admin.beranda.partners.index', compact('partners'));
  }

  public function create()
  {
    return view('admin.beranda.partners.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'logo' => 'nullable|image|max:2048',
      'website_url' => 'nullable|url|max:255',
      'description' => 'nullable|string',
      'sort_order' => 'integer|min:0',
    ]);

    $data = $request->except(['logo']);
    $data['is_active'] = $request->has('is_active');

    if ($request->hasFile('logo')) {
      $data['logo'] = $request->file('logo')->store('partners', 'public');
    }

    Partner::create($data);

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.beranda.partners.index')->with('success', 'Partner berhasil ditambahkan.');
  }

  public function edit($id)
  {
    $partner = Partner::findOrFail($id);
    return view('admin.beranda.partners.edit', compact('partner'));
  }

  public function update(Request $request, $id)
  {
    $partner = Partner::findOrFail($id);

    $request->validate([
      'name' => 'required|string|max:255',
      'logo' => 'nullable|image|max:2048',
      'website_url' => 'nullable|url|max:255',
      'description' => 'nullable|string',
      'sort_order' => 'integer|min:0',
    ]);

    $data = $request->except(['logo', '_token', '_method']);
    $data['is_active'] = $request->has('is_active');

    if ($request->hasFile('logo')) {
      if ($partner->logo) {
        Storage::disk('public')->delete($partner->logo);
      }
      $data['logo'] = $request->file('logo')->store('partners', 'public');
    }

    $partner->update($data);

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.beranda.partners.index')->with('success', 'Partner berhasil diperbarui.');
  }

  public function destroy($id)
  {
    $partner = Partner::findOrFail($id);

    if ($partner->logo) {
      Storage::disk('public')->delete($partner->logo);
    }

    $partner->delete();

    FrontendCacheService::revalidate(['landing-page']);

    return redirect()->route('admin.beranda.partners.index')->with('success', 'Partner berhasil dihapus.');
  }
}

