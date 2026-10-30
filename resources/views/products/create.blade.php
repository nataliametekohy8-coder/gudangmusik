@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('crumb', 'Produk / Tambah')

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('products.index') }}">Produk</a>
                <span>/</span>
                <span class="current">Tambah</span>
            </nav>
            <h1>📦 Tambah Produk Baru</h1>
            <p>Lengkapi data produk alat musik yang akan disimpan ke gudang.</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Form tambah produk --}}
    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-guitar-pick"></i> Informasi Produk</h3>
                <span class="muted">Semua kolom wajib diisi</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="name">Nama Produk</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Gitar Akustik F310" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="sku">SKU</label>
                        <input id="sku" type="text" name="sku" value="{{ old('sku') }}" placeholder="Contoh: GTR-001" required>
                        @error('sku')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="brand">Brand</label>
                        <input id="brand" type="text" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Yamaha" required>
                        @error('brand')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) old('category_id') === (string) $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="supplier_id">Supplier</label>
                        <select id="supplier_id" name="supplier_id" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected((string) old('supplier_id') === (string) $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="stock">Stok Awal</label>
                        <input id="stock" type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                        @error('stock')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="min_stock">Stok Minimum</label>
                        <input id="min_stock" type="number" name="min_stock" value="{{ old('min_stock', 5) }}" min="0" required>
                        <span class="hint">Batas peringatan stok menipis.</span>
                        @error('min_stock')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-cash-banknote"></i> Harga</h3>
                <span class="muted">Isi angka tanpa titik atau koma</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="field">
                        <label for="buy_price">Harga Beli (Rp)</label>
                        <input id="buy_price" type="number" name="buy_price" value="{{ old('buy_price') }}" min="0" placeholder="0" required>
                        @error('buy_price')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="sell_price">Harga Jual (Rp)</label>
                        <input id="sell_price" type="number" name="sell_price" value="{{ old('sell_price') }}" min="0" placeholder="0" required>
                        @error('sell_price')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol footer --}}
            <div class="panel-foot">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
            </div>
        </section>

    </form>
@endsection
