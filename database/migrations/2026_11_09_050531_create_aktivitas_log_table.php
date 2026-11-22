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
        Schema::create('aktivitas_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_user');
            $table->string('tipe', 50)->default('sistem'); // 'pesanan', 'pembayaran', 'produksi', 'stok', 'auth'
            $table->string('aksi');
            $table->string('referensi', 100)->nullable(); // e.g. ORD-2026-002, PRD-2026-001
            $table->string('warna', 30)->default('emerald'); // 'emerald', 'blue', 'rose', 'amber', 'purple'
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'tipe']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_log');
    }
};
