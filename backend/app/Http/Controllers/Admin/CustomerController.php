<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {

        $customer->load(['orders' => function ($query) {
            $query->latest();
        }]);

        // Calculate stats
        $stats = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->sum('total_price') ?? 0, // Assuming 'total_price' or 'total' column
            'last_order' => $customer->orders->first()?->created_at,
            'average_order_value' => $customer->orders->count() > 0
                ? $customer->orders->avg('total_price')
                : 0,
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);
        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        $data['role'] = 'customer';

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:500',
            // Add other fields as needed based on User model
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($customer->avatar) {
                try {
                    if (Storage::disk('public')->exists($customer->avatar)) {
                        Storage::disk('public')->delete($customer->avatar);
                        Log::info('Customer avatar deleted successfully', [
                            'customer_id' => $customer->id,
                            'avatar_path' => $customer->avatar,
                        ]);
                    } else {
                        Log::warning('Customer avatar file not found for deletion', [
                            'customer_id' => $customer->id,
                            'avatar_path' => $customer->avatar,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to delete customer avatar', [
                        'customer_id' => $customer->id,
                        'avatar_path' => $customer->avatar,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(User $customer)
    {

        // Check if customer has orders? Might want to soft delete or prevent delete if orders exist.
        // For simplicity, we'll allow delete but maybe we should warn.
        // If we delete user, orders might be orphaned or cascade delete depending on DB constraint.
        // Assuming cascade or we just delete.

        if ($customer->avatar) {
            try {
                if (Storage::disk('public')->exists($customer->avatar)) {
                    Storage::disk('public')->delete($customer->avatar);
                    Log::info('Customer avatar deleted during customer destroy', [
                        'customer_id' => $customer->id,
                        'avatar_path' => $customer->avatar,
                    ]);
                } else {
                    Log::warning('Customer avatar file not found during destroy', [
                        'customer_id' => $customer->id,
                        'avatar_path' => $customer->avatar,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to delete customer avatar during destroy', [
                    'customer_id' => $customer->id,
                    'avatar_path' => $customer->avatar,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $customer->delete();
        Log::info('Customer deleted from database', [
            'customer_id' => $customer->id,
            'customer_email' => $customer->email,
            'customer_name' => $customer->name,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
