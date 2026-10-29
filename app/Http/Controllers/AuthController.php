<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form registrasi.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Memvalidasi input lalu menyimpan user baru dengan role 'staff'.
     *
     * Registrasi publik tidak boleh menentukan role sendiri, jadi role
     * dipaksa 'staff'. Karena kolom 'role' tidak ada di atribut Fillable
     * model User, penyimpanan memakai User::forceCreate().
     */
    public function register(Request $request): RedirectResponse
    {
        // Validasi server-side: name wajib, email unik, password minimal 8 karakter + konfirmasi.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Simpan user baru; password di-hash dan role selalu 'staff'.
        $user = User::forceCreate([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff',
        ]);

        // Login otomatis setelah registrasi berhasil.
        Auth::login($user);

        // Regenerasi session untuk mencegah session fixation.
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil. Selamat datang!');
    }

    /**
     * Menampilkan halaman form login.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Memvalidasi kredensial dan membuat session login.
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi dasar sebelum mencocokkan kredensial.
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cek kredensial; "remember" opsional sesuai checkbox form.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        // Regenerasi session agar ID session baru dipakai setelah login.
        $request->session()->regenerate();

        // Kembali ke halaman tujuan sebelumnya, atau ke dashboard.
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Mengakhiri sesi login user.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Hapus status autentikasi.
        Auth::logout();

        // Hapus data session dan buat token CSRF baru.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}