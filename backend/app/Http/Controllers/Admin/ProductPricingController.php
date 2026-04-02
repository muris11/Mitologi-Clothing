<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductPricing;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductPricingController extends Controller
{
    public function index()
    {
        $pricings = ProductPricing::orderBy('sort_order', 'asc')->get();

        return view('admin.beranda.product-pricings.index', compact('pricings'));
    }

    public function create()
    {
        return view('admin.beranda.product-pricings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'min_order' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['items'] = $request->items ?? [];

        ProductPricing::create($data);

        FrontendCacheService::revalidate(['landing-page']);
        Cache::forget('api.landing_page_data_v2');

        return redirect()->route('admin.beranda.product-pricings.index')->with('success', 'Pricelist Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pricing = ProductPricing::findOrFail($id);

        return view('admin.beranda.product-pricings.edit', compact('pricing'));
    }

    public function update(Request $request, $id)
    {
        $pricing = ProductPricing::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255',
            'min_order' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->except(['_token', '_method']);
        $data['is_active'] = $request->has('is_active');
        $data['items'] = $request->items ?? [];

        $pricing->update($data);

        FrontendCacheService::revalidate(['landing-page']);
        Cache::forget('api.landing_page_data_v2');

        return redirect()->route('admin.beranda.product-pricings.index')->with('success', 'Pricelist Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pricing = ProductPricing::findOrFail($id);
        $pricing->delete();

        FrontendCacheService::revalidate(['landing-page']);
        Cache::forget('api.landing_page_data_v2');

        return redirect()->route('admin.beranda.product-pricings.index')->with('success', 'Pricelist Produk berhasil dihapus.');
    }
}
