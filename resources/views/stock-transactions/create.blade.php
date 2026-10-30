@extends('layouts.app')

@section('title', 'Catat Transaksi Stok')
@section('crumb', 'Transaksi Stok / Tambah')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="crumb-nav" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>/</span>
        <a href="{{ route('stock-transactions.index') }}">Transaksi Stok</a>
        <span>/</span>
        <span class="current">Tambah</span>
    </nav>

    {{-- Header --}}
    <div class="page-head">
        <div>
            <h1>🔄 Catat Transaksi Stok</h1>
            <p>Catat barang masuk atau keluar, stok terbarui otomatis.</p>
        </div>

        <a href="{{ route('stock-transactions.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Error validasi ditampilkan global oleh layouts/app.blade.php --}}

    <form method="POST" action="{{ route('stock-transactions.store') }}">
        @csrf

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-clipboard-list"></i> 📋 Detail Transaksi</h3>
                <span class="muted">Kolom bertanda * wajib diisi</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    {{-- Tipe transaksi --}}
                    <div class="field span-2">
                        <label>Tipe Transaksi *</label>
                        <div class="radio-row">
                            <label class="radio-option">
                                <input type="radio" name="type" value="in" @checked(old('type', 'in') === 'in')>
                                <span>⬆️ Stok Masuk (Restock/Pembelian)</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="type" value="out" @checked(old('type') === 'out')>
                                <span>⬇️ Stok Keluar (Penjualan/Rusak)</span>
                            </label>
                        </div>
                        @error('type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Produk --}}
                    <div class="field span-2">
                        <label for="product_id">Produk *</label>
                        <select id="product_id" name="product_id" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" @selected((string) old('product_id', $selectedProductId) === (string) $p->id)>
                                    {{ $p->name }} ({{ $p->sku }}) — Stok: {{ $p->stock }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jumlah --}}
                    <div class="field">
                        <label for="quantity">Jumlah *</label>
                        <input id="quantity" type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                        @error('quantity')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="field">
                        <label for="transaction_date">Tanggal *</label>
                        <input id="transaction_date" type="date" name="transaction_date"
                               value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                        @error('transaction_date')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="field span-2">
                        <label for="note">Catatan (opsional)</label>
                        <textarea id="note" name="note" rows="3" placeholder="Restock dari supplier...">{{ old('note') }}</textarea>
                        @error('note')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol footer --}}
            <div class="panel-foot">
                <a href="{{ route('stock-transactions.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Transaksi</button>
            </div>
        </section>

        <div class="alert alert-warning">
            ℹ️ Setelah disimpan, <strong>stok produk akan otomatis diperbarui</strong>.
        </div>
    </form>
@endsection
