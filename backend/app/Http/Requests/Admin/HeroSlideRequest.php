<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HeroSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];

        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|image|max:4096';
        } else {
            $rules['image'] = 'nullable|image|max:4096';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul slide wajib diisi.',
            'image.required' => 'Gambar slide wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 4MB.',
        ];
    }
}
