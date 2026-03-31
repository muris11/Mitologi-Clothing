<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderBy('is_primary', 'desc')->get();
        return response()->json(['addresses' => $addresses]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean',
        ]);

        $validated['user_id'] = $request->user()->id;

        // If this is the first address or is_primary is true, set it as primary
        if ($validated['is_primary'] ?? false) {
            $request->user()->addresses()->update(['is_primary' => false]);
        }

        $address = Address::create($validated);

        return response()->json(['address' => $address], 201);
    }

    public function update(Request $request, Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'label' => 'sometimes|string|max:255',
            'recipient_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address_line_1' => 'sometimes|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'sometimes|string|max:255',
            'province' => 'sometimes|string|max:255',
            'postal_code' => 'sometimes|string|max:10',
            'country' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean',
        ]);

        // If setting as primary, unset other primary addresses
        if (($validated['is_primary'] ?? false) && !$address->is_primary) {
            $request->user()->addresses()->update(['is_primary' => false]);
        }

        $address->update($validated);

        return response()->json(['address' => $address]);
    }

    public function destroy(Request $request, Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
