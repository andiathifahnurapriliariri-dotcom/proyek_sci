-- ============================================================================
-- BASIS DATA: SISTEM INFORMASI MANAJEMEN PEMESANAN, STOK, DAN MONITORING PRODUKSI
-- PERUSAHAAN KONVEKSI: COTTON.id
-- ============================================================================
-- Bahasa: Bahasa Indonesia
-- DBMS: MySQL / MariaDB
-- Karakter Set: UTF-8 (utf8mb4_unicode_ci)
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;

-- ============================================================================
-- 1. TABEL: users (Data Pengguna / Akun Sistem)
-- Deskripsi: Menyimpan data akun pengguna dengan peran admin, operator produksi,
--            dan staf logistik/gudang.
-- ============================================================================

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Pengguna)',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Lengkap Pengguna',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Alamat Email untuk Masuk (Login)',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Verifikasi Email',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kata Sandi Terenkripsi (Hash Bcrypt)',
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin' COMMENT 'Peran Pengguna: admin, operator_produksi, pemilik',
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor Telepon / WhatsApp Pengguna',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Token Pengingat Sesi Login',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Data Pengguna dan Hak Akses Sistem COTTON.id';

-- Data Awal Pengguna
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `telepon`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Konveksi COTTON.id', 'admin@cotton.id', '2026-11-04 07:00:00', '$2y$12$D2B9zN3kGfPqv1vR2FmEou3B7T0q/8lGv5WbC8wK7zM.1A2B3C4De', 'admin', '081234567890', NOW(), NOW()),
(2, 'Budi Santoso (Petugas Produksi)', 'produksi@cotton.id', '2026-11-04 07:00:00', '$2y$12$D2B9zN3kGfPqv1vR2FmEou3B7T0q/8lGv5WbC8wK7zM.1A2B3C4De', 'operator_produksi', '081298765432', NOW(), NOW()),
(3, 'Dewi Lestari (Staf Gudang & Logistik)', 'gudang@cotton.id', '2026-11-04 07:00:00', '$2y$12$D2B9zN3kGfPqv1vR2FmEou3B7T0q/8lGv5WbC8wK7zM.1A2B3C4De', 'admin', '081377889900', NOW(), NOW());

-- View Alias Berbahasa Indonesia untuk Pengguna
CREATE OR REPLACE VIEW `pengguna` AS 
SELECT 
  `id` AS `id_pengguna`,
  `name` AS `nama_pengguna`,
  `email` AS `email`,
  `role` AS `peran`,
  `telepon` AS `nomor_telepon`,
  `created_at` AS `waktu_dibuat`,
  `updated_at` AS `waktu_diperbarui`
FROM `users`;


-- ============================================================================
-- 2. TABEL: pelanggan (Data Pelanggan Konveksi)
-- Deskripsi: Dikelola langsung oleh Admin untuk mencatat data konsumen pemesan.
-- ============================================================================

DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE `pelanggan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Pelanggan)',
  `kode_pelanggan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode Unik Pelanggan (Contoh: PEL-001)',
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Perorangan / Instansi / Komunitas',
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Alamat Lengkap Pengiriman / Domisili',
  `nomor_telepon` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor HP / WhatsApp Aktif',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Alamat Email Korespondensi',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pelanggan_kode_pelanggan_unique` (`kode_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Data Pelanggan yang Dikelola oleh Admin';

-- Data Awal Pelanggan
INSERT INTO `pelanggan` (`id`, `kode_pelanggan`, `nama`, `alamat`, `nomor_telepon`, `email`, `created_at`, `updated_at`) VALUES
(1, 'PEL-001', 'PT Sinergi Kreatif Nusantara', 'Gedung Wisma Niaga Lantai 4, Jalan Jenderal Sudirman No. 45, Jakarta Selatan', '081122334455', 'kontak@sinergikreatif.co.id', NOW(), NOW()),
(2, 'PEL-002', 'BEM Fakultas Ilmu Komputer Universitas Merdeka', 'Gedung F Kampus Terpadu, Jalan Pendidikan No. 12, Kota Malang', '085611223344', 'bem.fik@merdeka.ac.id', NOW(), NOW()),
(3, 'PEL-003', 'Komunitas Sepeda Gowes Sehat Surabaya', 'Perumahan Nirwana Asri Blok C-8, Rungkut, Kota Surabaya', '087799887766', 'gowessehat.sby@gmail.com', NOW(), NOW()),
(4, 'PEL-004', 'SMA Negeri 1 Jakarta (Panitia Pentas Seni)', 'Jalan Budi Utomo No. 7, Pasar Baru, Jakarta Pusat', '081388776655', 'pensi.sman1jkt@gmail.com', NOW(), NOW());


-- ============================================================================
-- 3. TABEL: produk (Master Katalog Produk Konveksi)
-- Deskripsi: Menyimpan katalog standar produk baju/pakaian yang diproduksi.
-- ============================================================================

DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Produk)',
  `kode_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode Produk (Contoh: PRD-001)',
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Produk Pakaian',
  `jenis_kain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis Bahan Kain (Contoh: Katun Combed 30s, Fleece, Drill)',
  `warna` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Varian Warna Bahan (Contoh: Hitam Pekat, Biru Dongker)',
  `ukuran_baju` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ukuran Baju (S, M, L, XL, XXL)',
  `harga_satuan` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Harga Dasar Satuan per Potong (Rp)',
  `deskripsi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Penjelasan Spesifikasi dan Kualitas Bahan',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  UNIQUE KEY `produk_kode_produk_unique` (`kode_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Katalog Produk Standar Konveksi COTTON.id';

-- Data Awal Produk
INSERT INTO `produk` (`id`, `kode_produk`, `nama_produk`, `jenis_kain`, `warna`, `ukuran_baju`, `harga_satuan`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'PRD-001', 'Kaos Polos Katun Combed 30s Lengan Pendek', 'Katun Combed 30s Reaktif', 'Hitam Pekat', 'L', 45000.00, 'Bahan 100% serat katun alami berkualitas tinggi, adem, berdaya serap keringat sangat baik, dan tekstur halus standar distro.', NOW(), NOW()),
(2, 'PRD-002', 'Kaos Polos Katun Combed 30s Lengan Pendek', 'Katun Combed 30s Reaktif', 'Putih Bersih', 'M', 45000.00, 'Bahan katun combed 30s putih cerah, benang rapat rapi, tidak menerawang, dan jahitan rantai pundak kokoh.', NOW(), NOW()),
(3, 'PRD-003', 'Kaos Katun Combed 24s Lengan Pendek', 'Katun Combed 24s', 'Biru Dongker', 'XL', 50000.00, 'Bahan katun combed 24s berkarakter lebih tebal, tidak mudah kusut, cocok untuk sablon plastisol maupun bordir.', NOW(), NOW()),
(4, 'PRD-004', 'Jaket Hoodie Kupluk Katun Fleece', 'Katun Fleece Tebal 280 gsm', 'Hitam Pekat', 'L', 125000.00, 'Jaket bertudung kepala (kupluk) berbahan katun fleece tebal dan lembut, menghangatkan tubuh dengan saku depan dan tali kur serut.', NOW(), NOW()),
(5, 'PRD-005', 'Kaos Berkerah Polo Katun Pique', 'Katun CVC Pique Hexagon', 'Merah Marun', 'XL', 75000.00, 'Pakaian formal kasual dengan kerah kokoh, kancing depan, dan rajutan corak pori-pori hexagon premium.', NOW(), NOW()),
(6, 'PRD-006', 'Kemeja PDL Lapangan Katun Drill', 'American Drill Katun Premium', 'Hijau Army', 'L', 95000.00, 'Kemeja dinas harian/lapangan tahan gesekan, memiliki ventilasi udara punggung, dua kantong dada, dan pengait lengan.', NOW(), NOW());


-- ============================================================================
-- 4. TABEL: stok_produk (Manajemen Stok & Batas Reorder Point / ROP)
-- Deskripsi: Dikelola oleh Admin untuk memonitor sisa persediaan fisik dan
--            memberikan peringatan batas ROP (Reorder Point).
-- ============================================================================

DROP TABLE IF EXISTS `stok_produk`;
CREATE TABLE `stok_produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Stok)',
  `produk_id` bigint unsigned NOT NULL COMMENT 'Relasi ke Tabel Produk (produk.id)',
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Produk',
  `warna` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Varian Warna Bahan',
  `ukuran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ukuran Baju (S, M, L, XL)',
  `sisa_stok` int NOT NULL DEFAULT '0' COMMENT 'Jumlah Sisa Stok Fisik yang Tersedia (pcs)',
  `batas_rop` int NOT NULL DEFAULT '10' COMMENT 'Ambang Batas Reorder Point / ROP (pcs)',
  `status_rop` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aman' COMMENT 'Status ROP: Aman, Batas ROP Tercapai, Habis',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  KEY `stok_produk_produk_id_foreign` (`produk_id`),
  CONSTRAINT `stok_produk_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Inventori Stok Fisik dan Peringatan Batas ROP';

-- Data Awal Stok Produk & Batas ROP
INSERT INTO `stok_produk` (`id`, `produk_id`, `nama_produk`, `warna`, `ukuran`, `sisa_stok`, `batas_rop`, `status_rop`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kaos Polos Katun Combed 30s Lengan Pendek', 'Hitam Pekat', 'L', 120, 30, 'Aman', NOW(), NOW()),
(2, 2, 'Kaos Polos Katun Combed 30s Lengan Pendek', 'Putih Bersih', 'M', 15, 25, 'Batas ROP Tercapai', NOW(), NOW()),
(3, 3, 'Kaos Katun Combed 24s Lengan Pendek', 'Biru Dongker', 'XL', 0, 20, 'Habis', NOW(), NOW()),
(4, 4, 'Jaket Hoodie Kupluk Katun Fleece', 'Hitam Pekat', 'L', 45, 15, 'Aman', NOW(), NOW()),
(5, 5, 'Kaos Berkerah Polo Katun Pique', 'Merah Marun', 'XL', 8, 15, 'Batas ROP Tercapai', NOW(), NOW()),
(6, 6, 'Kemeja PDL Lapangan Katun Drill', 'Hijau Army', 'L', 35, 10, 'Aman', NOW(), NOW());


-- ============================================================================
-- 5. TABEL: mutasi_stok (Pencatatan Stok Masuk dan Keluar)
-- Deskripsi: Audit trail mencatat aliran barang masuk (restock/hasil jahit)
--            dan keluar (pemesanan/penjualan/opname).
-- ============================================================================

DROP TABLE IF EXISTS `mutasi_stok`;
CREATE TABLE `mutasi_stok` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Mutasi)',
  `stok_produk_id` bigint unsigned NOT NULL COMMENT 'Relasi ke Tabel Stok Produk (stok_produk.id)',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Relasi ke Pengguna yang Mencatat (users.id)',
  `jenis_mutasi` enum('masuk','keluar') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis Aliran Stok: masuk atau keluar',
  `jumlah_stok_masuk` int NOT NULL DEFAULT '0' COMMENT 'Kuantitas Stok Masuk (pcs)',
  `jumlah_stok_keluar` int NOT NULL DEFAULT '0' COMMENT 'Kuantitas Stok Keluar (pcs)',
  `saldo_sebelum` int NOT NULL DEFAULT '0' COMMENT 'Saldo Stok Sebelum Terjadi Mutasi',
  `saldo_sesudah` int NOT NULL DEFAULT '0' COMMENT 'Saldo Stok Setelah Mutasi Dicatat',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Catatan Alasan Mutasi',
  `tanggal_mutasi` datetime NOT NULL COMMENT 'Waktu dan Tanggal Terjadinya Mutasi',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  KEY `mutasi_stok_stok_produk_id_foreign` (`stok_produk_id`),
  KEY `mutasi_stok_user_id_foreign` (`user_id`),
  CONSTRAINT `mutasi_stok_stok_produk_id_foreign` FOREIGN KEY (`stok_produk_id`) REFERENCES `stok_produk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mutasi_stok_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Riwayat Mutasi Stok Masuk dan Keluar';

-- Data Awal Mutasi Stok
INSERT INTO `mutasi_stok` (`id`, `stok_produk_id`, `user_id`, `jenis_mutasi`, `jumlah_stok_masuk`, `jumlah_stok_keluar`, `saldo_sebelum`, `saldo_sesudah`, `keterangan`, `tanggal_mutasi`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'masuk', 150, 0, 0, 150, 'Penerimaan stok awal dari pabrik pemasok kain tekstil', DATE_SUB(NOW(), INTERVAL 12 DAY), NOW(), NOW()),
(2, 1, 1, 'keluar', 0, 30, 150, 120, 'Pengeluaran bahan untuk pengerjaan pesanan seragam instansi', DATE_SUB(NOW(), INTERVAL 6 DAY), NOW(), NOW()),
(3, 2, 1, 'keluar', 0, 35, 50, 15, 'Pengambilan stok untuk pemenuhan pesanan kaos acara (sisa stok mencapai batas ROP)', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW(), NOW()),
(4, 4, 3, 'masuk', 50, 0, 0, 50, 'Penerimaan bahan kain katun fleece baru dari gudang bahan baku', DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), NOW());


-- ============================================================================
-- 6. TABEL: pemesanan (Pemesanan Pakaian Konveksi)
-- Deskripsi: Dikelola oleh Admin untuk menginput order pesanan pakaian custom
--            (kain, warna, ukuran, jumlah, desain sablon/bordir).
-- ============================================================================

DROP TABLE IF EXISTS `pemesanan`;
CREATE TABLE `pemesanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Pemesanan)',
  `nomor_pemesanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor Pesanan Unik (Contoh: PES-202611-0001)',
  `pelanggan_id` bigint unsigned NOT NULL COMMENT 'Relasi ke Tabel Pelanggan (pelanggan.id)',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Admin yang Mengelola Pesanan (users.id)',
  `produk_id` bigint unsigned DEFAULT NULL COMMENT 'Relasi ke Produk Katalog jika ada (produk.id)',
  `jenis_kain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis Kain yang Dipesan',
  `warna` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Warna Pakaian',
  `ukuran_baju` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ukuran Baju (S, M, L, XL, Custom)',
  `jumlah` int NOT NULL COMMENT 'Total Kuantitas Pesanan (potong/pcs)',
  `detail_desain` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Rincian Sablon/Bordir, Posisi, Dimensi Gambar',
  `file_desain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path File Gambar Mockup / Sketsa Desain',
  `tanggal_pemesanan` date NOT NULL COMMENT 'Tanggal Pemesanan Dibuat',
  `tanggal_selesai_estimasi` date DEFAULT NULL COMMENT 'Estimasi Tanggal Pesanan Selesai Diproduksi',
  `harga_satuan` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Harga Satuan per Potong (Rp)',
  `total_harga` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Total Biaya Pesanan (Rp)',
  `status_pemesanan` enum('menunggu_pembayaran','antrian_produksi','dalam_produksi','selesai_produksi','siap_diambil','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_pembayaran' COMMENT 'Status Alur Pemesanan',
  `status_pembayaran` enum('belum_bayar','dp','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar' COMMENT 'Status Pembayaran Pesanan',
  `catatan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan Khusus dari Pelanggan / Admin',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pemesanan_nomor_pemesanan_unique` (`nomor_pemesanan`),
  KEY `pemesanan_pelanggan_id_foreign` (`pelanggan_id`),
  KEY `pemesanan_user_id_foreign` (`user_id`),
  KEY `pemesanan_produk_id_foreign` (`produk_id`),
  CONSTRAINT `pemesanan_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pemesanan_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pemesanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Data Pemesanan Pakaian Konveksi yang Dikelola Admin';

-- Data Awal Pemesanan
INSERT INTO `pemesanan` (`id`, `nomor_pemesanan`, `pelanggan_id`, `user_id`, `produk_id`, `jenis_kain`, `warna`, `ukuran_baju`, `jumlah`, `detail_desain`, `file_desain`, `tanggal_pemesanan`, `tanggal_selesai_estimasi`, `harga_satuan`, `total_harga`, `status_pemesanan`, `status_pembayaran`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'PES-202611-0001', 1, 1, 1, 'Katun Combed 30s Reaktif', 'Hitam Pekat', 'L', 100, 'Sablon Cat Plastisol Logo Perusahaan di bagian dada kiri (ukuran 8x8 cm) serta Desain Tipografi Inspiratif di punggung belakang (ukuran A3 Penuh Warna)', 'desain/pes-0001-sinergi.png', DATE_SUB(CURDATE(), INTERVAL 7 DAY), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 65000.00, 6500000.00, 'dalam_produksi', 'dp', 'Prioritas pengerjaan untuk seragam acara temu tahunan kantor', NOW(), NOW()),
(2, 'PES-202611-0002', 2, 1, 4, 'Katun Fleece Tebal 280 gsm', 'Biru Dongker', 'XL', 50, 'Bordir Mesin Komputer Lambang Fakultas di dada kanan serta Sablon Karet Rubber Nama Angkatan Mahasiswa di punggung belakang', 'desain/pes-0002-bem.png', DATE_SUB(CURDATE(), INTERVAL 14 DAY), DATE_SUB(CURDATE(), INTERVAL 1 DAY), 150000.00, 7500000.00, 'selesai', 'lunas', 'Pesanan jaket seragam angkatan mahasiswa baru', NOW(), NOW()),
(3, 'PES-202611-0003', 3, 1, 3, 'Katun Combed 24s', 'Hijau Lumut', 'M', 30, 'Sablon Digital Transfer Film (DTF) Ilustrasi Sepeda Klasik di bagian tengah dada depan (ukuran A4)', 'desain/pes-0003-gowes.png', DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 70000.00, 2100000.00, 'menunggu_pembayaran', 'belum_bayar', 'Menunggu konfirmasi pembayaran uang muka (DP) dari bendahara komunitas', NOW(), NOW()),
(4, 'PES-202611-0004', 4, 1, 1, 'Katun Combed 30s Reaktif', 'Putih Bersih', 'S', 80, 'Sablon Cat Discharge Ilustrasi Musik Pentas Seni pada bagian depan dada dan daftar sponsor di punggung belakang', 'desain/pes-0004-pensi.png', DATE_SUB(CURDATE(), INTERVAL 3 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 60000.00, 4800000.00, 'antrian_produksi', 'dp', 'Kaos panitia pentas seni sekolah SMA 1, jadwal pakai tanggal 25', NOW(), NOW());


-- ============================================================================
-- 7. TABEL: pembayaran (Pembayaran Uang Muka / DP dan Pelunasan)
-- Deskripsi: Mencatat riwayat transaksi keuangan pemesanan konveksi.
-- ============================================================================

DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Pembayaran)',
  `kode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor Pembayaran Unik (Contoh: BYR-202611-0001)',
  `pemesanan_id` bigint unsigned NOT NULL COMMENT 'Relasi ke Tabel Pemesanan (pemesanan.id)',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Admin yang Memverifikasi Pembayaran (users.id)',
  `jenis_pembayaran` enum('DP','Pelunasan') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Skema Pembayaran: DP (Uang Muka) atau Pelunasan',
  `jumlah_bayar` decimal(12,2) NOT NULL COMMENT 'Nominal Pembayaran (Rp)',
  `tanggal_bayar` datetime NOT NULL COMMENT 'Waktu dan Tanggal Pembayaran Dilakukan',
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Transfer Bank' COMMENT 'Metode Bayar: Transfer Bank, Tunai, QRIS',
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path File Bukti Transfer / Struk Pembayaran',
  `status_konfirmasi` enum('Menunggu Verifikasi','Diterima','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diterima' COMMENT 'Status Verifikasi oleh Admin',
  `catatan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan Transaksi / Nama Bank Pengirim',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pembayaran_kode_pembayaran_unique` (`kode_pembayaran`),
  KEY `pembayaran_pemesanan_id_foreign` (`pemesanan_id`),
  KEY `pembayaran_user_id_foreign` (`user_id`),
  CONSTRAINT `pembayaran_pemesanan_id_foreign` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Transaksi Pembayaran Uang Muka (DP) dan Pelunasan';

-- Data Awal Pembayaran
INSERT INTO `pembayaran` (`id`, `kode_pembayaran`, `pemesanan_id`, `user_id`, `jenis_pembayaran`, `jumlah_bayar`, `tanggal_bayar`, `metode_pembayaran`, `bukti_pembayaran`, `status_konfirmasi`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'BYR-202611-0001', 1, 1, 'DP', 3250000.00, DATE_SUB(NOW(), INTERVAL 6 DAY), 'Transfer Bank', 'bukti_transfer/byr-0001.jpg', 'Diterima', 'Uang muka (DP) 50% diterima melalui transfer Bank BCA rekening resmi COTTON.id', NOW(), NOW()),
(2, 'BYR-202611-0002', 2, 1, 'DP', 3750000.00, DATE_SUB(NOW(), INTERVAL 13 DAY), 'Transfer Bank', 'bukti_transfer/byr-0002.jpg', 'Diterima', 'Uang muka (DP) 50% untuk pesanan jaket mahasiswa BEM telah diverifikasi', NOW(), NOW()),
(3, 'BYR-202611-0003', 2, 1, 'Pelunasan', 3750000.00, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Transfer Bank', 'bukti_transfer/byr-0003.jpg', 'Diterima', 'Pelunasan 50% disetorkan penuh saat pesanan siap diambil', NOW(), NOW()),
(4, 'BYR-202611-0004', 4, 1, 'DP', 2400000.00, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Transfer Bank', 'bukti_transfer/byr-0004.jpg', 'Diterima', 'Uang muka (DP) 50% kaos panitia pentas seni sekolah telah diverifikasi', NOW(), NOW());


-- ============================================================================
-- 8. TABEL: produksi (Monitoring 4 Tahapan Produksi Konveksi)
-- Deskripsi: Memonitor alur kerja manufaktur konveksi COTTON.id:
--            1. Pemotongan Kain (Cutting)
--            2. Sablon / Bordir (Printing)
--            3. Penjahitan (Sewing)
--            4. Steam (Finishing, QC, dan Pengemasan)
-- ============================================================================

DROP TABLE IF EXISTS `produksi`;
CREATE TABLE `produksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama (ID Produksi)',
  `kode_produksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor SPK Produksi Unik (Contoh: PRD-202611-001)',
  `pemesanan_id` bigint unsigned NOT NULL COMMENT 'Relasi ke Tabel Pemesanan (pemesanan.id)',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Petugas / Operator Produksi Penanggung Jawab (users.id)',
  `tanggal_mulai` date NOT NULL COMMENT 'Tanggal Mulai Pengerjaan Produksi',
  `tanggal_selesai` date DEFAULT NULL COMMENT 'Tanggal Selesai Seluruh Tahapan Produksi',
  
  -- Tahap 1: Pemotongan Kain
  `status_pemotongan_kain` enum('belum_mulai','sedang_diproses','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_mulai' COMMENT 'Status Tahap 1: Pemotongan Kain',
  `tgl_pemotongan_kain` datetime DEFAULT NULL COMMENT 'Waktu Selesai Pemotongan Kain',
  `catatan_pemotongan_kain` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan Pemotongan Pola dan Kain',

  -- Tahap 2: Sablon / Bordir
  `status_sablon` enum('belum_mulai','sedang_diproses','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_mulai' COMMENT 'Status Tahap 2: Sablon / Bordir',
  `tgl_sablon` datetime DEFAULT NULL COMMENT 'Waktu Selesai Sablon / Bordir',
  `catatan_sablon` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan Penyablonan dan Pengeringan',

  -- Tahap 3: Penjahitan
  `status_penjahitan` enum('belum_mulai','sedang_diproses','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_mulai' COMMENT 'Status Tahap 3: Penjahitan',
  `tgl_penjahitan` datetime DEFAULT NULL COMMENT 'Waktu Selesai Penjahitan',
  `catatan_penjahitan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan Obras, Overdeck, dan Jahit Rantai',

  -- Tahap 4: Steam (Finishing & Pengemasan)
  `status_steam` enum('belum_mulai','sedang_diproses','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_mulai' COMMENT 'Status Tahap 4: Penyetrikaan Uap, QC, dan Kemas',
  `tgl_steam` datetime DEFAULT NULL COMMENT 'Waktu Selesai Penyetrikaan Uap & Kemas',
  `catatan_steam` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan Setrika Uap dan Pemeriksaan Kualitas QC',

  -- Monitoring Ringkasan
  `tahap_saat_ini` enum('pemotongan_kain','sablon','penjahitan','steam','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pemotongan_kain' COMMENT 'Tahapan yang Sedang Berjalan',
  `progress_persen` int NOT NULL DEFAULT '0' COMMENT 'Persentase Kemajuan Produksi (0% - 100%)',
  `status_produksi` enum('antrian','proses','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'antrian' COMMENT 'Status SPK Produksi Keseluruhan',
  `catatan_produksi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan Ringkasan dari Petugas Produksi',
  
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Dibuat',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Data Terakhir Diperbarui',
  PRIMARY KEY (`id`),
  UNIQUE KEY `produksi_kode_produksi_unique` (`kode_produksi`),
  KEY `produksi_pemesanan_id_foreign` (`pemesanan_id`),
  KEY `produksi_user_id_foreign` (`user_id`),
  CONSTRAINT `produksi_pemesanan_id_foreign` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produksi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Monitoring 4 Tahapan Produksi Konveksi COTTON.id';

-- Data Awal Monitoring Produksi
INSERT INTO `produksi` (`id`, `kode_produksi`, `pemesanan_id`, `user_id`, `tanggal_mulai`, `tanggal_selesai`, `status_pemotongan_kain`, `tgl_pemotongan_kain`, `catatan_pemotongan_kain`, `status_sablon`, `tgl_sablon`, `catatan_sablon`, `status_penjahitan`, `tgl_penjahitan`, `catatan_penjahitan`, `status_steam`, `tgl_steam`, `catatan_steam`, `tahap_saat_ini`, `progress_persen`, `status_produksi`, `catatan_produksi`, `created_at`, `updated_at`) VALUES
(1, 'PRD-202611-001', 1, 2, DATE_SUB(CURDATE(), INTERVAL 5 DAY), NULL, 'selesai', DATE_SUB(NOW(), INTERVAL 5 DAY), 'Pemotongan pola baju ukuran L sebanyak 100 lembar kain selesai dengan rapi dan presisi tanpa cacat bahan', 'selesai', DATE_SUB(NOW(), INTERVAL 3 DAY), 'Penyablonan cat plastisol 2 sisi selesai dipanaskan dengan mesin curing pengering, warna pekat dan tahan uji cuci', 'sedang_diproses', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Proses penjahitan obras badan, pemasangan kerah leher, dan jahit rantai pundak sedang berjalan 60 dari 100 potong pakaian', 'belum_mulai', NULL, 'Menunggu proses penjahitan selesai', 'penjahitan', 60, 'proses', 'Pengerjaan sesuai jadwal, perkiraan selesai jahit besok siang kemudian lanjut tahap penyetrikaan uap dan pengemasan', NOW(), NOW()),
(2, 'PRD-202611-002', 2, 2, DATE_SUB(CURDATE(), INTERVAL 12 DAY), DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'selesai', DATE_SUB(NOW(), INTERVAL 12 DAY), 'Bahan katun fleece biru dongker 50 potong pola dipotong sesuai ukuran', 'selesai', DATE_SUB(NOW(), INTERVAL 9 DAY), 'Bordir komputer emblem lambang fakultas selesai presisi dan rapat benang', 'selesai', DATE_SUB(NOW(), INTERVAL 5 DAY), 'Penjahitan jaket hoodie, saku kantong depan, dan pemasangan tali serut telah selesai dengan kuat', 'selesai', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Penyetrikaan uap (steam), pembersihan sisa benang (QC), pelipatan rapi, dan pengemasan dalam plastik segel selesai', 'selesai', 100, 'selesai', 'Seluruh pakaian telah selesai 100% dan lolos pemeriksaan kendali mutu kualitas (Quality Control)', NOW(), NOW());


-- ============================================================================
-- 9. TABEL PENDUKUNG SISTEM (Autentikasi & Antrean Framework Laravel)
-- Deskripsi: Digunakan secara internal oleh framework Laravel untuk token reset,
--            sesi aktif pengguna, dan penanganan antrean sistem.
-- ============================================================================

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email Pengguna',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token Reset Kata Sandi',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu Permintaan Token',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Token Pengaturan Ulang Kata Sandi';

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID Sesi Unik',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'ID Pengguna Terkait',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Alamat IP Pengguna',
  `user_agent` text COLLATE utf8mb4_unicode_ci COMMENT 'Informasi Peramban / Browser',
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Data Enkripsi Sesi Aktif',
  `last_activity` int NOT NULL COMMENT 'Waktu Aktivitas Terakhir (Unix Timestamp)',
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Sesi Pengguna Aktif';

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kunci Cache',
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nilai Data Cache',
  `expiration` bigint NOT NULL COMMENT 'Waktu Kedaluwarsa Cache',
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Penyimpanan Memori Singgah (Cache)';

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kunci Kunci Cache',
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Pemilik Kunci',
  `expiration` bigint NOT NULL COMMENT 'Waktu Kedaluwarsa Kunci',
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Pengunci Cache Konkurensi';

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama Antrean Pekerjaan',
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Antrean',
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Data Muatan Tugas',
  `attempts` tinyint unsigned NOT NULL COMMENT 'Jumlah Percobaan Eksekusi',
  `reserved_at` int unsigned DEFAULT NULL COMMENT 'Waktu Tugas Direservasi',
  `available_at` int unsigned NOT NULL COMMENT 'Waktu Tugas Siap Dijalankan',
  `created_at` int unsigned NOT NULL COMMENT 'Waktu Tugas Dibuat',
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Antrean Pekerjaan Latar Belakang (Queue Jobs)';

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Kunci Utama Pekerjaan Gagal',
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'UUID Unik Pekerjaan Gagal',
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Koneksi',
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Antrean',
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Data Muatan Tugas',
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Pesan Galat / Eksepsi',
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu Kegagalan',
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Riwayat Pekerjaan Latar Belakang yang Gagal';

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID Batch Pekerjaan',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Batch',
  `total_jobs` int NOT NULL COMMENT 'Total Pekerjaan dalam Batch',
  `pending_jobs` int NOT NULL COMMENT 'Jumlah Pekerjaan Menunggu',
  `failed_jobs` int NOT NULL COMMENT 'Jumlah Pekerjaan Gagal',
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Daftar ID Pekerjaan yang Gagal',
  `options` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Opsi Konfigurasi Batch',
  `cancelled_at` int DEFAULT NULL COMMENT 'Waktu Pembatalan Batch',
  `created_at` int NOT NULL COMMENT 'Waktu Batch Dibuat',
  `finished_at` int DEFAULT NULL COMMENT 'Waktu Batch Selesai',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Kelompok Batch Antrean Pekerjaan';

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID Migrasi',
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama File Migrasi Laravel',
  `batch` int NOT NULL COMMENT 'Nomor Urutan Batch Migrasi',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel Riwayat Migrasi Skema Database Laravel';

-- Riwayat Migrasi yang Telah Terpasang
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_04_064103_create_pelanggan_table', 1),
(5, '2026_11_04_064105_create_produk_table', 1),
(6, '2026_11_04_064106_create_stok_produk_table', 1),
(7, '2026_11_04_064108_create_mutasi_stok_table', 1),
(8, '2026_11_04_064110_create_pemesanan_table', 1),
(9, '2026_11_04_064112_create_pembayaran_table', 1),
(10, '2026_11_04_064115_create_produksi_table', 1);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- ============================================================================
-- AKHIR DARI SKEMA BASIS DATA COTTON.id BERBAHASA INDONESIA
-- ============================================================================
