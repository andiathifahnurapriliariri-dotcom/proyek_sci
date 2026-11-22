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
        Schema::create('produksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produksi')->unique();
            $table->foreignId('pemesanan_id')->constrained('pemesanan')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Operator / PJ Produksi
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            // Tahap 1: Pemotongan Kain
            $table->enum('status_pemotongan_kain', ['belum_mulai', 'sedang_diproses', 'selesai'])->default('belum_mulai');
            $table->dateTime('tgl_pemotongan_kain')->nullable();
            $table->text('catatan_pemotongan_kain')->nullable();

            // Tahap 2: Sablon
            $table->enum('status_sablon', ['belum_mulai', 'sedang_diproses', 'selesai'])->default('belum_mulai');
            $table->dateTime('tgl_sablon')->nullable();
            $table->text('catatan_sablon')->nullable();

            // Tahap 3: Penjahitan
            $table->enum('status_penjahitan', ['belum_mulai', 'sedang_diproses', 'selesai'])->default('belum_mulai');
            $table->dateTime('tgl_penjahitan')->nullable();
            $table->text('catatan_penjahitan')->nullable();

            // Tahap 4: Steam (Finishing & Packing)
            $table->enum('status_steam', ['belum_mulai', 'sedang_diproses', 'selesai'])->default('belum_mulai');
            $table->dateTime('tgl_steam')->nullable();
            $table->text('catatan_steam')->nullable();

            // Monitoring Keseluruhan
            $table->enum('tahap_saat_ini', ['pemotongan_kain', 'sablon', 'penjahitan', 'steam', 'selesai'])->default('pemotongan_kain');
            $table->integer('progress_persen')->default(0); // 0, 25, 50, 75, 100%
            $table->enum('status_produksi', ['antrian', 'proses', 'selesai', 'dibatalkan'])->default('antrian');
            $table->text('catatan_produksi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
