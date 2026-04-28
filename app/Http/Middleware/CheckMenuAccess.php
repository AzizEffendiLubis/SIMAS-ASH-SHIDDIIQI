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

        if ($user->status === 'nonaktif') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['username' => 'Akun Anda telah dinonaktifkan.']);
        }

        if (!$user->canAccess($menu)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
