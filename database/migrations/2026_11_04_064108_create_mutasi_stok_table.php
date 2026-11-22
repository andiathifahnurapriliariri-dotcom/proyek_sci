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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_produk_id')->constrained('stok_produk')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis_mutasi', ['masuk', 'keluar']);
            $table->integer('jumlah_stok_masuk')->default(0);
            $table->integer('jumlah_stok_keluar')->default(0);
            $table->integer('saldo_sebelum')->default(0);
            $table->integer('saldo_sesudah')->default(0);
            $table->string('keterangan');
            $table->dateTime('tanggal_mutasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
