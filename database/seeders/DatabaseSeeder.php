<?php

namespace Database\Seeders;

use App\Models\MutasiStok;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\StokProduk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Mengisi database sistem informasi konveksi COTTON.id dengan data berbahasa Indonesia.
     */
    public function run(): void
    {
        // ==========================================
        // 1. DATA PENGGUNA (Admin, Operator Produksi, Staf Gudang)
        // ==========================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@cotton.id'],
            [
                'name' => 'Michel',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'telepon' => '081234567890',
            ]
        );

        $operator = User::firstOrCreate(
            ['email' => 'produksi@cotton.id'],
            [
                'name' => 'Budi Santoso (Petugas Produksi)',
                'password' => Hash::make('password'),
                'role' => 'operator_produksi',
                'telepon' => '081298765432',
            ]
        );

        $stafGudang = User::firstOrCreate(
            ['email' => 'gudang@cotton.id'],
            [
                'name' => 'Dewi Lestari (Staf Gudang & Logistik)',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'telepon' => '081377889900',
            ]
        );

        // ==========================================
        // 2. DATA PELANGGAN
        // ==========================================
        $pelanggan1 = Pelanggan::create([
            'kode_pelanggan' => 'PEL-001',
            'nama' => 'PT Sinergi Kreatif Nusantara',
            'alamat' => 'Gedung Wisma Niaga Lantai 4, Jalan Jenderal Sudirman No. 45, Jakarta Selatan',
            'nomor_telepon' => '081122334455',
            'email' => 'kontak@sinergikreatif.co.id',
        ]);

        $pelanggan2 = Pelanggan::create([
            'kode_pelanggan' => 'PEL-002',
            'nama' => 'BEM Fakultas Ilmu Komputer Universitas Merdeka',
            'alamat' => 'Gedung F Kampus Terpadu, Jalan Pendidikan No. 12, Kota Malang',
            'nomor_telepon' => '085611223344',
            'email' => 'bem.fik@merdeka.ac.id',
        ]);

        $pelanggan3 = Pelanggan::create([
            'kode_pelanggan' => 'PEL-003',
            'nama' => 'Komunitas Sepeda Gowes Sehat Surabaya',
            'alamat' => 'Perumahan Nirwana Asri Blok C-8, Rungkut, Kota Surabaya',
            'nomor_telepon' => '087799887766',
            'email' => 'gowessehat.sby@gmail.com',
        ]);

        $pelanggan4 = Pelanggan::create([
            'kode_pelanggan' => 'PEL-004',
            'nama' => 'SMA Negeri 1 Jakarta (Panitia Pentas Seni)',
            'alamat' => 'Jalan Budi Utomo No. 7, Pasar Baru, Jakarta Pusat',
            'nomor_telepon' => '081388776655',
            'email' => 'pensi.sman1jkt@gmail.com',
        ]);

        // ==========================================
        // 3. DATA PRODUK (Katalog Konveksi)
        // ==========================================
        $produk1 = Produk::create([
            'kode_produk' => 'PRD-001',
            'nama_produk' => 'Kaos Polos Katun Combed 30s Lengan Pendek',
            'jenis_kain' => 'Katun Combed 30s Reaktif',
            'warna' => 'Hitam Pekat',
            'ukuran_baju' => 'L',
            'harga_satuan' => 45000,
            'deskripsi' => 'Bahan 100% serat katun alami berkualitas tinggi, adem, berdaya serap keringat sangat baik, dan tekstur halus standar distro.',
        ]);

        $produk2 = Produk::create([
            'kode_produk' => 'PRD-002',
            'nama_produk' => 'Kaos Polos Katun Combed 30s Lengan Pendek',
            'jenis_kain' => 'Katun Combed 30s Reaktif',
            'warna' => 'Putih Bersih',
            'ukuran_baju' => 'M',
            'harga_satuan' => 45000,
            'deskripsi' => 'Bahan katun combed 30s putih cerah, benang rapat rapi, tidak menerawang, dan jahitan rantai pundak kokoh.',
        ]);

        $produk3 = Produk::create([
            'kode_produk' => 'PRD-003',
            'nama_produk' => 'Kaos Katun Combed 24s Lengan Pendek',
            'jenis_kain' => 'Katun Combed 24s',
            'warna' => 'Biru Dongker',
            'ukuran_baju' => 'XL',
            'harga_satuan' => 50000,
            'deskripsi' => 'Bahan katun combed 24s berkarakter lebih tebal, tidak mudah kusut, cocok untuk sablon plastisol maupun bordir.',
        ]);

        $produk4 = Produk::create([
            'kode_produk' => 'PRD-004',
            'nama_produk' => 'Jaket Hoodie Kupluk Katun Fleece',
            'jenis_kain' => 'Katun Fleece Tebal 280 gsm',
            'warna' => 'Hitam Pekat',
            'ukuran_baju' => 'L',
            'harga_satuan' => 125000,
            'deskripsi' => 'Jaket bertudung kepala (kupluk) berbahan katun fleece tebal dan lembut, menghangatkan tubuh dengan saku depan dan tali kur serut.',
        ]);

        $produk5 = Produk::create([
            'kode_produk' => 'PRD-005',
            'nama_produk' => 'Kaos Berkerah Polo Katun Pique',
            'jenis_kain' => 'Katun CVC Pique Hexagon',
            'warna' => 'Merah Marun',
            'ukuran_baju' => 'XL',
            'harga_satuan' => 75000,
            'deskripsi' => 'Pakaian formal kasual dengan kerah kokoh, kancing depan, dan rajutan corak pori-pori hexagon premium.',
        ]);

        $produk6 = Produk::create([
            'kode_produk' => 'PRD-006',
            'nama_produk' => 'Kemeja PDL Lapangan Katun Drill',
            'jenis_kain' => 'American Drill Katun Premium',
            'warna' => 'Hijau Army',
            'ukuran_baju' => 'L',
            'harga_satuan' => 95000,
            'deskripsi' => 'Kemeja dinas harian/lapangan tahan gesekan, memiliki ventilasi udara punggung, dua kantong dada, dan pengait lengan.',
        ]);

        // ==========================================
        // 4. DATA STOK PRODUK & BATAS ROP (Reorder Point)
        // ==========================================
        $stok1 = StokProduk::create([
            'produk_id' => $produk1->id,
            'nama_produk' => $produk1->nama_produk,
            'warna' => $produk1->warna,
            'ukuran' => $produk1->ukuran_baju,
            'sisa_stok' => 120,
            'batas_rop' => 30,
            'status_rop' => 'Aman',
        ]);

        $stok2 = StokProduk::create([
            'produk_id' => $produk2->id,
            'nama_produk' => $produk2->nama_produk,
            'warna' => $produk2->warna,
            'ukuran' => $produk2->ukuran_baju,
            'sisa_stok' => 15,
            'batas_rop' => 25,
            'status_rop' => 'Batas ROP Tercapai', // Peringatan Reorder Point
        ]);

        $stok3 = StokProduk::create([
            'produk_id' => $produk3->id,
            'nama_produk' => $produk3->nama_produk,
            'warna' => $produk3->warna,
            'ukuran' => $produk3->ukuran_baju,
            'sisa_stok' => 0,
            'batas_rop' => 20,
            'status_rop' => 'Habis', // Peringatan Stok Kosong
        ]);

        $stok4 = StokProduk::create([
            'produk_id' => $produk4->id,
            'nama_produk' => $produk4->nama_produk,
            'warna' => $produk4->warna,
            'ukuran' => $produk4->ukuran_baju,
            'sisa_stok' => 45,
            'batas_rop' => 15,
            'status_rop' => 'Aman',
        ]);

        $stok5 = StokProduk::create([
            'produk_id' => $produk5->id,
            'nama_produk' => $produk5->nama_produk,
            'warna' => $produk5->warna,
            'ukuran' => $produk5->ukuran_baju,
            'sisa_stok' => 8,
            'batas_rop' => 15,
            'status_rop' => 'Batas ROP Tercapai', // Peringatan Reorder Point
        ]);

        $stok6 = StokProduk::create([
            'produk_id' => $produk6->id,
            'nama_produk' => $produk6->nama_produk,
            'warna' => $produk6->warna,
            'ukuran' => $produk6->ukuran_baju,
            'sisa_stok' => 35,
            'batas_rop' => 10,
            'status_rop' => 'Aman',
        ]);

        // ==========================================
        // 5. DATA MUTASI STOK (Stok Masuk & Stok Keluar)
        // ==========================================
        MutasiStok::create([
            'stok_produk_id' => $stok1->id,
            'user_id' => $admin->id,
            'jenis_mutasi' => 'masuk',
            'jumlah_stok_masuk' => 150,
            'jumlah_stok_keluar' => 0,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => 150,
            'keterangan' => 'Penerimaan stok awal dari pabrik pemasok kain tekstil',
            'tanggal_mutasi' => now()->subDays(12),
        ]);

        MutasiStok::create([
            'stok_produk_id' => $stok1->id,
            'user_id' => $admin->id,
            'jenis_mutasi' => 'keluar',
            'jumlah_stok_masuk' => 0,
            'jumlah_stok_keluar' => 30,
            'saldo_sebelum' => 150,
            'saldo_sesudah' => 120,
            'keterangan' => 'Pengeluaran bahan untuk pengerjaan pesanan seragam instansi',
            'tanggal_mutasi' => now()->subDays(6),
        ]);

        MutasiStok::create([
            'stok_produk_id' => $stok2->id,
            'user_id' => $admin->id,
            'jenis_mutasi' => 'keluar',
            'jumlah_stok_masuk' => 0,
            'jumlah_stok_keluar' => 35,
            'saldo_sebelum' => 50,
            'saldo_sesudah' => 15,
            'keterangan' => 'Pengambilan stok untuk pemenuhan pesanan kaos acara (sisa stok mencapai batas ROP)',
            'tanggal_mutasi' => now()->subDays(3),
        ]);

        MutasiStok::create([
            'stok_produk_id' => $stok4->id,
            'user_id' => $stafGudang->id,
            'jenis_mutasi' => 'masuk',
            'jumlah_stok_masuk' => 50,
            'jumlah_stok_keluar' => 0,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => 50,
            'keterangan' => 'Penerimaan bahan kain katun fleece baru dari gudang bahan baku',
            'tanggal_mutasi' => now()->subDays(15),
        ]);

        // ==========================================
        // 6. DATA PEMESANAN (Dikelola oleh Admin)
        // ==========================================
        $order1 = Pemesanan::create([
            'nomor_pemesanan' => 'PES-202611-0001',
            'pelanggan_id' => $pelanggan1->id,
            'user_id' => $admin->id,
            'produk_id' => $produk1->id,
            'jenis_kain' => 'Katun Combed 30s Reaktif',
            'warna' => 'Hitam Pekat',
            'ukuran_baju' => 'L',
            'jumlah' => 100,
            'detail_desain' => 'Sablon Cat Plastisol Logo Perusahaan di bagian dada kiri (ukuran 8x8 cm) serta Desain Tipografi Inspiratif di punggung belakang (ukuran A3 Penuh Warna)',
            'file_desain' => 'desain/pes-0001-sinergi.png',
            'tanggal_pemesanan' => now()->subDays(7)->toDateString(),
            'tanggal_selesai_estimasi' => now()->addDays(7)->toDateString(),
            'harga_satuan' => 65000,
            'total_harga' => 6500000,
            'status_pemesanan' => 'dalam_produksi',
            'status_pembayaran' => 'dp',
            'catatan' => 'Prioritas pengerjaan untuk seragam acara temu tahunan kantor',
        ]);

        $order2 = Pemesanan::create([
            'nomor_pemesanan' => 'PES-202611-0002',
            'pelanggan_id' => $pelanggan2->id,
            'user_id' => $admin->id,
            'produk_id' => $produk4->id,
            'jenis_kain' => 'Katun Fleece Tebal 280 gsm',
            'warna' => 'Biru Dongker',
            'ukuran_baju' => 'XL',
            'jumlah' => 50,
            'detail_desain' => 'Bordir Mesin Komputer Lambang Fakultas di dada kanan serta Sablon Karet Rubber Nama Angkatan Mahasiswa di punggung belakang',
            'file_desain' => 'desain/pes-0002-bem.png',
            'tanggal_pemesanan' => now()->subDays(14)->toDateString(),
            'tanggal_selesai_estimasi' => now()->subDays(1)->toDateString(),
            'harga_satuan' => 150000,
            'total_harga' => 7500000,
            'status_pemesanan' => 'selesai',
            'status_pembayaran' => 'lunas',
            'catatan' => 'Pesanan jaket seragam angkatan mahasiswa baru',
        ]);

        $order3 = Pemesanan::create([
            'nomor_pemesanan' => 'PES-202611-0003',
            'pelanggan_id' => $pelanggan3->id,
            'user_id' => $admin->id,
            'produk_id' => $produk3->id,
            'jenis_kain' => 'Katun Combed 24s',
            'warna' => 'Hijau Lumut',
            'ukuran_baju' => 'M',
            'jumlah' => 30,
            'detail_desain' => 'Sablon Digital Transfer Film (DTF) Ilustrasi Sepeda Klasik di bagian tengah dada depan (ukuran A4)',
            'file_desain' => 'desain/pes-0003-gowes.png',
            'tanggal_pemesanan' => now()->subDays(1)->toDateString(),
            'tanggal_selesai_estimasi' => now()->addDays(10)->toDateString(),
            'harga_satuan' => 70000,
            'total_harga' => 2100000,
            'status_pemesanan' => 'menunggu_pembayaran',
            'status_pembayaran' => 'belum_bayar',
            'catatan' => 'Menunggu konfirmasi pembayaran uang muka (DP) dari bendahara komunitas',
        ]);

        $order4 = Pemesanan::create([
            'nomor_pemesanan' => 'PES-202611-0004',
            'pelanggan_id' => $pelanggan4->id,
            'user_id' => $admin->id,
            'produk_id' => $produk1->id,
            'jenis_kain' => 'Katun Combed 30s Reaktif',
            'warna' => 'Putih Bersih',
            'ukuran_baju' => 'S',
            'jumlah' => 80,
            'detail_desain' => 'Sablon Cat Discharge Ilustrasi Musik Pentas Seni pada bagian depan dada dan daftar sponsor di punggung belakang',
            'file_desain' => 'desain/pes-0004-pensi.png',
            'tanggal_pemesanan' => now()->subDays(3)->toDateString(),
            'tanggal_selesai_estimasi' => now()->addDays(12)->toDateString(),
            'harga_satuan' => 60000,
            'total_harga' => 4800000,
            'status_pemesanan' => 'antrian_produksi',
            'status_pembayaran' => 'dp',
            'catatan' => 'Kaos panitia pentas seni sekolah SMA 1, jadwal pakai tanggal 25',
        ]);

        // ==========================================
        // 7. DATA PEMBAYARAN (DP & Pelunasan)
        // ==========================================
        Pembayaran::create([
            'kode_pembayaran' => 'BYR-202611-0001',
            'pemesanan_id' => $order1->id,
            'user_id' => $admin->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 3250000, // DP 50%
            'tanggal_bayar' => now()->subDays(6),
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_pembayaran' => 'bukti_transfer/byr-0001.jpg',
            'status_konfirmasi' => 'Diterima',
            'catatan' => 'Uang muka (DP) 50% diterima melalui transfer Bank BCA rekening resmi COTTON.id',
        ]);

        Pembayaran::create([
            'kode_pembayaran' => 'BYR-202611-0002',
            'pemesanan_id' => $order2->id,
            'user_id' => $admin->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 3750000, // DP 50%
            'tanggal_bayar' => now()->subDays(13),
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_pembayaran' => 'bukti_transfer/byr-0002.jpg',
            'status_konfirmasi' => 'Diterima',
            'catatan' => 'Uang muka (DP) 50% untuk pesanan jaket mahasiswa BEM telah diverifikasi',
        ]);

        Pembayaran::create([
            'kode_pembayaran' => 'BYR-202611-0003',
            'pemesanan_id' => $order2->id,
            'user_id' => $admin->id,
            'jenis_pembayaran' => 'Pelunasan',
            'jumlah_bayar' => 3750000, // Pelunasan 50%
            'tanggal_bayar' => now()->subDays(1),
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_pembayaran' => 'bukti_transfer/byr-0003.jpg',
            'status_konfirmasi' => 'Diterima',
            'catatan' => 'Pelunasan 50% disetorkan penuh saat pesanan siap diambil',
        ]);

        Pembayaran::create([
            'kode_pembayaran' => 'BYR-202611-0004',
            'pemesanan_id' => $order4->id,
            'user_id' => $admin->id,
            'jenis_pembayaran' => 'DP',
            'jumlah_bayar' => 2400000, // DP 50%
            'tanggal_bayar' => now()->subDays(2),
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_pembayaran' => 'bukti_transfer/byr-0004.jpg',
            'status_konfirmasi' => 'Diterima',
            'catatan' => 'Uang muka (DP) 50% kaos panitia pentas seni sekolah telah diverifikasi',
        ]);

        // ==========================================
        // 8. DATA PRODUKSI (Monitoring 4 Tahapan: Pemotongan Kain, Sablon, Penjahitan, Steam)
        // ==========================================
        // Produksi 1: Sedang diproses pada tahap penjahitan (Progress 60%)
        Produksi::create([
            'kode_produksi' => 'PRD-202611-001',
            'pemesanan_id' => $order1->id,
            'user_id' => $operator->id,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => null,

            // Tahap 1: Pemotongan Kain
            'status_pemotongan_kain' => 'selesai',
            'tgl_pemotongan_kain' => now()->subDays(5),
            'catatan_pemotongan_kain' => 'Pemotongan pola baju ukuran L sebanyak 100 lembar kain selesai dengan rapi dan presisi tanpa cacat bahan',

            // Tahap 2: Sablon
            'status_sablon' => 'selesai',
            'tgl_sablon' => now()->subDays(3),
            'catatan_sablon' => 'Penyablonan cat plastisol 2 sisi selesai dipanaskan dengan mesin curing pengering, warna pekat dan tahan uji cuci',

            // Tahap 3: Penjahitan
            'status_penjahitan' => 'sedang_diproses',
            'tgl_penjahitan' => now()->subDays(1),
            'catatan_penjahitan' => 'Proses penjahitan obras badan, pemasangan kerah leher, dan jahit rantai pundak sedang berjalan 60 dari 100 potong pakaian',

            // Tahap 4: Steam (Penyetrikaan Uap & Pengemasan)
            'status_steam' => 'belum_mulai',
            'tgl_steam' => null,
            'catatan_steam' => 'Menunggu proses penjahitan selesai',

            'tahap_saat_ini' => 'penjahitan',
            'progress_persen' => 60,
            'status_produksi' => 'proses',
            'catatan_produksi' => 'Pengerjaan sesuai jadwal, perkiraan selesai jahit besok siang kemudian lanjut tahap penyetrikaan uap dan pengemasan',
        ]);

        // Produksi 2: Telah selesai 100% semua tahapan
        Produksi::create([
            'kode_produksi' => 'PRD-202611-002',
            'pemesanan_id' => $order2->id,
            'user_id' => $operator->id,
            'tanggal_mulai' => now()->subDays(12)->toDateString(),
            'tanggal_selesai' => now()->subDays(2)->toDateString(),

            // Tahap 1: Pemotongan Kain
            'status_pemotongan_kain' => 'selesai',
            'tgl_pemotongan_kain' => now()->subDays(12),
            'catatan_pemotongan_kain' => 'Bahan katun fleece biru dongker 50 potong pola dipotong sesuai ukuran',

            // Tahap 2: Sablon / Bordir
            'status_sablon' => 'selesai',
            'tgl_sablon' => now()->subDays(9),
            'catatan_sablon' => 'Bordir komputer emblem lambang fakultas selesai presisi dan rapat benang',

            // Tahap 3: Penjahitan
            'status_penjahitan' => 'selesai',
            'tgl_penjahitan' => now()->subDays(5),
            'catatan_penjahitan' => 'Penjahitan jaket hoodie, saku kantong depan, dan pemasangan tali serut telah selesai dengan kuat',

            // Tahap 4: Steam
            'status_steam' => 'selesai',
            'tgl_steam' => now()->subDays(2),
            'catatan_steam' => 'Penyetrikaan uap (steam), pembersihan sisa benang (QC), pelipatan rapi, dan pengemasan dalam plastik segel selesai',

            'tahap_saat_ini' => 'selesai',
            'progress_persen' => 100,
            'status_produksi' => 'selesai',
            'catatan_produksi' => 'Seluruh pakaian telah selesai 100% dan lolos pemeriksaan kendali mutu kualitas (Quality Control)',
        ]);
    }
}
