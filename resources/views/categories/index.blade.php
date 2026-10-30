@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('crumb', 'Kategori')

@section('content')
    {{-- Header + breadcrumb + tombol tambah --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <span class="current">Kategori</span>
            </nav>
            <h1>🗂️ Kategori Produk</h1>
            <p>Kelola kategori alat musik</p>
        </div>

        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Kategori
        </a>
    </div>

    {{-- Flash messages: sudah ditampilkan global oleh layouts/app.blade.php --}}

    {{-- Filter / search --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-search"></i> Pencarian</h3>
            <span class="muted">{{ $categories->total() }} kategori ditemukan</span>
        </div>

        <div class="panel-body">
            <form method="GET" action="{{ route('categories.index') }}" class="form-grid filter-grid">
                <div class="field">
                    <label for="search">Cari Kategori</label>
                    <input id="search" type="search" name="search" class="form-control"
                           placeholder="🔍 Cari nama kategori..." value="{{ request('search') }}">
                </div>

                <div class="field filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search"></i> Cari
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </section>

    {{-- Tabel kategori --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-category"></i> Daftar Kategori</h3>
            <span class="muted">Halaman {{ $categories->currentPage() }} dari {{ $categories->lastPage() }}</span>
        </div>

        <div class="table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 56px;">No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th class="num">Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr>
                            <td class="muted">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td><strong>{{ $cat->name }}</strong></td>
                            <td class="cell-category">{{ Str::limit($cat->description ?? '', 80) ?: '-' }}</td>
                            <td class="num">
                                @if ($cat->products_count > 0)
                                    <span class="badge badge-success">{{ $cat->products_count }} produk</span>
                                @else
                                    <span class="badge badge-warning">0 produk</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('categories.show', $cat) }}" class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Yakin hapus kategori {{ $cat->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">
                                Belum ada kategori. Klik 'Tambah Kategori' untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="panel-foot">
            <span class="muted">
                Menampilkan {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }} dari {{ $categories->total() }} kategori
            </span>

            {{ $categories->links() }}
        </div>
    </section>
@endsection
