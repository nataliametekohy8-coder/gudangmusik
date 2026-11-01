@extends('layouts.app')

@section('title', 'Dashboard')
@section('crumb', 'Dashboard')

@section('content')
    {{-- Banner sambutan. --}}
    <section class="hero">
        <div>
            <h1>Halo, {{ $user->name }} 👋</h1>
            <p>Berikut ringkasan kondisi gudang alat musik Anda hari ini. Data dihitung langsung dari tabel produk dan transaksi stok.</p>
        </div>
        <span class="badge badge-indigo">
            <i class="ti ti-shield-check"></i>
            Role: {{ $user->role }}
        </span>
    </section>

    {{-- Kartu statistik (setiap kartu menuju halaman terkait). --}}
    <div class="stats-grid">
        <a class="stat-card tone-indigo" href="{{ route('products.index') }}">
            <div class="stat-top">
                <span class="stat-label">Total Produk</span>
                <span class="stat-icon"><i class="ti ti-guitar-pick"></i></span>
            </div>
            <div class="stat-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
            <div class="stat-foot">Jenis produk terdaftar di gudang</div>
        </a>

        <a class="stat-card tone-green" href="{{ route('products.index') }}">
            <div class="stat-top">
                <span class="stat-label">Total Unit Stok</span>
                <span class="stat-icon"><i class="ti ti-packages"></i></span>
            </div>
            <div class="stat-value">{{ number_format($totalStock, 0, ',', '.') }}</div>
            <div class="stat-foot">Akumulasi seluruh stok produk</div>
        </a>

        <a class="stat-card tone-indigo" href="{{ route('products.index') }}">
            <div class="stat-top">
                <span class="stat-label">Nilai Stok</span>
                <span class="stat-icon"><i class="ti ti-cash-banknote"></i></span>
            </div>
            <div class="stat-value currency">Rp {{ number_format($stockValue, 0, ',', '.') }}</div>
            <div class="stat-foot">Dihitung dari stok &times; harga beli</div>
        </a>

        <a class="stat-card tone-amber {{ $lowStockCount > 0 ? 'is-alert' : '' }}" href="{{ route('products.index', ['status' => 'low']) }}">
            <div class="stat-top">
                <span class="stat-label">Stok Menipis</span>
                <span class="stat-icon"><i class="ti ti-alert-triangle"></i></span>
            </div>
            <div class="stat-value">{{ number_format($lowStockCount, 0, ',', '.') }}</div>
            <div class="stat-foot">Produk dengan stok &le; stok minimum</div>
        </a>
    </div>

    <div class="grid-2">
        {{-- Produk yang perlu segera restock. --}}
        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-bell-ringing"></i> Produk Stok Menipis</h3>
                <a href="{{ route('products.index', ['status' => 'low']) }}" class="btn btn-sm btn-secondary">
                    Lihat semua
                </a>
            </div>

            <div class="panel-body flush">
                @if ($lowStockProducts->isEmpty())
                    <div class="empty-state">
                        <i class="ti ti-circle-check"></i>
                        <strong>Semua stok aman</strong>
                        <p>Tidak ada produk yang berada di bawah batas stok minimum.</p>
                    </div>
                @else
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th class="num">Stok</th>
                                    <th class="num">Min</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lowStockProducts as $product)
                                    <tr>
                                        <td>
                                            <span class="cell-product">
                                                <span class="strong">{{ $product->name }}</span>
                                                <small>{{ $product->sku }} &middot; {{ $product->brand }}</small>
                                            </span>
                                        </td>
                                        <td class="cell-category">{{ $product->category?->name ?? '-' }}</td>
                                        <td class="num strong">{{ number_format($product->stock, 0, ',', '.') }}</td>
                                        <td class="num muted">{{ number_format($product->min_stock, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($product->stock === 0)
                                                <span class="badge badge-solid-red">
                                                    <i class="ti ti-package-off"></i> Habis
                                                </span>
                                            @else
                                                <span class="badge badge-amber">
                                                    <i class="ti ti-arrow-down-right"></i> Menipis
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

        {{-- Ringkasan akun yang sedang login. --}}
        <section class="panel">
            <div class="panel-head">
                <h3><i class="ti ti-user-circle"></i> Akun Anda</h3>
            </div>

            <div class="panel-body">
                <dl class="info-list">
                    <div class="info-row">
                        <dt><i class="ti ti-user"></i> Nama</dt>
                        <dd>{{ $user->name }}</dd>
                    </div>
                    <div class="info-row">
                        <dt><i class="ti ti-mail"></i> Email</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div class="info-row">
                        <dt><i class="ti ti-shield-check"></i> Role</dt>
                        <dd>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-green' : 'badge-indigo' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </dd>
                    </div>
                    <div class="info-row">
                        <dt><i class="ti ti-calendar"></i> Tanggal</dt>
                        <dd>{{ now()->translatedFormat('d F Y') }}</dd>
                    </div>
                </dl>
            </div>
        </section>
    </div>

    {{-- Transaksi stok terbaru. --}}
    <section class="panel">
        <div class="panel-head">
            <h3><i class="ti ti-history"></i> 5 Transaksi Stok Terbaru</h3>
            <div class="actions">
                <a href="{{ route('stock-transactions.create') }}" class="btn btn-sm btn-primary">
                    <i class="ti ti-plus"></i> Catat
                </a>
                <a href="{{ route('stock-transactions.index') }}" class="btn btn-sm btn-secondary">Lihat semua</a>
            </div>
        </div>

        <div class="panel-body flush">
            @if ($recentTransactions->isEmpty())
                <div class="empty-state">
                    <i class="ti ti-arrow-back-up"></i>
                    <strong>Belum ada transaksi</strong>
                    <p>Transaksi barang masuk dan keluar akan muncul di sini.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Jenis</th>
                                <th class="num">Jumlah</th>
                                <th>Dicatat Oleh</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTransactions as $transaction)
                                <tr>
                                    <td class="strong">
                                        {{ $transaction->transaction_date ? \Illuminate\Support\Carbon::parse($transaction->transaction_date)->translatedFormat('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        <span class="cell-product">
                                            <span>{{ $transaction->product?->name ?? '-' }}</span>
                                            <small>{{ $transaction->product?->sku ?? '' }}</small>
                                        </span>
                                    </td>
                                    <td>
                                        @if ($transaction->type === 'in')
                                            <span class="badge badge-green">
                                                <i class="ti ti-arrow-down-left"></i> Masuk
                                            </span>
                                        @else
                                            <span class="badge badge-red">
                                                <i class="ti ti-arrow-up-right"></i> Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="num strong">{{ number_format($transaction->quantity, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="cell-product">
                                            <span>{{ $transaction->user?->name ?? '-' }}</span>
                                            <small>{{ $transaction->user?->role ?? '' }}</small>
                                        </span>
                                    </td>
                                    <td class="muted">{{ $transaction->note ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection
