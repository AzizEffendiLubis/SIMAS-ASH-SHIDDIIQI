<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan username (NIS/NIP)
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()
                ->withErrors(['username' => 'Username tidak ditemukan.'])
                ->withInput();
        }

        // Akun nonaktif tidak boleh login — status enum: aktif|nonaktif (migration users)
        if ($user->status === 'nonaktif') {
            return back()
                ->withErrors(['username' => 'Akun Anda tidak aktif. Hubungi administrator.'])
                ->withInput();
        }

        // Verifikasi password
        // Password di-hash otomatis via cast 'hashed' di User model
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah.'])
                ->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // must_change_password adalah boolean field di tabel users, default true
        if ($user->must_change_password) {
            return redirect()->route('password.change')
                ->with('info', 'Anda wajib mengganti password sebelum melanjutkan.');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Tampilkan form ganti password pertama kali.
     */
    public function showChangePassword()
    {
        // must_change_password accessor boolean di User model
        if (!Auth::user()->must_change_password) {
            return redirect()->route('dashboard');
        }

        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Pastikan password baru berbeda dari password yang sedang aktif
        // Hash::check diperlukan karena password disimpan dalam bentuk hash
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password baru tidak boleh sama dengan password lama.',
            ]);
        }

        // cast 'hashed' di User model akan otomatis meng-hash nilai password
        $user->update([
            'password'             => $request->password,
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diubah. Selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // TIDAK ADA: showRegister(), register(), forgotPassword()
    //
    // Tidak ada registrasi mandiri.
    // Forgot password tidak tersedia — Admin Utama mereset password pengguna
    // melalui UserController::update() dengan mengisi field password baru.
    // Setelah direset, must_change_password otomatis kembali true sehingga
    // pengguna wajib ganti password di login berikutnya.
}