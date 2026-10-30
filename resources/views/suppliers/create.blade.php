@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('crumb', 'Supplier / Tambah')

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('suppliers.index') }}">Supplier</a>
                <span>/</span>
                <span class="current">Tambah</span>
            </nav>
            <h1>🚚 Tambah Supplier Baru</h1>
            <p>Lengkapi data pemasok alat musik.</p>
        </div>

        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Form tambah supplier --}}
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-truck-delivery"></i> Informasi Supplier</h3>
                <span class="muted">Kolom bertanda * wajib diisi</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="name">Nama Supplier *</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               maxlength="150" placeholder="Contoh: PT Nada Sejahtera" class="form-control" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="phone">Telepon *</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                               maxlength="30" placeholder="021-555-1234" class="form-control" required>
                        @error('phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email (opsional)</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               maxlength="150" placeholder="supplier@contoh.com" class="form-control">
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field span-2">
                        <label for="address">Alamat (opsional)</label>
                        <textarea id="address" name="address" rows="3" maxlength="500"
                                  placeholder="Alamat lengkap supplier" class="form-control">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol footer --}}
            <div class="panel-foot">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Supplier</button>
            </div>
        </section>
    </form>
@endsection
