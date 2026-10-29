@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('content')
    <h2>Buat akun baru</h2>
    <p class="auth-sub">Akun baru otomatis mendapat role <strong>staff</strong>.</p>

    {{-- Form registrasi. Password wajib dikonfirmasi (field password_confirmation). --}}
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="name">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Nama Anda" required autofocus autocomplete="name">
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required autocomplete="email">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password">
            <span class="hint">Gunakan minimal 8 karakter.</span>
        </div>

        <div class="field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">
            <i class="ti ti-user-plus"></i>
            Daftar
        </button>
    </form>

    <p class="auth-switch">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </p>
@endsection
