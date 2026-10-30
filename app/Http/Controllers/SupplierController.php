<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Daftar supplier dengan jumlah produk masing-masing.
     */
    public function index(Request $request): View
    {
        // withCount('products') agar jumlah produk tidak menimbulkan query N+1.
        $suppliers = Supplier::query()
            ->withCount('products')
            ->when(filled($request->input('search')), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Form tambah supplier.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * Simpan supplier baru.
     */
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        // Data sudah tervalidasi lewat StoreSupplierRequest.
        $supplier = Supplier::create($request->validated());

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Supplier \"{$supplier->name}\" berhasil ditambahkan.");
    }

    /**
     * Detail supplier beserta daftar produk yang dipasoknya.
     */
    public function show(Supplier $supplier): View
    {
        $supplier->loadCount('products');

        // Produk dari supplier ini (hanya kolom yang dipakai tabel).
        $products = $supplier->products()
            ->select(['id', 'supplier_id', 'name', 'sku', 'stock', 'min_stock', 'sell_price'])
            ->orderBy('name')
            ->paginate(10);

        return view('suppliers.show', compact('supplier', 'products'));
    }

    /**
     * Form edit supplier.
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update supplier.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        // Data sudah tervalidasi lewat UpdateSupplierRequest.
        $supplier->update($request->validated());

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Supplier \"{$supplier->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus supplier. Ditolak bila masih dipakai produk.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->products()->exists()) {
            return redirect()
                ->route('suppliers.index')
                ->with('error', 'Supplier tidak bisa dihapus karena masih memiliki produk.');
        }

        $name = $supplier->name;
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', "Supplier \"{$name}\" berhasil dihapus.");
    }
}
