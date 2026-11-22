<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$adminUser = \App\Models\User::where('role', 'admin')->first()
    ?? \App\Models\User::first();

if ($adminUser) {
    auth()->login($adminUser);
    echo "[OK] Authenticated as Admin: {$adminUser->name} ({$adminUser->username})\n";
} else {
    echo "[FAIL] Admin user not found!\n";
    exit(1);
}

// 1. Inisialisasi komponen Livewire ManajemenPesanan
$component = \Livewire\Livewire::test(\App\Livewire\Admin\ManajemenPesanan::class);
echo "[OK] ManajemenPesanan component mounted.\n";

// 2. Verifikasi Data Awal (Contoh Skenario Bagian 5)
// Default variasi: 4 baris -> Total: 40 pcs, Total Harga: Rp2.300.000
$variasiAwal = $component->get('variasi');
echo "Jumlah baris variasi default: " . count($variasiAwal) . "\n";
$totalPcsAwal = $component->get('totalPakaianPcs');
$totalHargaAwal = $component->get('totalHargaPesanan');
$totalJenisAwal = $component->get('totalJenisProduk');
$totalVariasiAwal = $component->get('totalVariasi');

echo "Total Pcs: {$totalPcsAwal} (Expected: 40)\n";
echo "Total Harga: Rp" . number_format($totalHargaAwal, 0, ',', '.') . " (Expected: Rp2.300.000)\n";
echo "Total Jenis: {$totalJenisAwal} (Expected: 2)\n";
echo "Total Variasi: {$totalVariasiAwal} (Expected: 4)\n";

assert($totalPcsAwal === 40, "Total Pcs harus 40");
assert($totalHargaAwal === 2300000, "Total Harga harus 2.300.000");

// 3. Verifikasi Aturan Surcharge Ukuran Besar (XXL, XXXL, 4XL, 5XL)
$instance = $component->instance();
$hargaM = $instance->hitungHargaSatuan('Kaos 30s', 'Dewasa', 'M');
$hargaXXL = $instance->hitungHargaSatuan('Kaos 30s', 'Dewasa', 'XXL');
$hargaXXXL = $instance->hitungHargaSatuan('Kaos 30s', 'Dewasa', 'XXXL');
$harga4XL = $instance->hitungHargaSatuan('Kaos 30s', 'Dewasa', '4XL');
$harga5XL = $instance->hitungHargaSatuan('Kaos 30s', 'Dewasa', '5XL');
$hargaAnak = $instance->hitungHargaSatuan('Kaos 30s', 'Anak-anak', '10');

echo "Harga Kaos 30s Dewasa M: Rp" . number_format($hargaM, 0, ',', '.') . " (Expected: Rp60.000)\n";
echo "Harga Kaos 30s Dewasa XXL (+5k): Rp" . number_format($hargaXXL, 0, ',', '.') . " (Expected: Rp65.000)\n";
echo "Harga Kaos 30s Dewasa XXXL (+10k): Rp" . number_format($hargaXXXL, 0, ',', '.') . " (Expected: Rp70.000)\n";
echo "Harga Kaos 30s Dewasa 4XL (+15k): Rp" . number_format($harga4XL, 0, ',', '.') . " (Expected: Rp75.000)\n";
echo "Harga Kaos 30s Dewasa 5XL (+20k): Rp" . number_format($harga5XL, 0, ',', '.') . " (Expected: Rp80.000)\n";
echo "Harga Kaos 30s Anak-anak 10: Rp" . number_format($hargaAnak, 0, ',', '.') . " (Expected: Rp50.000)\n";

assert($hargaM === 60000);
assert($hargaXXL === 65000);
assert($hargaXXXL === 70000);
assert($harga4XL === 75000);
assert($harga5XL === 80000);
assert($hargaAnak === 50000);
echo "[OK] Seluruh aturan harga dasar & surcharge ukuran terbukti benar!\n";

// 4. Uji Isolasi Ukuran Kategori Anak-anak vs Dewasa
$opsiAnak = $instance->getUkuranOptionsByKategori('Anak-anak');
$opsiDewasa = $instance->getUkuranOptionsByKategori('Dewasa');
echo "Opsi Anak: " . implode(', ', $opsiAnak) . "\n";
echo "Opsi Dewasa: " . implode(', ', $opsiDewasa) . "\n";
assert(!in_array('M', $opsiAnak), "Ukuran dewasa M tidak boleh ada di anak-anak");
assert(!in_array('10', $opsiDewasa), "Ukuran anak 10 tidak boleh ada di dewasa");
echo "[OK] Isolasi ukuran Anak-anak dan Dewasa terbukti ketat!\n";

// 5. Uji Tambah Variasi & Ubah Kuantitas
$component->call('tambahVariasi');
$variasiSetelahTambah = $component->get('variasi');
echo "Baris setelah Tambah Variasi: " . count($variasiSetelahTambah) . "\n";
assert(count($variasiSetelahTambah) === 5);

// Ubah baris ke-5 menjadi XXL dengan jumlah 5 pcs
$component->set('variasi.4.produk', 'Hoodie');
$component->set('variasi.4.kategori', 'Dewasa');
$component->set('variasi.4.warna', 'Merah Marun');
$component->set('variasi.4.ukuran', 'XXL');
$component->set('variasi.4.jumlah', 5);

$hargaBaris5 = $component->get('variasi.4.harga_satuan');
$subtotalBaris5 = $component->get('variasi.4.subtotal');
echo "Baris 5 (Hoodie Dewasa Merah Marun XXL 5 pcs): Harga Satuan = Rp{$hargaBaris5}, Subtotal = Rp{$subtotalBaris5}\n";
assert($hargaBaris5 === 65000);
assert($subtotalBaris5 === 325000);

// 6. Uji Hapus Variasi
$component->call('hapusVariasi', 4);
assert(count($component->get('variasi')) === 4);
echo "[OK] Hapus variasi berhasil.\n";

// 7. Uji Deteksi Duplikasi & Penggabungan Jumlah
// Set baris 1 sama persis dengan baris 0
$component->set('variasi.1.produk', 'Kaos 30s');
$component->set('variasi.1.kategori', 'Dewasa');
$component->set('variasi.1.warna', 'Hitam Pekat');
$component->set('variasi.1.ukuran', 'M');
$component->set('variasi.1.jumlah', 15);

$pesanError = $component->get('pesanErrorVariasi');
echo "Pesan error duplikat terdeteksi: '{$pesanError}'\n";
assert(!empty($pesanError), "Duplikasi harus terdeteksi");

// Panggil fungsi gabungkan duplikat
$component->call('gabungkanVariasiDuplikat');
$variasiSetelahMerge = $component->get('variasi');
$pesanErrorSetelahMerge = $component->get('pesanErrorVariasi');
echo "Jumlah baris setelah merge: " . count($variasiSetelahMerge) . "\n";
echo "Jumlah baris 0 setelah merge: " . $variasiSetelahMerge[0]['jumlah'] . " (Expected: 25 pcs)\n";
assert($variasiSetelahMerge[0]['jumlah'] === 25, "Jumlah harus digabung menjadi 10 + 15 = 25");
assert(empty($pesanErrorSetelahMerge), "Pesan error harus hilang setelah merge");
echo "[OK] Deteksi duplikasi dan tombol gabungkan jumlah berhasil!\n";

// 8. Uji Simpan ke Database Lengkap dengan Snapshot Harga
$component->set('nama_pelanggan', 'PT Test Variasi Solusindo');
$component->set('nomor_telepon', '081298765432');
$component->set('alamat', 'Jl. Uji Variasi No. 99, Kota Bandung');
$component->set('tanggal_deadline', now()->addDays(7)->toDateString());
$component->set('status_pembayaran_awal', 'dp');

$component->call('simpanPesanan');
$pesanSukses = $component->get('pesanSukses');
echo "Pesan sukses simpan: '{$pesanSukses}'\n";

$savedPesanan = \App\Models\Pemesanan::where('pelanggan_id', function ($q) {
    $q->select('id')->from('pelanggans')->where('nama', 'PT Test Variasi Solusindo');
})->latest()->first();

assert($savedPesanan !== null, "Pesanan harus tersimpan di database");
echo "[OK] Pesanan tersimpan dengan No SPK: {$savedPesanan->nomor_pemesanan}, Total Harga: Rp" . number_format($savedPesanan->total_harga, 0, ',', '.') . "\n";

$savedVariasi = \App\Models\PemesananVariasi::where('pemesanan_id', $savedPesanan->id)->get();
echo "Jumlah baris variasi di DB: " . $savedVariasi->count() . "\n";
assert($savedVariasi->count() > 0, "Variasi harus tersimpan di DB");

foreach ($savedVariasi as $idx => $sv) {
    echo "  - Variasi #{$idx}: {$sv->nama_produk} | {$sv->kategori_ukuran} | {$sv->warna} | {$sv->ukuran} | {$sv->jumlah} pcs | Rp" . number_format($sv->harga_satuan, 0, ',', '.') . " | Subtotal: Rp" . number_format($sv->subtotal, 0, ',', '.') . "\n";
    assert($sv->harga_satuan > 0, "Harga satuan snapshot harus tersimpan");
    assert($sv->subtotal > 0, "Subtotal snapshot harus tersimpan");
}

// 9. Uji Tampilan Detail Modal SPK Admin
$component->call('bukaModalDetail', $savedPesanan->id);
$selectedPesanan = $component->get('selectedPesanan');
assert($selectedPesanan !== null && $selectedPesanan->id === $savedPesanan->id);
echo "[OK] Modal Detail SPK memuat data pesanan terpilih dan variasi terhubung.\n";

// Cleanup test record
\App\Models\PemesananVariasi::where('pemesanan_id', $savedPesanan->id)->delete();
\App\Models\Pembayaran::where('pemesanan_id', $savedPesanan->id)->delete();
$pelangganId = $savedPesanan->pelanggan_id;
$savedPesanan->delete();
\App\Models\Pelanggan::where('id', $pelangganId)->delete();
echo "[OK] Cleanup data pengujian selesai.\n";

echo "\n>>> SELURUH SKENARIO PENGUJIAN 100% SUKSES DAN TERVERIFIKASI! <<<\n";
