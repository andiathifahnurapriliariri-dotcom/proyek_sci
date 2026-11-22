<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menambahkan akun default untuk seluruh pengguna sistem COTTON.id.
     * Masing-masing akun memiliki role dan dashboard tersendiri.
     */
    public function run(): void
    {
        $pengguna = [
            [
                'username'  => 'admin',
                'name'      => 'Michel',
                'email'     => 'admin@cotton.id',
                'password'  => Hash::make('admin123'),
                'role'      => 'admin',
                'telepon'   => '081234567890',
            ],
            [
                'username'  => 'pemotongan',
                'name'      => 'Staf Divisi Pemotongan Kain',
                'email'     => 'pemotongan@cotton.id',
                'password'  => Hash::make('pemotongan123'),
                'role'      => 'pemotongan_kain',
                'telepon'   => '081200000001',
            ],
            [
                'username'  => 'sablon',
                'name'      => 'Staf Divisi Sablon & Bordir',
                'email'     => 'sablon@cotton.id',
                'password'  => Hash::make('sablon123'),
                'role'      => 'sablon',
                'telepon'   => '081200000002',
            ],
            [
                'username'  => 'penjahitan',
                'name'      => 'Staf Divisi Penjahitan',
                'email'     => 'penjahitan@cotton.id',
                'password'  => Hash::make('penjahitan123'),
                'role'      => 'penjahitan',
                'telepon'   => '081200000003',
            ],
            [
                'username'  => 'steam',
                'name'      => 'Staf Divisi Steam & Finishing',
                'email'     => 'steam@cotton.id',
                'password'  => Hash::make('steam123'),
                'role'      => 'steam',
                'telepon'   => '081200000004',
            ],
        ];

        foreach ($pengguna as $data) {
            User::updateOrCreate(
                ['username' => $data['username']],
                $data
            );
        }
    }
}
