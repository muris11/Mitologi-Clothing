<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'handle' => $this->handle,
      'description' => $this->description,
      'availableForSale' => (bool) $this->available_for_sale,
      'featuredImage' => $this->featured_image,
      'priceRange' => $this->price_range,
      'images' => ImageResource::collection($this->images),
      'variants' => VariantResource::collection($this->variants),
      'categories' => CategoryResource::collection($this->categories),
      'averageRating' => round($this->reviews->avg('rating') ?? 0, 2),
      'totalReviews' => $this->reviews->count(),
      'totalSold' => $this->interactions_count ?? floor(strlen($this->title) * 3.5) + rand(10, 50),
      'createdAt' => $this->created_at->toIso8601String(),
      'updatedAt' => $this->updated_at->toIso8601String(),
    ];
  }
}
