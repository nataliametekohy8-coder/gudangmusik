@extends('layouts.app')

@section('title', 'Edit Produk: ' . $product->name)
@section('crumb', 'Produk / Edit')

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('products.index') }}">Produk</a>
                <span>/</span>
                <span class="current">Edit</span>
            </nav>
            <h1>✏️ Edit Produk: {{ $product->name }}</h1>
            <p>Perbarui data produk, stok, dan harga di bawah ini.</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Form edit produk --}}
    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-guitar-pick"></i> Informasi Produk</h3>
                <span class="muted">SKU: {{ $product->sku }}</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="name">Nama Produk</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="sku">SKU</label>
                        <input id="sku" type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="brand">Brand</label>
                        <input id="brand" type="text" name="brand" value="{{ old('brand', $product->brand) }}" required>
                        @error('brand')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) old('category_id', $product->category_id) === (string) $cat->id)>
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
                                <option value="{{ $supplier->id }}" @selected((string) old('supplier_id', $product->supplier_id) === (string) $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="stock">Stok Saat Ini</label>
                        <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="min_stock">Stok Minimum</label>
                        <input id="min_stock" type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" required>
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
                        <input id="buy_price" type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" min="0" required>
                        @error('buy_price')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="sell_price">Harga Jual (Rp)</label>
                        <input id="sell_price" type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" min="0" required>
                        @error('sell_price')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol footer --}}
            <div class="panel-foot">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update Produk</button>
            </div>
        </section>

    </form>
@endsection
