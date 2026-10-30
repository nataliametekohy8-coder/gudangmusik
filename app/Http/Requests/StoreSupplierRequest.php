<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh mengirim request ini.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Aturan validasi untuk menyimpan supplier baru.
     *
     * Tabel "suppliers" saat ini berisi kolom: name, phone, address.
     * Rule "email" disiapkan agar siap dipakai bila kolomnya ditambahkan.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', 'unique:suppliers,name'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
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
            'name.required' => 'Nama supplier wajib diisi.',
            'name.max' => 'Nama supplier maksimal 150 karakter.',
            'name.unique' => 'Nama supplier sudah digunakan.',
            'phone.required' => 'Telepon wajib diisi.',
            'phone.max' => 'Telepon maksimal 30 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 150 karakter.',
            'address.max' => 'Alamat maksimal 500 karakter.',
        ];
    }
}
