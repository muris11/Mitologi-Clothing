<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderStep;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderStepController extends Controller
{
    public function index()
    {
        $orderSteps = OrderStep::orderBy('step_number')->paginate(10);

        return view('admin.beranda.order-steps.index', compact('orderSteps'));
    }

    public function create()
    {
        $nextStep = OrderStep::max('step_number') + 1;

        return view('admin.beranda.order-steps.create', compact('nextStep'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:langsung,ecommerce',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        OrderStep::create([
            'step_number' => $request->step_number,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'sort_order' => $request->sort_order ?? $request->step_number,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['order-steps', 'landing-page']);

        return redirect()->route('admin.beranda.order-steps.index')->with('success', 'Langkah pemesanan berhasil ditambahkan.');
    }

    public function edit(OrderStep $order_step)
    {
        return view('admin.beranda.order-steps.edit', compact('order_step'));
    }

    public function update(Request $request, OrderStep $order_step)
    {
        $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:langsung,ecommerce',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $order_step->update([
            'step_number' => $request->step_number,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'sort_order' => $request->sort_order ?? $request->step_number,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['order-steps', 'landing-page']);

        return redirect()->route('admin.beranda.order-steps.index')->with('success', 'Langkah pemesanan berhasil diperbarui.');
    }

    public function destroy(OrderStep $order_step)
    {
        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['order-steps', 'landing-page']);
        $order_step->delete();

        return redirect()->route('admin.beranda.order-steps.index')->with('success', 'Langkah pemesanan berhasil dihapus.');
    }
}
