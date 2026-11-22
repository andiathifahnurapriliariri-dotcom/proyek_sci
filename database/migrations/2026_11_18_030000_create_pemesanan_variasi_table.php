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
        if (!Schema::hasTable('pemesanan_variasi')) {
            Schema::create('pemesanan_variasi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pemesanan_id')->constrained('pemesanan')->cascadeOnDelete();
                $table->string('kategori_ukuran', 50)->default('Dewasa'); // 'Dewasa' atau 'Anak-anak'
                $table->string('warna', 100);
                $table->string('ukuran', 50);
                $table->unsignedInteger('jumlah')->default(1);
                $table->timestamps();

                $table->index(['pemesanan_id', 'kategori_ukuran']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_variasi');
    }
};
