@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name)
@section('crumb', 'Kategori / ' . $category->name)

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('categories.index') }}">Kategori</a>
                <span>/</span>
                <span class="current">{{ $category->name }}</span>
            </nav>
            <h1>🗂️ {{ $category->name }}</h1>
            <p>Detail kategori dan daftar produk di dalamnya.</p>
        </div>

        <div class="actions">
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">✏️ Edit Kategori</a>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>

    {{-- Info card --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-info-circle"></i> Informasi Kategori</h3>
            <span class="badge {{ $category->products_count > 0 ? 'badge-success' : 'badge-warning' }}">
                {{ $category->products_count }} produk
            </span>
        </div>

        <div class="panel-body">
            <dl class="info-list">
                <div class="info-row">
                    <dt>Nama</dt>
                    <dd>{{ $category->name }}</dd>
                </div>
                <div class="info-row">
                    <dt>Deskripsi</dt>
                    <dd>{{ $category->description ?: '-' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Jumlah Produk</dt>
                    <dd>{{ $category->products_count }}</dd>
                </div>
            </dl>
        </div>
    </section>

    {{-- Tabel produk dalam kategori ini --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-guitar-pick"></i> Produk dalam Kategori Ini</h3>
            <span class="muted">Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
        </div>

        <div class="table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>SKU</th>
                        <th class="num">Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td class="num">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada produk dalam kategori ini.</td>
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
