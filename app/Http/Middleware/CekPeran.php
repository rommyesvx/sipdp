<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekPeran
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil data pengguna yang sedang login
        $user = Auth::user();

        // 3. Loop melalui setiap peran yang diizinkan dari route
        foreach ($roles as $role) {
            // 4. Cek apakah kolom 'role' milik pengguna cocok
            if ($user->role === $role) {
                // Jika cocok, izinkan pengguna melanjutkan
                return $next($request);
            }
        }

        // 5. Jika tidak ada peran yang cocok, tolak aksesnya
        abort(403, 'AKSES DITOLAK');
    }
}
