<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockTransactionRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh mengirim request ini.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Aturan validasi pencatatan transaksi stok.
     *
     * Catatan: kecukupan stok untuk tipe "out" divalidasi di controller
     * (di dalam DB transaction + lockForUpdate) agar bebas race condition.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
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
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak ditemukan.',
            'type.required' => 'Tipe transaksi wajib dipilih.',
            'type.in' => 'Tipe transaksi tidak valid.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Tanggal tidak valid.',
            'note.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
