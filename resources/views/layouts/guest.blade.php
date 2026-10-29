<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') &middot; Gudang Alat Musik</title>
    {{-- CSS lokal (tanpa library). Ikon memakai Tabler Icons lewat CDN. --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">
</head>
<body>
    <div class="auth-page">
        {{-- Panel kiri: brand. Disembunyikan di layar kecil oleh CSS. --}}
        <aside class="auth-aside">
            <a class="auth-brand" href="{{ url('/') }}">
                <span class="brand-mark"><i class="ti ti-music"></i></span>
                <span class="brand-text">
                    <strong>Gudang Alat Musik</strong>
                    <small>Sistem Inventori</small>
                </span>
            </a>

            <div class="auth-headline">
                <h1>Kelola stok alat musik dari satu tempat.</h1>
                <p>Pantau jumlah stok, nilai persediaan, dan riwayat barang masuk &amp; keluar secara rapi.</p>

                <ul class="auth-features">
                    <li><i class="ti ti-chart-pie"></i> Statistik stok dan nilai inventori real-time</li>
                    <li><i class="ti ti-bell-ringing"></i> Peringatan otomatis stok menipis</li>
                    <li><i class="ti ti-history"></i> Riwayat transaksi stok yang tercatat</li>
                </ul>
            </div>

            <div class="auth-aside-foot">
                &copy; {{ date('Y') }} Gudang Alat Musik
            </div>
        </aside>

        {{-- Panel kanan: form login / register. --}}
        <main class="auth-main">
            <div class="auth-card">
                <div class="auth-mobile-brand">
                    <span class="brand-mark"><i class="ti ti-music"></i></span>
                    <strong>Gudang Alat Musik</strong>
                </div>

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
            </div>
        </main>
    </div>
</body>
</html>
