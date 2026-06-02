<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next, string $menu): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Akun dinonaktifkan saat sedang login — paksa logout
        if ($user->status === 'nonaktif') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['username' => 'Akun Anda telah dinonaktifkan.']);
        }

        // Wajib ganti password dulu sebelum bisa akses menu apapun
        // Dokumen: "Pengguna diwajibkan mengganti password saat pertama kali login."
        // Juga berlaku setelah Admin Utama mereset password pengguna.
        if ($user->must_change_password) {
            return redirect()->route('password.change')
                ->with('info', 'Anda wajib mengganti password sebelum melanjutkan.');
        }

        if (!$user->canAccess($menu)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}