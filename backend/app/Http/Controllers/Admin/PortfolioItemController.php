<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Category;

class PortfolioItemController extends Controller
{
    public function index()
    {
        $items = PortfolioItem::orderBy('sort_order', 'asc')->get();
        return view('admin.beranda.portfolio.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.beranda.portfolio.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        // Auto-generate slug from title
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (PortfolioItem::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('portfolio', 'public');
        }

        PortfolioItem::create($data);

        FrontendCacheService::revalidate(['portfolios']);

        return redirect()->route('admin.beranda.portfolio.index')->with('success', 'Portfolio item berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.beranda.portfolio.edit', compact('portfolioItem', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['image', '_token', '_method']);
        $data['is_active'] = $request->has('is_active');

        // Re-generate slug if title changed
        if ($request->title !== $portfolioItem->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while (PortfolioItem::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($portfolioItem->image_url) {
                Storage::disk('public')->delete($portfolioItem->image_url);
            }
            $data['image_url'] = $request->file('image')->store('portfolio', 'public');
        }

        $portfolioItem->update($data);

        FrontendCacheService::revalidate(['portfolios']);

        return redirect()->route('admin.beranda.portfolio.index')->with('success', 'Portfolio item berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $portfolioItem = PortfolioItem::findOrFail($id);
        
        if ($portfolioItem->image_url) {
            Storage::disk('public')->delete($portfolioItem->image_url);
        }
        
        $portfolioItem->delete();

        FrontendCacheService::revalidate(['portfolios']);

        return redirect()->route('admin.beranda.portfolio.index')->with('success', 'Portfolio item berhasil dihapus.');
    }
}

