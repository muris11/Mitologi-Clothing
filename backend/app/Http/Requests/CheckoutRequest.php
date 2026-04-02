<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shippingName' => 'required|string|max:255',
            'shippingPhone' => 'required|string|max:20',
            'shippingAddress' => 'required|string',
            'shippingCity' => 'required|string|max:255',
            'shippingProvince' => 'required|string|max:255',
            'shippingPostalCode' => 'required|string|max:10',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'shippingName.required' => 'Nama penerima wajib diisi.',
            'shippingPhone.required' => 'Nomor telepon wajib diisi.',
            'shippingAddress.required' => 'Alamat pengiriman wajib diisi.',
            'shippingCity.required' => 'Kota wajib diisi.',
            'shippingProvince.required' => 'Provinsi wajib diisi.',
            'shippingPostalCode.required' => 'Kode pos wajib diisi.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * Override to return standardized API error format.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'error' => [
                    'code' => 'validation_error',
                    'message' => 'Data yang diberikan tidak valid.',
                    'details' => $validator->errors()->toArray(),
                ],
            ], 422)
        );
    }
}
