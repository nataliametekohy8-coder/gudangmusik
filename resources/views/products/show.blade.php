@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)
@section('crumb', 'Produk / ' . $product->sku)

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('products.index') }}">Produk</a>
                <span>/</span>
                <span class="current">{{ $product->sku }}</span>
            </nav>
            <h1>🔎 Detail Produk</h1>
            <p>Informasi lengkap produk beserta riwayat transaksi stoknya.</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Kartu detail produk --}}
    <section class="panel">
        <div class="panel-body">
            <div class="detail-grid">
                {{-- KIRI: gambar placeholder --}}
                <div class="detail-media">
                    <span>🎸</span>
                    <small>{{ $product->brand }}</small>
                </div>

                {{-- KANAN: informasi produk --}}
                <div class="detail-info">
                    <h1>{{ $product->name }}</h1>

                    <p class="detail-sku">
                        SKU: <code>{{ $product->sku }}</code>
                        <span class="badge badge-indigo">{{ $product->brand }}</span>
                    </p>

                    <dl class="info-list">
                        <div class="info-row">
                            <dt>Kategori</dt>
                            <dd>{{ $product->category->name ?? '-' }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Supplier</dt>
                            <dd>{{ $product->supplier->name ?? '-' }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Telepon supplier</dt>
                            <dd>{{ $product->supplier->phone ?? '-' }}</dd>
                        </div>
                    </dl>

                    {{-- Kotak stok --}}
                    <div class="detail-stats">
                        <div class="detail-stat">
                            <span class="stat-label">Stok Saat Ini</span>
                            @if ($product->stock == 0)
                                <span class="badge badge-danger">Habis (0)</span>
                            @elseif ($product->stock <= $product->min_stock)
                                <span class="badge badge-warning">{{ $product->stock }} ⚠️ Menipis</span>
                            @else
                                <span class="badge badge-success">{{ $product->stock }} ✅ Aman</span>
                            @endif
                        </div>

                        <div class="detail-stat">
                            <span class="stat-label">Stok Minimum</span>
                            <strong>{{ number_format($product->min_stock, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    {{-- Info harga --}}
                    <dl class="info-list">
                        <div class="info-row">
                            <dt>Harga Beli</dt>
                            <dd>Rp {{ number_format($product->buy_price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Harga Jual</dt>
                            <dd>Rp {{ number_format($product->sell_price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Margin</dt>
                            <dd>
                                Rp {{ number_format($product->sell_price - $product->buy_price, 0, ',', '.') }}
                                @if ($product->buy_price > 0)
                                    ({{ round((($product->sell_price - $product->buy_price) / $product->buy_price) * 100, 2) }}%)
                                @endif
                            </dd>
                        </div>
                    </dl>

                    {{-- Tombol aksi --}}
                    <div class="actions detail-actions">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">✏️ Edit</a>
                        <a href="{{ route('stock-transactions.create', ['product_id' => $product->id]) }}" class="btn btn-success">🔄 Catat Transaksi</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- Riwayat --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-history"></i> 📜 Riwayat Transaksi (10 terakhir)</h3>
            <span class="muted">{{ $transactions->count() }} transaksi ditampilkan</span>
        </div>

        <div class="table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th class="num">Qty</th>
                        <th>User</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $trx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') }}</td>
                            <td>
                                @if ($trx->type == 'in')
                                    <span class="badge badge-success">⬆️ Masuk</span>
                                @else
                                    <span class="badge badge-danger">⬇️ Keluar</span>
                                @endif
                            </td>
                            <td class="num">
                                @if ($trx->type == 'in')
                                    +{{ $trx->quantity }}
                                @else
                                    -{{ $trx->quantity }}
                                @endif
                            </td>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                            <td class="muted">{{ $trx->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Belum ada transaksi untuk produk ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="panel-foot">
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">← Kembali ke daftar produk</a>
        </div>
    </section>
@endsection

