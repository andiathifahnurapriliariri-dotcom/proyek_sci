<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Redirect pengguna setelah login berhasil berdasarkan role masing-masing.
     */
    public function toResponse($request): RedirectResponse
    {
        $role = $request->user()->role;

        return match ($role) {
            'admin'                                                                        => redirect()->route('admin.dashboard'),
            'operator_produksi', 'staff', 'pemotongan', 'pemotongan_kain', 'sablon', 'penjahitan', 'steam' => redirect()->route('staff.dashboard'),
            default                                                                        => redirect()->route('admin.dashboard'),
        };
    }
}

