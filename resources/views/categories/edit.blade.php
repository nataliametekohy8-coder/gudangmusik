@extends('layouts.app')

@section('title', 'Edit Kategori: ' . $category->name)
@section('crumb', 'Kategori / Edit')

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('categories.index') }}">Kategori</a>
                <span>/</span>
                <span class="current">Edit</span>
            </nav>
            <h1>✏️ Edit Kategori: {{ $category->name }}</h1>
            <p>Perbarui nama atau deskripsi kategori ini.</p>
        </div>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Form edit kategori --}}
    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-category"></i> Informasi Kategori</h3>
                <span class="muted">ID: {{ $category->id }}</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="name">Nama Kategori</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" maxlength="100" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field span-2">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" maxlength="500"
                                  placeholder="Keterangan singkat kategori (opsional)">{{ old('description', $category->description) }}</textarea>
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
                <button type="submit" class="btn btn-primary">Update Kategori</button>
            </div>
        </section>
    </form>
@endsection
