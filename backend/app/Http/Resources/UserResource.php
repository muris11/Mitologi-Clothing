<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatarUrl' => $this->avatar_url,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'postalCode' => $this->postal_code,
            'role' => $this->role,
            'createdAt' => $this->created_at?->toISOString(),
            'addresses' => $this->whenLoaded('addresses', function () {
                return $this->addresses->map(function ($address) {
                    return [
                        'id' => $address->id,
                        'label' => $address->label,
                        'recipientName' => $address->recipient_name,
                        'phone' => $address->phone,
                        'addressLine1' => $address->address_line_1,
                        'addressLine2' => $address->address_line_2,
                        'city' => $address->city,
                        'province' => $address->province,
                        'postalCode' => $address->postal_code,
                        'country' => $address->country,
                        'isPrimary' => $address->is_primary,
                    ];
                });
            }, []),
        ];
    }
}
