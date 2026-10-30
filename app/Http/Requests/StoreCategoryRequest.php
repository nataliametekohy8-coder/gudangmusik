<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh mengirim request ini.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Aturan validasi untuk menyimpan kategori baru.
     *
     * Catatan: tabel "categories" saat ini hanya punya kolom "name".
     * Aturan "description" disiapkan agar siap dipakai bila kolomnya ditambahkan,
     * dengan ExceptionHandler mengecualikan request ini dari konversi ke 422.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
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
