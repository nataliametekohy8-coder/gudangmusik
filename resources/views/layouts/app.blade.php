<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; Gudang Alat Musik</title>
    {{-- CSS lokal (tanpa library). Ikon memakai Tabler Icons lewat CDN. --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">
</head>
<body>
    {{-- Sidebar gelap (indigo). --}}
    <aside class="sidebar">
        <a class="sidebar-brand" href="{{ url('/dashboard') }}">
            <span class="brand-mark"><i class="ti ti-music"></i></span>
            <span class="brand-text">
                <strong>Gudang Alat Musik</strong>
                <small>Sistem Inventori</small>
            </span>
        </a>

        <nav class="sidebar-nav">
            <div class="nav-section">Utama</div>

            <a class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ url('/dashboard') }}">
                <i class="ti ti-layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <a class="nav-link" href="#">
                <i class="ti ti-guitar-pick"></i>
                <span>Produk</span>
            </a>

            <a class="nav-link" href="#">
                <i class="ti ti-arrows-exchange"></i>
                <span>Transaksi Stok</span>
            </a>

            {{-- Menu khusus admin: tidak tampil untuk role staff. --}}
            @if (auth()->user()->role === 'admin')
                <div class="nav-section">Master Data</div>

                <a class="nav-link" href="#">
                    <i class="ti ti-category"></i>
                    <span>Kategori</span>
                </a>

                <a class="nav-link" href="#">
                    <i class="ti ti-truck-delivery"></i>
                    <span>Supplier</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-foot">
            <div class="sidebar-user">
                <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="who">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ auth()->user()->role }}</small>
                </span>

                {{-- Logout wajib POST + @csrf. --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" aria-label="Logout">
                        <i class="ti ti-logout"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="app-shell">
        {{-- Topbar. --}}
        <header class="topbar">
            <div class="topbar-title">
                <span class="crumb">
                    <i class="ti ti-home"></i> Beranda / @yield('crumb', 'Dashboard')
                </span>
                <h2>@yield('title', 'Dashboard')</h2>
            </div>

            <div class="topbar-search">
                <i class="ti ti-search"></i>
                <input type="search" placeholder="Cari produk / SKU..." aria-label="Pencarian" readonly>
            </div>

            <div class="topbar-meta">
                <span class="topbar-date">
                    <i class="ti ti-calendar"></i>
                    {{ now()->translatedFormat('d M Y') }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="ti ti-power"></i>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="content">
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="ti ti-circle-check"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    <i class="ti ti-alert-circle"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="ti ti-alert-triangle"></i>
                    <div>
                        <strong>Periksa kembali isian Anda:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
