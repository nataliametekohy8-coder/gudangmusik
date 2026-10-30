<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh mengirim request ini.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Aturan validasi untuk mengubah kategori yang sudah ada.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Route model binding "category" bisa berupa objek Category atau id mentah.
        $categoryId = $this->route('category')->id ?? $this->route('category');

        return [
            // Nama unik, tetapi milik kategori ini sendiri diabaikan saat pengecekan.
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($categoryId)],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Pesan error dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
        ];
    }
}
