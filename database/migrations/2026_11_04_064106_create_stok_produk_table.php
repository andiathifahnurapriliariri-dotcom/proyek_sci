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
        Schema::create('stok_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('nama_produk');
            $table->string('warna');
            $table->string('ukuran', 20);
            $table->integer('sisa_stok')->default(0);
            $table->integer('batas_rop')->default(10); // Reorder Point (ROP) threshold
            $table->string('status_rop')->default('Aman'); // 'Aman', 'Batas ROP Tercapai', 'Habis'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_produk');
    }
};
