@extends('layouts.app')

@section('title', 'Supplier')
@section('crumb', 'Supplier')

@section('content')
    {{-- Header + breadcrumb + tombol tambah --}}
    <div class="page-head">
        <div>
            <nav class="crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <span>/</span>
                <span class="current">Supplier</span>
            </nav>
            <h1>🚚 Supplier</h1>
            <p>Kelola data pemasok alat musik</p>
        </div>

        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Tambah Supplier
        </a>
    </div>

    {{-- Flash messages: sudah ditampilkan global oleh layouts/app.blade.php --}}

    {{-- Filter / search --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-search"></i> Pencarian</h3>
            <span class="muted">{{ $suppliers->total() }} supplier ditemukan</span>
        </div>

        <div class="panel-body">
            <form method="GET" action="{{ route('suppliers.index') }}" class="form-grid filter-grid">
                <div class="field">
                    <label for="search">Cari Supplier</label>
                    <input id="search" type="text" name="search" class="form-control"
                           placeholder="🔍 Cari nama, telepon, atau email..." value="{{ request('search') }}">
                </div>

                <div class="field filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search"></i> Cari
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </section>

    {{-- Tabel supplier --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-truck-delivery"></i> Daftar Supplier</h3>
            <span class="muted">Halaman {{ $suppliers->currentPage() }} dari {{ $suppliers->lastPage() }}</span>
        </div>

        <div class="table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 56px;">No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th class="num">Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $sup)
                        <tr>
                            <td class="muted">{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                            <td><strong>{{ $sup->name }}</strong></td>
                            <td>{{ $sup->phone ?? '-' }}</td>
                            <td>{{ $sup->email ?? '-' }}</td>
                            <td class="num">
                                @if ($sup->products_count > 0)
                                    <span class="badge badge-success">{{ $sup->products_count }} produk</span>
                                @else
                                    <span class="badge badge-warning">0 produk</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('suppliers.show', $sup) }}" class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('suppliers.edit', $sup) }}" class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('suppliers.destroy', $sup) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Yakin hapus supplier {{ $sup->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Belum ada supplier. Klik 'Tambah Supplier' untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="panel-foot">
            <span class="muted">
                Menampilkan {{ $suppliers->firstItem() ?? 0 }}–{{ $suppliers->lastItem() ?? 0 }} dari {{ $suppliers->total() }} supplier
            </span>

            {{ $suppliers->links() }}
        </div>
    </section>
@endsection
