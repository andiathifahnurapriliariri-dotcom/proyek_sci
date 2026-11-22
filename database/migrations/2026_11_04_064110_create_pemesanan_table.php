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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pemesanan')->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Admin pengelola
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete();
            $table->string('jenis_kain');
            $table->string('warna');
            $table->string('ukuran_baju', 20);
            $table->integer('jumlah');
            $table->text('detail_desain');
            $table->string('file_desain')->nullable();
            $table->date('tanggal_pemesanan');
            $table->date('tanggal_selesai_estimasi')->nullable();
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_pemesanan', [
                'menunggu_pembayaran',
                'antrian_produksi',
                'dalam_produksi',
                'selesai_produksi',
                'siap_diambil',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_pembayaran');
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
