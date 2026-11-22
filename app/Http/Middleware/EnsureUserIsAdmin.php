<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Memastikan hanya pengguna dengan role admin yang dapat mengakses rute ini.
     * Pengguna dengan role lain dialihkan ke dashboard divisinya masing-masing.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== 'admin') {
            return match ($request->user()->role) {
                'operator_produksi', 'staff', 'pemotongan', 'pemotongan_kain', 'sablon', 'penjahitan', 'steam' => redirect()->route('staff.dashboard'),
                default => abort(403, 'Akses ditolak: Khusus Administrator COTTON.id'),
            };
        }

        return $next($request);
    }
}

