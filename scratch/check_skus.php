<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Produk::take(10)->get() as $p) {
    echo "{$p->id} | {$p->kode_produk} | {$p->nama_produk} | {$p->jenis_kain} | {$p->warna} | {$p->ukuran_baju}\n";
}
