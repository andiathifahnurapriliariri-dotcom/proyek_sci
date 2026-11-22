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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran')->unique();
            $table->foreignId('pemesanan_id')->constrained('pemesanan')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Admin penerima
            $table->enum('jenis_pembayaran', ['DP', 'Pelunasan']);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->dateTime('tanggal_bayar');
            $table->string('metode_pembayaran')->default('Transfer Bank'); // 'Transfer Bank', 'Tunai', 'QRIS'
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_konfirmasi', ['Menunggu Verifikasi', 'Diterima', 'Ditolak'])->default('Diterima');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
