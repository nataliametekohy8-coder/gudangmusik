<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan ringkasan dashboard gudang.
     */
    public function index(): View
    {
        // Total seluruh produk yang terdaftar.
        $totalProducts = Product::query()->count();

        // Total unit barang di gudang (SUM stock).
        $totalStock = (int) Product::query()->sum('stock');

        // Nilai stok berdasarkan harga beli (SUM stock * buy_price).
        $stockValue = (float) Product::query()->sum(DB::raw('stock * buy_price'));

        // Produk dengan stok menipis (stock <= min_stock) beserta kategorinya.
        $lowStockProducts = Product::query()
            ->select(['id', 'category_id', 'name', 'sku', 'brand', 'stock', 'min_stock'])
            ->whereColumn('stock', '<=', 'min_stock')
            ->with(['category' => fn ($query) => $query->select(['id', 'name'])])
            ->orderBy('stock')
            ->orderBy('name')
            ->get();

        // 5 transaksi stok terbaru beserta produk dan user pencatatnya.
        $recentTransactions = StockTransaction::query()
            ->with([
                'product' => fn ($query) => $query->select(['id', 'name', 'sku']),
                'user' => fn ($query) => $query->select(['id', 'name', 'role']),
            ])
            ->latest('transaction_date')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'user' => auth()->user(),
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'stockValue' => $stockValue,
            'lowStockCount' => $lowStockProducts->count(),
            'lowStockProducts' => $lowStockProducts,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
