@extends('layouts.app')

@section('title', 'Transaksi Stok')
@section('crumb', 'Transaksi Stok')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="crumb-nav" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>/</span>
        <span class="current">Transaksi Stok</span>
    </nav>

    {{-- Header --}}
    <div class="page-head">
        <div>
            <h1>🔄 Transaksi Stok</h1>
            <p>Riwayat transaksi masuk &amp; keluar</p>
        </div>

        <a href="{{ route('stock-transactions.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Catat Transaksi
        </a>
    </div>

    {{-- Flash: session('success') & session('error') sudah dirender global oleh layouts/app.blade.php --}}

    {{-- Filter --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-filter"></i> Filter Transaksi</h3>
            <span class="muted">{{ $transactions->total() }} transaksi</span>
        </div>

        <div class="panel-body">
            <form method="GET" action="{{ route('stock-transactions.index') }}" class="form-grid filter-grid">
                <div class="field">
                    <label for="type">Tipe</label>
                    <select id="type" name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="in" @selected(request('type') === 'in')>⬆️ Masuk</option>
                        <option value="out" @selected(request('type') === 'out')>⬇️ Keluar</option>
                    </select>
                </div>

                <div class="field">
                    <label for="product_id">Produk</label>
                    <select id="product_id" name="product_id" class="form-select">
                        <option value="">Semua Produk</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" @selected((string) request('product_id') === (string) $p->id)>
                                {{ $p->name }} ({{ $p->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="from">Dari Tanggal</label>
                    <input id="from" type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>

                <div class="field">
                    <label for="to">Sampai Tanggal</label>
                    <input id="to" type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>

                <div class="field filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search"></i> Filter
                    </button>
                    <a href="{{ route('stock-transactions.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </section>

    {{-- Tabel --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-arrows-exchange"></i> Riwayat Transaksi</h3>
            <span class="muted">Halaman {{ $transactions->currentPage() }} dari {{ $transactions->lastPage() }}</span>
        </div>

        <div class="table-wrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Tipe</th>
                        <th class="num">Qty</th>
                        <th>User</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $trx)
                        <tr>
                            <td class="strong">
                                {{ $trx->transaction_date ? \Illuminate\Support\Carbon::parse($trx->transaction_date)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                <span class="cell-product">
                                    <span class="strong">{{ $trx->product->name ?? '-' }}</span>
                                    <small>{{ $trx->product->sku ?? '-' }}</small>
                                </span>
                            </td>
                            <td>
                                @if ($trx->type === 'in')
                                    <span class="badge badge-success">⬆️ Masuk</span>
                                @else
                                    <span class="badge badge-danger">⬇️ Keluar</span>
                                @endif
                            </td>
                            <td class="num strong">{{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }}</td>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                            <td class="muted">{{ Str::limit($trx->note, 40) ?: '-' }}</td>
                            <td>
                                <a href="{{ route('stock-transactions.show', $trx) }}" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">
                                Belum ada transaksi. Klik "Catat Transaksi" untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="panel-foot">
            <span class="muted">
                Menampilkan {{ $transactions->firstItem() ?? 0 }}–{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi
            </span>

            {{ $transactions->links() }}
        </div>
    </section>
@endsection
