@extends('layouts.guest')

@section('content')
<div class="login-shell">
    <div class="login-split">
        <div class="login-illustration-side">
            <div class="login-logo">🎸 <span>GudangMusik</span></div>
            <div class="login-illustration">
                <div class="blob"></div>
                <div class="music-icon guitar">🎸</div>
                <div class="music-icon drum">🥁</div>
                <div class="music-icon keyboard">🎹</div>
                <div class="music-icon mic">🎤</div>
                <div class="music-icon note-1">🎵</div>
                <div class="music-icon note-2">🎶</div>
                <div class="music-icon character">👨‍🎤</div>
            </div>
            <div class="login-footer">© 2026 GudangMusik — Sistem Inventaris Alat Musik</div>
        </div>
        <div class="login-form-side">
            <div class="login-form-container">
                <h1>LOGIN</h1>
                <p class="login-subtitle">Masuk ke akun inventaris Anda</p>
                @if (session('status'))
                    <div class="login-alert login-alert-success">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="login-alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="login-alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-with-icon">
                            <span class="input-icon">👤</span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@musik.test" autocomplete="username">
                        </div>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <span class="input-icon">🔒</span>
                            <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
                        </div>
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group remember">
                        <label>
                            <input type="checkbox" name="remember" value="1" @checked(old('remember'))> Ingat saya
                        </label>
                    </div>
                    <button type="submit" class="login-btn">LOGIN</button>
                    <div class="login-bottom">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


