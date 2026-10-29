<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan pencarian, filter, dan paginasi.
     */
    public function index(Request $request): View
    {
        // Ambil input filter dari query string.
        $search = trim((string) $request->query('search', ''));
        $categoryId = $request->query('category_id');
        $status = (string) $request->query('status', '');

        // Query dasar: relasi hanya kolom yang dibutuhkan (optimasi select).
        $products = Product::query()
            ->with([
                'category:id,name',
                'supplier:id,name',
            ])
            // Pencarian pada nama, SKU, atau merek.
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            // Filter kategori.
            ->when($categoryId !== null && $categoryId !== '', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            // Filter status stok: menipis, habis, atau aman.
            ->when($status === 'low', function ($query) {
                $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            })
            ->when($status === 'empty', function ($query) {
                $query->where('stock', '=', 0);
            })
            ->when($status === 'safe', function ($query) {
                $query->whereColumn('stock', '>', 'min_stock');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Data kategori untuk dropdown filter (hanya id & nama).
        $categories = Category::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create(): View
    {
        // Data untuk dropdown kategori dan supplier (hanya id & nama).
        $categories = Category::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        // Data sudah tervalidasi lewat StoreProductRequest.
        $product = Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil ditambahkan.");
    }

    /**
     * Menampilkan detail satu produk beserta riwayat transaksi stoknya.
     */
    public function show(Product $product): View
    {
        // Muat relasi kategori & supplier (hanya kolom yang dipakai).
        $product->load([
            'category:id,name',
            'supplier:id,name,phone',
        ]);

        // 10 transaksi terbaru untuk produk ini (dengan nama pencatatnya).
        $transactions = $product->stockTransactions()
            ->with(['user:id,name'])
            ->latest('transaction_date')
            ->latest('id')
            ->limit(10)
            ->get();

        return view('products.show', compact('product', 'transactions'));
    }

    /**
     * Menampilkan form ubah produk.
     */
    public function edit(Product $product): View
    {
        // Data untuk dropdown kategori dan supplier (hanya id & nama).
        $categories = Category::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Memperbarui data produk.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        // Data sudah tervalidasi lewat UpdateProductRequest (SKU milik sendiri diabaikan).
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil diperbarui.");
    }

    /**
     * Menghapus produk bila belum punya riwayat transaksi.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Produk yang punya transaksi tidak boleh dihapus (menjaga riwayat stok).
        if ($product->stockTransactions()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Produk tidak bisa dihapus karena masih ada transaksi.');
        }

        $name = $product->name;
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "Produk \"{$name}\" berhasil dihapus.");
    }
}
