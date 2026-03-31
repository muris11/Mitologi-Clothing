<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('sort_order')->paginate(10);
        return view('admin.beranda.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('admin.beranda.materials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'color_theme' => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Material::create([
            'name' => $request->name,
            'description' => $request->description,
            'color_theme' => $request->color_theme,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        FrontendCacheService::revalidate(['materials', 'landing-page']);

        return redirect()->route('admin.beranda.materials.index')->with('success', 'Material berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        return view('admin.beranda.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'color_theme' => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $material->update([
            'name' => $request->name,
            'description' => $request->description,
            'color_theme' => $request->color_theme,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        FrontendCacheService::revalidate(['materials', 'landing-page']);

        return redirect()->route('admin.beranda.materials.index')->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        FrontendCacheService::revalidate(['materials', 'landing-page']);
        $material->delete();
        return redirect()->route('admin.beranda.materials.index')->with('success', 'Material berhasil dihapus.');
    }
}

