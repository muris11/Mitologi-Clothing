<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $addresses = $request->user()->addresses()
            ->orderBy('is_primary', 'desc')
            ->get()
            ->map(fn ($address) => [
                'id' => $address->id,
                'label' => $address->label,
                'recipientName' => $address->recipient_name,
                'phone' => $address->phone,
                'addressLine1' => $address->address_line_1,
                'addressLine2' => $address->address_line_2,
                'city' => $address->city,
                'province' => $address->province,
                'postalCode' => $address->postal_code,
                'country' => $address->country ?? 'Indonesia',
                'isPrimary' => $address->is_primary,
                'createdAt' => $address->created_at,
            ]);

        return $this->successResponse($addresses);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recipientName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'addressLine1' => 'required|string|max:500',
            'addressLine2' => 'nullable|string|max:500',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postalCode' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'isPrimary' => 'nullable|boolean',
        ]);

        $validated['user_id'] = $request->user()->id;

        $validated['recipient_name'] = $validated['recipientName'];
        $validated['address_line_1'] = $validated['addressLine1'];
        $validated['address_line_2'] = $validated['addressLine2'] ?? null;
        $validated['postal_code'] = $validated['postalCode'];
        $validated['is_primary'] = $validated['isPrimary'] ?? false;

        unset($validated['recipientName'], $validated['addressLine1'], $validated['addressLine2'], $validated['postalCode'], $validated['isPrimary']);

        if ($validated['is_primary']) {
            $request->user()->addresses()->update(['is_primary' => false]);
        }

        $address = Address::create($validated);

        return $this->successResponse([
            'id' => $address->id,
            'label' => $address->label,
            'recipientName' => $address->recipient_name,
            'phone' => $address->phone,
            'addressLine1' => $address->address_line_1,
            'addressLine2' => $address->address_line_2,
            'city' => $address->city,
            'province' => $address->province,
            'postalCode' => $address->postal_code,
            'country' => $address->country ?? 'Indonesia',
            'isPrimary' => $address->is_primary,
        ], 'Alamat berhasil ditambahkan', 201);
    }

    public function update(Request $request, Address $address): JsonResponse
    {
        if ($address->user_id !== $request->user()->id) {
            return $this->forbiddenResponse('Unauthorized');
        }

        $validated = $request->validate([
            'label' => 'sometimes|string|max:255',
            'recipientName' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'addressLine1' => 'sometimes|string|max:500',
            'addressLine2' => 'nullable|string|max:500',
            'city' => 'sometimes|string|max:255',
            'province' => 'sometimes|string|max:255',
            'postalCode' => 'sometimes|string|max:10',
            'country' => 'nullable|string|max:100',
            'isPrimary' => 'nullable|boolean',
        ]);

        $mappedData = [];
        if (isset($validated['label'])) {
            $mappedData['label'] = $validated['label'];
        }
        if (isset($validated['recipientName'])) {
            $mappedData['recipient_name'] = $validated['recipientName'];
        }
        if (isset($validated['phone'])) {
            $mappedData['phone'] = $validated['phone'];
        }
        if (isset($validated['addressLine1'])) {
            $mappedData['address_line_1'] = $validated['addressLine1'];
        }
        if (array_key_exists('addressLine2', $validated)) {
            $mappedData['address_line_2'] = $validated['addressLine2'];
        }
        if (isset($validated['city'])) {
            $mappedData['city'] = $validated['city'];
        }
        if (isset($validated['province'])) {
            $mappedData['province'] = $validated['province'];
        }
        if (isset($validated['postalCode'])) {
            $mappedData['postal_code'] = $validated['postalCode'];
        }
        if (isset($validated['country'])) {
            $mappedData['country'] = $validated['country'];
        }
        if (isset($validated['isPrimary'])) {
            $mappedData['is_primary'] = $validated['isPrimary'];
        }

        if (! empty($mappedData['is_primary']) && ! $address->is_primary) {
            $request->user()->addresses()->update(['is_primary' => false]);
        }

        $address->update($mappedData);

        return $this->successResponse([
            'id' => $address->id,
            'label' => $address->label,
            'recipientName' => $address->recipient_name,
            'phone' => $address->phone,
            'addressLine1' => $address->address_line_1,
            'addressLine2' => $address->address_line_2,
            'city' => $address->city,
            'province' => $address->province,
            'postalCode' => $address->postal_code,
            'country' => $address->country ?? 'Indonesia',
            'isPrimary' => $address->is_primary,
        ], 'Alamat berhasil diperbarui');
    }

    public function destroy(Request $request, Address $address): JsonResponse
    {
        if ($address->user_id !== $request->user()->id) {
            return $this->forbiddenResponse('Unauthorized');
        }

        $address->delete();

        return $this->successResponse(null, 'Alamat berhasil dihapus');
    }
}
