<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom pendukung di tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'lokasi_workshop')) {
                $table->string('lokasi_workshop')->default('Lantai Jahit & Workshop #01')->after('telepon');
            }
            if (!Schema::hasColumn('users', 'shift')) {
                $table->string('shift')->default('Shift Pagi (08:00 - 17:00 WIB)')->after('lokasi_workshop');
            }
            if (!Schema::hasColumn('users', 'stasiun_kerja')) {
                $table->string('stasiun_kerja')->default('Stasiun Kerja Budi')->after('shift');
            }
        });

        // 2. Tambah kolom pendukung di tabel pemesanan
        Schema::table('pemesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanan', 'is_prioritas')) {
                $table->boolean('is_prioritas')->default(false)->after('catatan');
            }
            if (!Schema::hasColumn('pemesanan', 'kesiapan_bahan')) {
                $table->string('kesiapan_bahan')->nullable()->after('is_prioritas');
            }
            if (!Schema::hasColumn('pemesanan', 'lokasi_rak')) {
                $table->string('lokasi_rak')->nullable()->after('kesiapan_bahan');
            }
            if (!Schema::hasColumn('pemesanan', 'perlu_sablon')) {
                $table->boolean('perlu_sablon')->default(true)->after('lokasi_rak');
            }
            if (!Schema::hasColumn('pemesanan', 'jenis_sablon')) {
                $table->string('jenis_sablon')->nullable()->after('perlu_sablon');
            }
            if (!Schema::hasColumn('pemesanan', 'status_sablon_antrean')) {
                $table->string('status_sablon_antrean')->default('tersimpan_aman')->after('jenis_sablon');
            }
            if (!Schema::hasColumn('pemesanan', 'kuota_sablon_saat_ini')) {
                $table->integer('kuota_sablon_saat_ini')->default(0)->after('status_sablon_antrean');
            }
            if (!Schema::hasColumn('pemesanan', 'kuota_sablon_target')) {
                $table->integer('kuota_sablon_target')->default(0)->after('kuota_sablon_saat_ini');
            }
        });

        // 3. Tabel detail 7 tahapan produksi untuk staf konveksi
        Schema::create('produksi_tahapan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksi')->cascadeOnDelete();
            $table->string('nama_tahap'); // pemotongan_kain, penjahitan, pembersihan_benang, steam, pelipatan, sablon, packing
            $table->string('label_tahap');
            $table->integer('urutan')->default(1);
            $table->boolean('is_required')->default(true);
            $table->enum('status', ['menunggu', 'siap_dikerjakan', 'sedang_dikerjakan', 'selesai', 'tertunda'])->default('menunggu');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Staff yang mengerjakan
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->text('catatan_pengerjaan')->nullable();
            $table->text('catatan_penundaan')->nullable();
            $table->timestamps();
        });

        // 4. Tabel notifikasi staf
        Schema::create('notifikasi_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('pemesanan_id')->nullable()->constrained('pemesanan')->nullOnDelete();
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe')->default('tugas_baru'); // tugas_baru, prioritas, bahan_siap, tahap_selesai, deadline, tertunda, sablon, sistem
            $table->boolean('dibaca')->default(false);
            $table->dateTime('dibaca_pada')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_staff');
        Schema::dropIfExists('produksi_tahapan');

        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn([
                'is_prioritas',
                'kesiapan_bahan',
                'lokasi_rak',
                'perlu_sablon',
                'jenis_sablon',
                'status_sablon_antrean',
                'kuota_sablon_saat_ini',
                'kuota_sablon_target',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lokasi_workshop', 'shift', 'stasiun_kerja']);
        });
    }
};
