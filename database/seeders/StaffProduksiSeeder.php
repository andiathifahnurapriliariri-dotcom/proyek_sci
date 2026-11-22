<?php

namespace Database\Seeders;

use App\Models\AktivitasLog;
use App\Models\NotifikasiStaff;
use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\ProduksiTahapan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffProduksiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Operator Produksi: Budi Santoso
        $budi = User::updateOrCreate(
            ['email' => 'produksi@cotton.id'],
            [
                'username' => 'staff',
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'operator_produksi',
                'telepon' => '081298765432',
                'lokasi_workshop' => 'Lantai Jahit & Workshop #01',
                'shift' => 'Shift Pagi (08:00 - 17:00 WIB)',
                'stasiun_kerja' => 'Stasiun Kerja Budi',
            ]
        );

        // 4 Role Departemen Produksi (Pemotongan, Penjahitan, Steam, Sablon)
        $departemenUsers = [
            [
                'username' => 'pemotongan',
                'name' => 'Riri',
                'email' => 'pemotongan@cotton.id',
                'password' => Hash::make('pemotongan123'),
                'role' => 'pemotongan',
                'telepon' => '081298765401',
                'lokasi_workshop' => 'Lantai Potong & Workshop #01',
                'shift' => 'Shift Pagi (08:00 - 17:00 WIB)',
                'stasiun_kerja' => 'Meja Potong Pola Kain #01',
            ],
            [
                'username' => 'penjahitan',
                'name' => 'Staf Penjahitan',
                'email' => 'penjahitan@cotton.id',
                'password' => Hash::make('penjahitan123'),
                'role' => 'penjahitan',
                'telepon' => '081298765402',
                'lokasi_workshop' => 'Lantai Jahit & Workshop #02',
                'shift' => 'Shift Pagi (08:00 - 17:00 WIB)',
                'stasiun_kerja' => 'Meja Mesin Jahit & Obras #02',
            ],
            [
                'username' => 'steam',
                'name' => 'Staf Steam & Finishing',
                'email' => 'steam@cotton.id',
                'password' => Hash::make('steam123'),
                'role' => 'steam',
                'telepon' => '081298765403',
                'lokasi_workshop' => 'Area Steam & Finishing #03',
                'shift' => 'Shift Pagi (08:00 - 17:00 WIB)',
                'stasiun_kerja' => 'Stasiun Setrika Uap & Press #03',
            ],
            [
                'username' => 'sablon',
                'name' => 'Staf Sablon',
                'email' => 'sablon@cotton.id',
                'password' => Hash::make('sablon123'),
                'role' => 'sablon',
                'telepon' => '081298765404',
                'lokasi_workshop' => 'Studio Sablon & Workshop #04',
                'shift' => 'Shift Pagi (08:00 - 17:00 WIB)',
                'stasiun_kerja' => 'Meja Sablon & Screen Presisi #04',
            ],
        ];

        foreach ($departemenUsers as $du) {
            User::updateOrCreate(
                ['username' => $du['username']],
                $du
            );
        }

        $admin = User::firstWhere('role', 'admin') ?? User::first();

        // 2. Data Pelanggan Sesuai Screenshot
        $pelangganSinergi = Pelanggan::updateOrCreate(
            ['kode_pelanggan' => 'PEL-001'],
            [
                'nama' => 'PT Sinergi Kreatif',
                'alamat' => 'Gedung Wisma Niaga Lt. 4, Jakarta Selatan',
                'nomor_telepon' => '0812-9988-1234',
                'email' => 'maya@sinergikreatif.co.id',
            ]
        );

        $pelangganBintang = Pelanggan::updateOrCreate(
            ['kode_pelanggan' => 'PEL-005'],
            [
                'nama' => 'CV Bintang Graha',
                'alamat' => 'Jl. Boulevard Raya No. 18, Bandung',
                'nomor_telepon' => '0813-1122-3344',
                'email' => 'kontak@bintanggraha.com',
            ]
        );

        $pelangganYouth = Pelanggan::updateOrCreate(
            ['kode_pelanggan' => 'PEL-006'],
            [
                'nama' => 'Komunitas Youth BDG',
                'alamat' => 'Jl. Riau No. 42, Bandung',
                'nomor_telepon' => '0857-8899-0011',
                'email' => 'youth.bdg@gmail.com',
            ]
        );

        $pelangganSelaras = Pelanggan::updateOrCreate(
            ['kode_pelanggan' => 'PEL-007'],
            [
                'nama' => 'PT Selaras Kreatif',
                'alamat' => 'Jl. Gatot Subroto Kav. 12, Jakarta',
                'nomor_telepon' => '0812-3344-5566',
                'email' => 'info@selaraskreatif.co.id',
            ]
        );

        $pelangganBem = Pelanggan::updateOrCreate(
            ['kode_pelanggan' => 'PEL-002'],
            [
                'nama' => 'BEM Fakultas Ilmu Komputer',
                'alamat' => 'Kampus Terpadu, Malang',
                'nomor_telepon' => '085611223344',
                'email' => 'bem@merdeka.ac.id',
            ]
        );

        $pelangganGowes = Pelanggan::updateOrCreate(
            ['kode_pelanggan' => 'PEL-003'],
            [
                'nama' => 'Komunitas Gowes Sehat',
                'alamat' => 'Perumahan Nirwana Asri, Surabaya',
                'nomor_telepon' => '087799887766',
                'email' => 'gowes@gmail.com',
            ]
        );

        // 3. PESANAN ORD-2026-001 (Detail Pekerjaan di Gambar 4)
        $order1 = Pemesanan::updateOrCreate(
            ['nomor_pemesanan' => 'ORD-2026-001'],
            [
                'pelanggan_id' => $pelangganSinergi->id,
                'user_id' => $admin?->id,
                'jenis_kain' => 'Kaos Polos Combed 30s',
                'warna' => 'Hitam Solid / Jet Black',
                'ukuran_baju' => 'S: 5, M: 10, L: 10, XL: 5 Pcs',
                'jumlah' => 30,
                'detail_desain' => 'Sablon Plastisol Doff Putih. Desain typography monokrom minimalis. Jarak 7 cm dari sambungan rib kerah.',
                'tanggal_pemesanan' => now()->subDay()->toDateString(),
                'tanggal_selesai_estimasi' => now()->toDateString(),
                'harga_satuan' => 55000,
                'total_harga' => 1650000,
                'status_pemesanan' => 'dalam_produksi',
                'status_pembayaran' => 'dp',
                'catatan' => 'Pola potongan sudah diplot di meja potong utama. Prioritas Produksi Reguler (Tepat Waktu).',
                'is_prioritas' => true,
                'kesiapan_bahan' => 'Pola potongan sudah diplot di meja potong utama',
                'lokasi_rak' => 'Meja Potong #01',
                'perlu_sablon' => true,
                'jenis_sablon' => 'Plastisol White Super',
                'kode_gambar' => 'DSN-TYPO-COTTON',
                'kategori_produk' => 'Apparel Merchandise Kantor',
                'pic_pelanggan' => 'Michel (0812-3456-7890)',
                'instruksi_kemasan' => 'Polybag Satuan (Per lusin berlabel ukuran)',
                'titik_sablon' => 2,
                'status_sablon_antrean' => 'tersimpan_aman',
            ]
        );

        $prod1 = Produksi::updateOrCreate(
            ['pemesanan_id' => $order1->id],
            [
                'kode_produksi' => 'PRD-2026-001',
                'user_id' => $budi->id,
                'tanggal_mulai' => now()->toDateString(),
                'status_pemotongan_kain' => 'sedang_diproses',
                'tgl_pemotongan_kain' => now()->setTime(8, 30),
                'catatan_pemotongan_kain' => 'Pola ukuran M dan L dipotong terlebih dahulu sesuai urutan antrean jahit. Operator: Budi Santoso.',
                'tahap_saat_ini' => 'pemotongan_kain',
                'progress_persen' => 40,
                'status_produksi' => 'proses',
                'catatan_produksi' => 'Sedang Dikerjakan (Tahap 1: Pemotongan Kain)',
            ]
        );

        ProduksiTahapan::where('produksi_id', $prod1->id)->delete();
        ProduksiTahapan::create([
            'produksi_id' => $prod1->id,
            'nama_tahap' => 'pemotongan_kain',
            'label_tahap' => 'Pemotongan Kain (Cutting)',
            'urutan' => 1,
            'is_required' => true,
            'status' => 'sedang_dikerjakan',
            'user_id' => $budi->id,
            'waktu_mulai' => now()->setTime(8, 30),
            'catatan_pengerjaan' => 'Gelar tumpukan kain telah selesai dikalibrasi presisi. Pola ukuran M dan L dipotong terlebih dahulu.',
        ]);
        ProduksiTahapan::create([
            'produksi_id' => $prod1->id,
            'nama_tahap' => 'penjahitan',
            'label_tahap' => 'Penjahitan (Sewing Line)',
            'urutan' => 2,
            'is_required' => true,
            'status' => 'menunggu',
        ]);
        ProduksiTahapan::create([
            'produksi_id' => $prod1->id,
            'nama_tahap' => 'steam',
            'label_tahap' => 'Steam (Steam & Perapian)',
            'urutan' => 3,
            'is_required' => true,
            'status' => 'menunggu',
        ]);
        ProduksiTahapan::create([
            'produksi_id' => $prod1->id,
            'nama_tahap' => 'packing',
            'label_tahap' => 'Sablon & Packing',
            'urutan' => 4,
            'is_required' => true,
            'status' => 'menunggu',
        ]);

        // 4. PESANAN ORD-2026-002 (Di Tabel Antrean Sablon Gambar 3)
        $order2 = Pemesanan::updateOrCreate(
            ['nomor_pemesanan' => 'ORD-2026-002'],
            [
                'pelanggan_id' => $pelangganBintang->id,
                'user_id' => $admin?->id,
                'jenis_kain' => 'Kaos Pendek Putih (Cotton Combed 30s)',
                'warna' => 'Putih Bersih',
                'ukuran_baju' => 'Ukuran L (3 pcs)',
                'jumlah' => 3,
                'detail_desain' => 'Tulisan Typography Minimalis COTTON',
                'tanggal_pemesanan' => now()->subDays(2)->toDateString(),
                'tanggal_selesai_estimasi' => now()->addDays(3)->toDateString(),
                'harga_satuan' => 48000,
                'total_harga' => 144000,
                'status_pemesanan' => 'dalam_produksi',
                'status_pembayaran' => 'dp',
                'catatan' => 'Gabung kluster sablon typography',
                'is_prioritas' => false,
                'kesiapan_bahan' => 'Bahan potongan siap di rak sablon',
                'lokasi_rak' => 'Rak Sablon B',
                'perlu_sablon' => true,
                'jenis_sablon' => 'Rubber Soft (Hitam Pekat)',
                'kode_gambar' => 'DSN-TYPO-COTTON',
                'status_sablon_antrean' => 'siap_cetak',
                'kuota_sablon_saat_ini' => 8,
                'kuota_sablon_target' => 5,
            ]
        );
        $prod2 = Produksi::updateOrCreate(['pemesanan_id' => $order2->id], [
            'kode_produksi' => 'PRD-2026-002',
            'user_id' => $budi->id,
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'status_pemotongan_kain' => 'selesai',
            'tahap_saat_ini' => 'sablon',
            'progress_persen' => 30,
            'status_produksi' => 'proses',
        ]);
        $prod2->inisialisasiTahapan();

        // 5. PESANAN ORD-2026-003 (Di Kanban Penjahitan Gambar 2)
        $order3 = Pemesanan::updateOrCreate(
            ['nomor_pemesanan' => 'ORD-2026-003'],
            [
                'pelanggan_id' => $pelangganBem->id,
                'user_id' => $admin?->id,
                'jenis_kain' => 'Polo Shirt Lacoste Pique',
                'warna' => 'Navy Blue',
                'ukuran_baju' => 'M (20 Pcs), L (30 Pcs)',
                'jumlah' => 50,
                'detail_desain' => 'Bordir dada kiri lambang fakultas',
                'tanggal_pemesanan' => now()->subDays(3)->toDateString(),
                'tanggal_selesai_estimasi' => now()->addDays(4)->toDateString(),
                'harga_satuan' => 85000,
                'total_harga' => 4250000,
                'status_pemesanan' => 'dalam_produksi',
                'status_pembayaran' => 'dp',
                'catatan' => 'Regu Jahit Siti (Lini 2) - Pemasangan kerah & manset selesai 32 dari 50 pcs.',
                'is_prioritas' => false,
                'kesiapan_bahan' => 'Bahan potongan, kancing & kerah lengkap siap ambil di Rak B3',
                'lokasi_rak' => 'Rak B3 / Lini Jahit 2',
                'perlu_sablon' => true,
                'jenis_sablon' => 'Bordir Komputer',
                'status_sablon_antrean' => 'tersimpan_aman',
            ]
        );
        $prod3 = Produksi::updateOrCreate(['pemesanan_id' => $order3->id], [
            'kode_produksi' => 'PRD-2026-003',
            'user_id' => $budi->id,
            'tanggal_mulai' => now()->subDays(2)->toDateString(),
            'status_pemotongan_kain' => 'selesai',
            'status_sablon' => 'selesai',
            'status_penjahitan' => 'sedang_diproses',
            'tahap_saat_ini' => 'penjahitan',
            'progress_persen' => 60,
            'status_produksi' => 'proses',
            'catatan_produksi' => 'Pemasangan kerah & manset selesai 32 dari 50 pcs.',
        ]);
        $prod3->inisialisasiTahapan();

        // 6. PESANAN ORD-2026-004 (Di Kluster Sablon DSN-YOUTH-26 Gambar 3)
        $order4 = Pemesanan::updateOrCreate(
            ['nomor_pemesanan' => 'ORD-2026-004'],
            [
                'pelanggan_id' => $pelangganYouth->id,
                'user_id' => $admin?->id,
                'jenis_kain' => 'Hoodie Jumper Navy (Cotton Fleece 330gsm)',
                'warna' => 'Navy Solid',
                'ukuran_baju' => 'XL (1 Pcs), XXL (2 Pcs)',
                'jumlah' => 3,
                'detail_desain' => 'Logo Komunitas Youth 2026 (Plastisol 3 Warna)',
                'tanggal_pemesanan' => now()->subDay()->toDateString(),
                'tanggal_selesai_estimasi' => now()->addDays(5)->toDateString(),
                'harga_satuan' => 140000,
                'total_harga' => 420000,
                'status_pemesanan' => 'dalam_produksi',
                'status_pembayaran' => 'dp',
                'catatan' => 'Menunggu tambahan pesanan dengan motif serupa (kurang 2 potong lagi)',
                'is_prioritas' => false,
                'kesiapan_bahan' => 'Kain fleece sudah dipotong, menunggu kuota screen',
                'lokasi_rak' => 'Rak A1',
                'perlu_sablon' => true,
                'jenis_sablon' => 'Plastisol 3 Warna',
                'kode_gambar' => 'DSN-YOUTH-26',
                'status_sablon_antrean' => 'menunggu_kuota',
                'kuota_sablon_saat_ini' => 3,
                'kuota_sablon_target' => 5,
            ]
        );
        $prod4 = Produksi::updateOrCreate(['pemesanan_id' => $order4->id], [
            'kode_produksi' => 'PRD-2026-004',
            'user_id' => $budi->id,
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'status_pemotongan_kain' => 'selesai',
            'status_sablon' => 'belum_mulai',
            'tahap_saat_ini' => 'sablon',
            'progress_persen' => 20,
            'status_produksi' => 'proses',
            'catatan_produksi' => 'Tertahan kuota sablon 3/5 pcs',
        ]);
        $prod4->inisialisasiTahapan();

        // 7. PESANAN ORD-2026-005 (Di Kanban Steam Gambar 2)
        $order5 = Pemesanan::updateOrCreate(
            ['nomor_pemesanan' => 'ORD-2026-005'],
            [
                'pelanggan_id' => $pelangganGowes->id,
                'user_id' => $admin?->id,
                'jenis_kain' => 'Jaket Parasut Taslan',
                'warna' => 'Hitam Kombinasi Abu',
                'ukuran_baju' => 'L: 30 Pcs, XL: 20 Pcs',
                'jumlah' => 50,
                'detail_desain' => 'Sablon reflektif scotlite punggung & dada',
                'tanggal_pemesanan' => now()->subDays(4)->toDateString(),
                'tanggal_selesai_estimasi' => now()->addDays(2)->toDateString(),
                'harga_satuan' => 115000,
                'total_harga' => 5750000,
                'status_pemesanan' => 'dalam_produksi',
                'status_pembayaran' => 'dp',
                'catatan' => 'Perapian & Setrika Uap: Jahitan selesai rapi, sedang proses steam perapian bentuk jaket.',
                'is_prioritas' => false,
                'kesiapan_bahan' => 'Bahan selesai dijahit rapi',
                'lokasi_rak' => 'Stasiun Steam 01',
                'perlu_sablon' => true,
                'jenis_sablon' => 'Sablon Reflektif',
                'status_sablon_antrean' => 'selesai',
            ]
        );
        $prod5 = Produksi::updateOrCreate(['pemesanan_id' => $order5->id], [
            'kode_produksi' => 'PRD-2026-005',
            'user_id' => $budi->id,
            'tanggal_mulai' => now()->subDays(3)->toDateString(),
            'status_pemotongan_kain' => 'selesai',
            'status_sablon' => 'selesai',
            'status_penjahitan' => 'selesai',
            'status_steam' => 'sedang_diproses',
            'tahap_saat_ini' => 'steam',
            'progress_persen' => 75,
            'status_produksi' => 'proses',
            'catatan_produksi' => 'Sedang proses steam perapian bentuk jaket',
        ]);
        $prod5->inisialisasiTahapan();

        // 8. PESANAN ORD-2026-008 (Di Kluster Sablon DSN-TYPO-COTTON Gambar 3)
        $order8 = Pemesanan::updateOrCreate(
            ['nomor_pemesanan' => 'ORD-2026-008'],
            [
                'pelanggan_id' => $pelangganSelaras->id,
                'user_id' => $admin?->id,
                'jenis_kain' => 'Kaos Broken White (Heavyweight 20s)',
                'warna' => 'Broken White',
                'ukuran_baju' => 'M (2 Pcs), L (3 Pcs)',
                'jumlah' => 5,
                'detail_desain' => 'Tulisan Typography Minimalis COTTON',
                'tanggal_pemesanan' => now()->subDays(2)->toDateString(),
                'tanggal_selesai_estimasi' => now()->addDays(3)->toDateString(),
                'harga_satuan' => 60000,
                'total_harga' => 300000,
                'status_pemesanan' => 'dalam_produksi',
                'status_pembayaran' => 'dp',
                'catatan' => 'Siap cetak bersama ORD-2026-002 (total 8 pcs)',
                'is_prioritas' => false,
                'kesiapan_bahan' => 'Pola potongan siap di rak sablon',
                'lokasi_rak' => 'Rak Sablon B',
                'perlu_sablon' => true,
                'jenis_sablon' => 'Rubber Soft (Hitam Pekat)',
                'kode_gambar' => 'DSN-TYPO-COTTON',
                'status_sablon_antrean' => 'siap_cetak',
                'kuota_sablon_saat_ini' => 8,
                'kuota_sablon_target' => 5,
            ]
        );
        $prod8 = Produksi::updateOrCreate(['pemesanan_id' => $order8->id], [
            'kode_produksi' => 'PRD-2026-008',
            'user_id' => $budi->id,
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'status_pemotongan_kain' => 'selesai',
            'tahap_saat_ini' => 'sablon',
            'progress_persen' => 30,
            'status_produksi' => 'proses',
        ]);
        $prod8->inisialisasiTahapan();

        // 9. Log Riwayat Pengerjaan SPK ORD-2026-001 (Sesuai Gambar 4)
        AktivitasLog::where('referensi', 'ORD-2026-001')->delete();
        AktivitasLog::create([
            'user_id' => $budi->id,
            'nama_user' => 'Budi Santoso',
            'tipe' => 'produksi',
            'aksi' => 'Pemotongan Pola Badan & Lengan Dimulai',
            'referensi' => 'ORD-2026-001',
            'warna' => 'teal',
            'keterangan' => 'Gelar tumpukan kain telah selesai dikalibrasi presisi. Pola ukuran M dan L dipotong terlebih dahulu sesuai urutan antrean jahit. Operator: Budi Santoso.',
            'created_at' => now()->setTime(8, 30),
        ]);
        AktivitasLog::create([
            'user_id' => $budi->id,
            'nama_user' => 'Budi Santoso',
            'tipe' => 'produksi',
            'aksi' => 'Pola Baju Siap Dikerjakan & SPK Diterima di Meja Potong',
            'referensi' => 'ORD-2026-001',
            'warna' => 'blue',
            'keterangan' => 'Lembar instruksi pola reguler fit O-Neck diverifikasi lengkap oleh Budi Santoso. Pisau potong vertikal telah diasah dan siap operasi.',
            'created_at' => now()->setTime(8, 10),
        ]);
        AktivitasLog::create([
            'user_id' => $admin?->id,
            'nama_user' => 'Administrator Konveksi COTTON.id',
            'tipe' => 'pesanan',
            'aksi' => 'SPK Diterbitkan & Masuk Antrean Lantai Produksi',
            'referensi' => 'ORD-2026-001',
            'warna' => 'slate',
            'keterangan' => 'Pesanan disetujui untuk mulai diproduksi batch pagi. File vektor sablon diserahkan ke bagian afdruk afkir screen.',
            'created_at' => now()->subDay()->setTime(16, 45),
        ]);
    }
}
