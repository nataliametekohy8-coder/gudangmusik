@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <h2>Selamat datang kembali</h2>
    <p class="auth-sub">Masuk untuk mengelola stok Gudang Alat Musik.</p>

    {{-- Form login. Semua form memakai @csrf dan setiap output memakai {{ }}. --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required autofocus autocomplete="email">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
        </div>

        <label class="checkbox" for="remember">
            <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="btn btn-primary btn-lg btn-block">
            <i class="ti ti-login"></i>
            Masuk
        </button>
    </form>

    <p class="auth-switch">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
    </p>
@endsection
