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

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.'])->withInput();
        }
        if ($user->status === 'nonaktif') {
            return back()->withErrors(['username' => 'Akun Anda belum aktif. Hubungi administrator.'])->withInput();
        }
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        return redirect()->route('dashboard');
    }

    public function showRegister()
    {
        // Hanya kirim daftar unit — tidak ada pilihan role, role dikunci ke 'user'
        $units = ['TK', 'SD', 'SMP', 'SMA', 'MA', 'Pondok Pesantren'];
        return view('auth.register', compact('units'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'nullable|string|max:20',
            'jabatan'    => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'username'   => 'required|string|min:4|max:50|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
            'password'   => 'required|string|min:8|confirmed',
            'terms'      => 'accepted',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'username.regex'     => 'Username hanya boleh berisi huruf, angka, dan underscore.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted'     => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        // ── FIX MASALAH 2: Registrasi mandiri SELALU role 'user', nonaktif ──
        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'jabatan'     => $request->jabatan,
            'unit_kerja'  => $request->unit_kerja,
            'username'    => $request->username,
            'password'    => $request->password,
            'role'        => 'user',       // TERKUNCI — tidak bisa dipilih dari form
            'status'      => 'nonaktif',   // Harus diaktifkan oleh Super Admin
            'menu_access' => ['dashboard', 'perbaikan_aset'], // User biasa hanya bisa lapor kerusakan
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat! Tunggu aktivasi dari administrator sebelum dapat login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
}
