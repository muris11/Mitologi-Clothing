<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah tidak boleh negatif.',
        ];
    }
}
