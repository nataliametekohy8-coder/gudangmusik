@extends('layouts.app')

@section('title', 'Edit Supplier: ' . $supplier->name)
@section('crumb', 'Supplier / Edit')

@section('content')
    {{-- Header + Breadcrumb --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('suppliers.index') }}">Supplier</a>
                <span>/</span>
                <span class="current">Edit</span>
            </nav>
            <h1>✏️ Edit Supplier: {{ $supplier->name }}</h1>
            <p>Perbarui kontak atau alamat supplier ini.</p>
        </div>

        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- Form edit supplier --}}
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
        @csrf
        @method('PUT')

        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-truck-delivery"></i> Informasi Supplier</h3>
                <span class="muted">ID: {{ $supplier->id }}</span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="name">Nama Supplier *</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $supplier->name) }}"
                               maxlength="150" class="form-control" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="phone">Telepon *</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                               maxlength="30" class="form-control" required>
                        @error('phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email (opsional)</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $supplier->email) }}"
                               maxlength="150" class="form-control">
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field span-2">
                        <label for="address">Alamat (opsional)</label>
                        <textarea id="address" name="address" rows="3" maxlength="500" class="form-control">{{ old('address', $supplier->address) }}</textarea>
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol footer --}}
            <div class="panel-foot">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Update Supplier</button>
            </div>
        </section>
    </form>
@endsection
