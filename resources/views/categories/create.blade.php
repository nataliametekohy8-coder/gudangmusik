@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('crumb', 'Kategori / Tambah')

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('categories.index') }}">Kategori</a>
                <span>/</span>
                <span class="current">Tambah</span>
            </nav>
            <h1>🗂️ Tambah Kategori Baru</h1>
            <p>Buat kelompok kategori untuk merapikan daftar produk.</p>
        </div>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Form tambah kategori --}}
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-category"></i> Informasi Kategori</h3>
                <span class="muted">Nama wajib diisi, deskripsi opsional</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="name">Nama Kategori</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" maxlength="100"
                               placeholder="Contoh: Gitar &amp; Ukulele" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field span-2">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" maxlength="500"
                                  placeholder="Keterangan singkat kategori (opsional)">{{ old('description') }}</textarea>
                        <span class="hint">Maksimal 500 karakter.</span>
                        @error('description')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol footer --}}
            <div class="panel-foot">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </div>
        </section>
    </form>
@endsection
