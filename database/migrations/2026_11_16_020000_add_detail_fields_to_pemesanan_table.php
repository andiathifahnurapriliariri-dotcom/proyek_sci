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
        Schema::table('pemesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanan', 'kode_gambar')) {
                $table->string('kode_gambar')->nullable()->after('jenis_sablon');
            }
            if (!Schema::hasColumn('pemesanan', 'kategori_produk')) {
                $table->string('kategori_produk')->nullable()->after('kode_gambar');
            }
            if (!Schema::hasColumn('pemesanan', 'pic_pelanggan')) {
                $table->string('pic_pelanggan')->nullable()->after('kategori_produk');
            }
            if (!Schema::hasColumn('pemesanan', 'instruksi_kemasan')) {
                $table->string('instruksi_kemasan')->nullable()->after('pic_pelanggan');
            }
            if (!Schema::hasColumn('pemesanan', 'titik_sablon')) {
                $table->integer('titik_sablon')->default(1)->after('instruksi_kemasan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn([
                'kode_gambar',
                'kategori_produk',
                'pic_pelanggan',
                'instruksi_kemasan',
                'titik_sablon',
            ]);
        });
    }
};
