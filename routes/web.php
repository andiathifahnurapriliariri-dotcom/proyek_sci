<?php

use App\Livewire\Admin\AuditLog;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\DataPelanggan;
use App\Livewire\Admin\Laporan;
use App\Livewire\Admin\ManajemenPembayaran;
use App\Livewire\Admin\ManajemenPengguna;
use App\Livewire\Admin\ManajemenPesanan;
use App\Livewire\Admin\MutasiStokProduk;
use App\Livewire\Admin\Notifikasi as AdminNotifikasi;
use App\Livewire\Admin\PemantauanProduksi;
use App\Livewire\Admin\PersediaanProduk;
use App\Livewire\Admin\RiwayatPesanan;
use App\Livewire\Staff\AntreanProduksi as StaffAntreanProduksi;
use App\Livewire\Staff\AntreanSablon as StaffAntreanSablon;
use App\Livewire\Staff\Dashboard as StaffDashboard;
use App\Livewire\Staff\DetailPekerjaan as StaffDetailPekerjaan;
use App\Livewire\Staff\Notifikasi as StaffNotifikasi;
use App\Livewire\Staff\Profil as StaffProfil;
use App\Livewire\Staff\RiwayatPekerjaan as StaffRiwayatPekerjaan;
use App\Livewire\Staff\RiwayatSaya as StaffRiwayatSaya;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin'                                                                                     => redirect()->route('admin.dashboard'),
            'operator_produksi', 'staff', 'pemotongan', 'pemotongan_kain', 'sablon', 'penjahitan', 'steam' => redirect()->route('staff.dashboard'),
            default                                                                                     => redirect()->route('admin.dashboard'),
        };
    }
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard & Modul Admin COTTON.id (Khusus Role Admin)
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/admin/pelanggan', DataPelanggan::class)->name('admin.pelanggan.index');
        Route::get('/admin/pesanan', ManajemenPesanan::class)->name('admin.pesanan.index');
        Route::get('/admin/pembayaran', ManajemenPembayaran::class)->name('admin.pembayaran.index');
        Route::get('/admin/persediaan', PersediaanProduk::class)->name('admin.persediaan.index');
        Route::get('/admin/mutasi-stok', MutasiStokProduk::class)->name('admin.mutasi-stok.index');
        Route::get('/admin/produksi', PemantauanProduksi::class)->name('admin.produksi.index');
        Route::get('/admin/riwayat-pesanan', RiwayatPesanan::class)->name('admin.riwayat-pesanan.index');
        Route::get('/admin/laporan', Laporan::class)->name('admin.laporan.index');
        Route::get('/admin/audit-log', AuditLog::class)->name('admin.audit-log.index');
        Route::get('/admin/pengguna', ManajemenPengguna::class)->name('admin.pengguna.index');
        Route::get('/admin/notifikasi', AdminNotifikasi::class)->name('admin.notifikasi.index');
        Route::get('/admin/pembayaran/{pembayaran}/bukti', function (Pembayaran $pembayaran) {
            if ($pembayaran->bukti_pembayaran && Storage::disk('local')->exists($pembayaran->bukti_pembayaran)) {
                return Storage::disk('local')->response(
                    $pembayaran->bukti_pembayaran,
                    $pembayaran->kode_pembayaran,
                    ['Content-Disposition' => 'inline']
                );
            }

            return view('livewire.admin.struk-pembayaran-print', [
                'pembayaran' => $pembayaran->load(['pemesanan.pelanggan', 'pemesanan.pembayaran', 'admin']),
            ]);
        })->name('admin.pembayaran.bukti');
    });

    // Modul Portal Kerja Workshop (Khusus Role Staff Produksi)
    Route::middleware('staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', StaffDashboard::class)->name('dashboard');
        Route::get('/detail-pekerjaan/{pemesananId?}', StaffDetailPekerjaan::class)->name('detail-pekerjaan');
        Route::get('/antrean-produksi', StaffAntreanProduksi::class)->name('antrean-produksi');
        Route::get('/antrean-sablon', StaffAntreanSablon::class)->name('antrean-sablon');
        Route::get('/riwayat-pekerjaan', StaffRiwayatPekerjaan::class)->name('riwayat-pekerjaan');
        Route::get('/riwayat-saya', StaffRiwayatSaya::class)->name('riwayat-saya');
        Route::get('/notifikasi', StaffNotifikasi::class)->name('notifikasi');
        Route::get('/profil', StaffProfil::class)->name('profil');
    });

    // Alias rute dashboard lama untuk kompatibilitas
    Route::get('dashboard', function () {
        if (auth()->user()?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('staff.dashboard');
    })->name('dashboard');

    // Dashboard per divisi produksi (terintegrasi langsung ke Portal Kerja Workshop)
    Route::get('produksi/pemotongan', fn () => redirect()->route('staff.dashboard'))->name('produksi.pemotongan');
    Route::get('produksi/sablon', fn () => redirect()->route('staff.dashboard'))->name('produksi.sablon');
    Route::get('produksi/penjahitan', fn () => redirect()->route('staff.dashboard'))->name('produksi.penjahitan');
    Route::get('produksi/steam', fn () => redirect()->route('staff.dashboard'))->name('produksi.steam');
});

require __DIR__.'/settings.php';
