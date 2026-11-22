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
        Schema::table('pemesanan_variasi', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanan_variasi', 'produk_id')) {
                $table->foreignId('produk_id')->nullable()->after('pemesanan_id')->constrained('produk')->nullOnDelete();
            }
            if (!Schema::hasColumn('pemesanan_variasi', 'nama_produk')) {
                $table->string('nama_produk', 150)->nullable()->after('produk_id');
            }
            if (!Schema::hasColumn('pemesanan_variasi', 'harga_satuan')) {
                $table->decimal('harga_satuan', 12, 2)->default(0)->after('jumlah');
            }
            if (!Schema::hasColumn('pemesanan_variasi', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('harga_satuan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_variasi', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->dropColumn(['produk_id', 'nama_produk', 'harga_satuan', 'subtotal']);
        });
    }
};
