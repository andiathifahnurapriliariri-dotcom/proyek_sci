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
        if (!Schema::hasTable('notifikasi_admin')) {
            Schema::create('notifikasi_admin', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('kategori', 50)->default('pesanan'); // pesanan, pembayaran, produksi, stok_rop, selesai
                $table->string('judul');
                $table->text('pesan');
                $table->string('link')->nullable();
                $table->string('referensi_tipe')->nullable();
                $table->unsignedBigInteger('referensi_id')->nullable();
                $table->string('event_key')->nullable()->unique();
                $table->boolean('dibaca')->default(false)->index();
                $table->dateTime('dibaca_pada')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_admin');
    }
};
