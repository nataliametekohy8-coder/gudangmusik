<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockTransactionRequest;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class StockTransactionController extends Controller
{
    /**
     * Daftar transaksi stok dengan filter tipe, produk, dan rentang tanggal.
     */
    public function index(Request $request): View
    {
        // Eager loading seperlunya (relasi + kolom minimal).
        $transactions = StockTransaction::query()
            ->with([
                'product' => fn ($q) => $q->select(['id', 'name', 'sku']),
                'user' => fn ($q) => $q->select(['id', 'name']),
            ])
            // Filter tipe: hanya 'in' atau 'out' yang diterima.
            ->when(in_array($request->input('type'), ['in', 'out'], true), function ($query) use ($request) {
                $query->where('type', $request->input('type'));
            })
            // Filter produk.
            ->when(filled($request->input('product_id')), function ($query) use ($request) {
                $query->where('product_id', $request->input('product_id'));
            })
            // Filter rentang tanggal.
            ->when(filled($request->input('from')), function ($query) use ($request) {
                $query->whereDate('transaction_date', '>=', $request->input('from'));
            })
            ->when(filled($request->input('to')), function ($query) use ($request) {
                $query->whereDate('transaction_date', '<=', $request->input('to'));
            })
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Daftar produk untuk dropdown filter.
        $products = Product::query()
            ->select(['id', 'name', 'sku'])
            ->orderBy('name')
            ->get();

        return view('stock-transactions.index', compact('transactions', 'products'));
    }

    /**
     * Form catat transaksi baru.
     */
    public function create(Request $request): View
    {
        // Butuh stok saat ini untuk ditampilkan di opsi produk.
        $products = Product::query()
            ->select(['id', 'name', 'sku', 'stock'])
            ->orderBy('name')
            ->get();

        // Pre-select produk bila datang dari halaman detail produk.
        $selectedProductId = $request->input('product_id');

        return view('stock-transactions.create', compact('products', 'selectedProductId'));
    }

    /**
     * Simpan transaksi + perbarui stok produk secara atomik.
     */
    public function store(StoreStockTransactionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            // Satu DB transaction: transaksi stok dan perubahan stok sukses/gagal bersama.
            DB::transaction(function () use ($data) {
                // Kunci baris produk agar tidak terjadi race condition.
                $product = Product::query()->lockForUpdate()->find($data['product_id']);

                if (! $product) {
                    throw new RuntimeException('Produk tidak ditemukan.');
                }

                // Untuk stok keluar, pastikan stok mencukupi.
                if ($data['type'] === 'out' && $product->stock < $data['quantity']) {
                    throw new RuntimeException("Stok tidak cukup. Stok tersedia: {$product->stock}.");
                }

                // Catat riwayat transaksi.
                StockTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => $data['type'],
                    'quantity' => $data['quantity'],
                    'note' => $data['note'] ?? null,
                    'transaction_date' => $data['transaction_date'],
                ]);

                // Perbarui stok produk sesuai tipe transaksi.
                if ($data['type'] === 'in') {
                    $product->increment('stock', $data['quantity']);
                } else {
                    $product->decrement('stock', $data['quantity']);
                }
            });
        } catch (RuntimeException $e) {
            // Gagal (mis. stok tidak cukup): transaksi di-rollback otomatis.
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        $typeLabel = $data['type'] === 'in' ? 'masuk' : 'keluar';

        return redirect()
            ->route('stock-transactions.index')
            ->with('success', "Transaksi stok {$typeLabel} berhasil dicatat & stok diperbarui.");
    }

    /**
     * Detail satu transaksi beserta produk dan pencatatnya.
     */
    public function show(StockTransaction $stockTransaction): View
    {
        // Load relasi dengan kolom minimal yang dipakai view.
        $stockTransaction->load([
            'product' => fn ($q) => $q->select(['id', 'name', 'sku', 'stock', 'min_stock', 'sell_price']),
            'user' => fn ($q) => $q->select(['id', 'name', 'role']),
        ]);

        return view('stock-transactions.show', compact('stockTransaction'));
    }
}
