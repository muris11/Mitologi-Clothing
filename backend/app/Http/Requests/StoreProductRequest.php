<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'handle' => 'required|string|max:255|unique:products,handle',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'available_for_sale' => 'boolean',
            'tags' => 'nullable|array',
        ];
    }
}
