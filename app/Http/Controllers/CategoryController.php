<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Daftar kategori dengan jumlah produk masing-masing.
     */
    public function index(Request $request): View
    {
        // Query dasar: hitung jumlah produk per kategori (withCount) tanpa query tambahan.
        $categories = Category::query()
            ->withCount('products')
            ->when(filled($request->input('search')), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->input('search').'%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories'));
    }

    /**
     * Form tambah kategori.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Simpan kategori baru.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        // Data sudah tervalidasi lewat StoreCategoryRequest.
        $category = Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil ditambahkan.");
    }

    /**
     * Detail kategori beserta daftar produk di dalamnya.
     */
    public function show(Category $category): View
    {
        $category->loadCount('products');

        // Produk dalam kategori ini (hanya kolom yang dipakai tabel).
        $products = $category->products()
            ->select(['id', 'category_id', 'name', 'sku', 'stock', 'min_stock', 'sell_price'])
            ->orderBy('name')
            ->paginate(10);

        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Form edit kategori.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update kategori.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        // Data sudah tervalidasi lewat UpdateCategoryRequest.
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus kategori. Ditolak bila masih dipakai produk.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk.');
        }

        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }
}
