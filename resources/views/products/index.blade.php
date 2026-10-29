@extends('layouts.app')

@section('title', 'Manajemen Produk')
@section('crumb', 'Produk')

@section('content')
    {{-- Header + Breadcrumb + Tombol Tambah --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <span class="current">Produk</span>
            </nav>
            <h1>📦 Manajemen Produk</h1>
            <p>Kelola stok alat musik Anda</p>
        </div>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Produk
        </a>
    </div>

    {{-- Flash Messages --}}
    {{-- Layout app sudah menampilkan session('success') dan session('error') secara global,
         jadi blok ini sengaja tidak diduplikasi di sini agar tidak muncul dua kali. --}}

    {{-- Filter --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-filter"></i> Filter Produk</h3>
            <span class="muted">{{ $products->total() }} produk ditemukan</span>
        </div>

        <div class="panel-body">
            <form method="GET" action="{{ route('products.index') }}" class="form-grid filter-grid">
                <div class="field">
                    <label for="filter-category">Kategori</label>
                    <select id="filter-category" name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="filter-status">Status Stok</label>
                    <select id="filter-status" name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="safe" @selected(request('status') === 'safe')>✅ Aman</option>
                        <option value="low" @selected(request('status') === 'low')>⚠️ Menipis</option>
                        <option value="empty" @selected(request('status') === 'empty')>🔴 Habis</option>
                    </select>
                </div>

                <div class="field">
                    <label for="filter-search">Pencarian</label>
                    <input id="filter-search" type="search" name="search" class="form-control"
                           placeholder="🔍 Cari nama, SKU, atau brand..." value="{{ request('search') }}">
                </div>

                <div class="field filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search"></i> Filter
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </section>

    {{-- Tabel --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-guitar-pick"></i> Daftar Produk</h3>
            <span class="muted">Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
        </div>

        <div class="table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 56px;">No</th>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th>Brand</th>
                        <th class="num">Stok</th>
                        <th class="num">Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="muted">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td class="cell-category">{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->brand }}</td>
                            <td class="num">
                                @if ($product->stock == 0)
                                    <span class="badge badge-danger">Habis</span>
                                @elseif ($product->stock <= $product->min_stock)
                                    <span class="badge badge-warning">{{ $product->stock }} ⚠️</span>
                                @else
                                    <span class="badge badge-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td class="num">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">👁 Detail</a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">✏️ Edit</a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Yakin hapus produk {{ $product->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑 Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">
                                Belum ada produk.
                                <a href="{{ route('products.create') }}">Klik di sini untuk menambah produk pertama.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="panel-foot">
            <span class="muted">
                Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
            </span>

            {{ $products->links() }}
        </div>
    </section>
@endsection
