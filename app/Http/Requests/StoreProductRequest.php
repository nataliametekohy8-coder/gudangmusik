<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh mengirim request ini.
     * Semua user yang sudah login boleh menambah produk.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Aturan validasi untuk menyimpan produk baru.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'brand' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'buy_price' => ['required', 'integer', 'min:0'],
            'sell_price' => ['required', 'integer', 'min:0', 'gte:buy_price'],
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
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak ditemukan.',
            'name.required' => 'Nama produk wajib diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'name.max' => 'Nama produk maksimal 150 karakter.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.string' => 'SKU harus berupa teks.',
            'sku.max' => 'SKU maksimal 50 karakter.',
            'sku.unique' => 'SKU sudah digunakan produk lain.',
            'brand.required' => 'Merek produk wajib diisi.',
            'brand.string' => 'Merek produk harus berupa teks.',
            'brand.max' => 'Merek produk maksimal 100 karakter.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'min_stock.required' => 'Stok minimum wajib diisi.',
            'min_stock.integer' => 'Stok minimum harus berupa angka bulat.',
            'min_stock.min' => 'Stok minimum tidak boleh kurang dari 0.',
            'buy_price.required' => 'Harga beli wajib diisi.',
            'buy_price.integer' => 'Harga beli harus berupa angka bulat (tanpa titik/koma).',
            'buy_price.min' => 'Harga beli tidak boleh kurang dari 0.',
            'sell_price.required' => 'Harga jual wajib diisi.',
            'sell_price.integer' => 'Harga jual harus berupa angka bulat (tanpa titik/koma).',
            'sell_price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'sell_price.gte' => 'Harga jual tidak boleh lebih kecil dari harga beli.',
        ];
    }
}
