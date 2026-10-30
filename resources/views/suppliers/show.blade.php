@extends('layouts.app')

@section('title', 'Supplier: ' . $supplier->name)
@section('crumb', 'Supplier / ' . $supplier->name)

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('suppliers.index') }}">Supplier</a>
                <span>/</span>
                <span class="current">{{ $supplier->name }}</span>
            </nav>
            <h1>🚚 {{ $supplier->name }}</h1>
            <p>Detail supplier dan produk yang dipasoknya.</p>
        </div>

        <div class="actions">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">✏️ Edit Supplier</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>

    {{-- Info supplier --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-info-circle"></i> Informasi Supplier</h3>
            <span class="badge {{ $supplier->products_count > 0 ? 'badge-success' : 'badge-warning' }}">
                {{ $supplier->products_count }} produk
            </span>
        </div>

        <div class="panel-body">
            <dl class="info-list">
                <div class="info-row">
                    <dt>Nama</dt>
                    <dd>{{ $supplier->name }}</dd>
                </div>
                <div class="info-row">
                    <dt>Telepon</dt>
                    <dd>{{ $supplier->phone ?? '-' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Email</dt>
                    <dd>{{ $supplier->email ?? '-' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Alamat</dt>
                    <dd>{{ $supplier->address ?? '-' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Jumlah Produk</dt>
                    <dd>{{ $supplier->products_count }} produk</dd>
                </div>
            </dl>
        </div>
    </section>

    {{-- Produk dari supplier ini --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-package"></i> 📦 Produk dari Supplier Ini</h3>
            <span class="muted">Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
        </div>

        <div class="table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>SKU</th>
                        <th class="num">Harga Jual</th>
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
                            <td colspan="4" style="text-align: center;">Belum ada produk dari supplier ini.</td>
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
