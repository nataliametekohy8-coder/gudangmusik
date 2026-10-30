@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('crumb', 'Transaksi Stok / Detail')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="crumb-nav" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>/</span>
        <a href="{{ route('stock-transactions.index') }}">Transaksi Stok</a>
        <span>/</span>
        <span class="current">Detail #{{ $stockTransaction->id }}</span>
    </nav>

    {{-- Header --}}
    <div class="page-head">
        <div>
            <h1>🔄 Detail Transaksi #{{ $stockTransaction->id }}</h1>
            <p>Rincian transaksi dan kondisi stok produk terkait.</p>
        </div>

        <a href="{{ route('stock-transactions.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="grid-2">
        {{-- Informasi transaksi --}}
        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-receipt"></i> Informasi Transaksi</h3>
                @if ($stockTransaction->type === 'in')
                    <span class="badge badge-success">⬆️ Masuk</span>
                @else
                    <span class="badge badge-danger">⬇️ Keluar</span>
                @endif
            </div>

            <div class="panel-body">
                <dl class="info-list">
                    <div class="info-row">
                        <dt>Tanggal</dt>
                        <dd>{{ \Illuminate\Support\Carbon::parse($stockTransaction->transaction_date)->format('d F Y') }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Tipe</dt>
                        <dd>{{ $stockTransaction->type === 'in' ? 'Stok Masuk' : 'Stok Keluar' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Jumlah</dt>
                        <dd>{{ $stockTransaction->type === 'in' ? '+' : '-' }}{{ $stockTransaction->quantity }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Dicatat oleh</dt>
                        <dd>{{ $stockTransaction->user->name ?? '-' }} ({{ $stockTransaction->user->role ?? '-' }})</dd>
                    </div>
                    <div class="info-row">
                        <dt>Catatan</dt>
                        <dd>{{ $stockTransaction->note ?: '-' }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Informasi produk --}}
        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-guitar-pick"></i> Informasi Produk</h3>
            </div>

            <div class="panel-body">
                <dl class="info-list">
                    <div class="info-row">
                        <dt>Nama</dt>
                        <dd>{{ $stockTransaction->product->name ?? '-' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>SKU</dt>
                        <dd>{{ $stockTransaction->product->sku ?? '-' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Stok saat ini</dt>
                        <dd>{{ $stockTransaction->product->stock ?? '-' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Stok minimum</dt>
                        <dd>{{ $stockTransaction->product->min_stock ?? '-' }}</dd>
                    </div>
                </dl>

                @if ($stockTransaction->product)
                    <div class="actions" style="margin-top: 16px;">
                        <a href="{{ route('products.show', $stockTransaction->product) }}" class="btn btn-info">
                            Lihat Detail Produk
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
