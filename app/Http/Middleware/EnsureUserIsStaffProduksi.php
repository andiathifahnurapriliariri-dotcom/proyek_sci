<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStaffProduksi
{
    /**
     * Memastikan hanya staf produksi atau administrator yang dapat mengakses rute lantai kerja workshop.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if ($user->isAdmin() || $user->isStaffProduksi()) {
            return $next($request);
        }

        abort(403, 'Akses ditolak: Anda tidak memiliki hak akses ke Portal Kerja Workshop COTTON.id.');
    }
}
