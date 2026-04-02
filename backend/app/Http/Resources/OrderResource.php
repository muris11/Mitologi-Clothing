<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'orderNumber' => $this->order_number,
            'status' => $this->status,
            'trackingNumber' => $this->tracking_number,
            'total' => (float) $this->total,
            'subtotal' => (float) $this->subtotal,
            'items' => OrderItemResource::collection($this->items),
            'shippingAddress' => new AddressResource($this->shipping_address),
            'createdAt' => $this->created_at->toIso8601String(),
            'updatedAt' => $this->updated_at->toIso8601String(),
        ];
    }
}
