<!DOCTYPE html>
<html lang="id" class="h-full notranslate" translate="no">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google" content="notranslate" />
    <title>{{ $title ?? config('app.name', 'COTTON.id') }}</title>
    
    <link rel="icon" href="{{ asset('images/logo-cotton.jpg') }}" type="image/jpeg">
    
    {{-- Google Fonts Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Chart.js untuk visual grafik bulanan dan donat pengerjaan --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }
        body {
            background-color: #F7F8FA;
        }
        /* Custom scrollbar halus untuk sidebar */
        .custom-sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }
    </style>
</head>
<body class="h-full text-gray-900 antialiased" x-data="{ mobileSidebarOpen: false }">
    <div class="flex min-h-screen">

        {{-- SIDEBAR GELAP SOFT (#0B0F19 & #111827) DENGAN TEKS PUTIH KONTRAS & CARD KAPSUL (SESUAI GAMBAR 1) --}}
        <aside class="w-64 shrink-0 flex flex-col min-h-screen sticky top-0 h-screen z-20 border-r border-slate-800/80 shadow-2xl relative overflow-hidden" style="background: linear-gradient(180deg, #0B0F19 0%, #111827 100%); width: 256px; min-width: 256px;">

            {{-- Header Logo Sidebar (Logo Bulat Sesuai Desain Asli & Bersih Tanpa Versi) --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-800/80 z-10 relative shrink-0">
                <div class="relative shrink-0 flex items-center justify-center rounded-full bg-black p-0.5 shadow-md border border-white/15 overflow-hidden" style="width: 42px; height: 42px; min-width: 42px; min-height: 42px; border-radius: 9999px;">
                    <img src="{{ asset('images/logo-cotton.jpg') }}" alt="COTTON.id Logo" class="object-cover rounded-full" style="width: 100%; height: 100%; object-fit: cover; border-radius: 9999px; display: block;">
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-base font-black tracking-tight text-white leading-tight">COTTON.id</h1>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Sistem Konveksi</p>
                </div>
            </div>

            {{-- Navigasi Menu (Teks Putih, Card Kapsul Rounded & Aksen Hijau Teal Kontras) --}}
            <div class="flex-1 overflow-y-auto px-3.5 pt-6 pb-4 space-y-5 z-10 relative custom-sidebar-scroll">
                {{-- MENU UTAMA --}}
                <div>
                    <p class="px-2 text-[11px] font-bold text-slate-400 tracking-wider uppercase mb-3 opacity-90">MENU UTAMA</p>
                    <nav class="space-y-1.5">
                        @php
                            $isDashboardAktif = request()->routeIs('admin.dashboard');
                            $isPelangganAktif = request()->routeIs('admin.pelanggan.*');
                            $isPesananAktif = request()->routeIs('admin.pesanan.*');
                            $isPembayaranAktif = request()->routeIs('admin.pembayaran.*');
                            $isPersediaanMutasiAktif = request()->routeIs('admin.persediaan.*', 'admin.mutasi-stok.*');
                            $isMonitoringProduksiAktif = request()->routeIs('admin.produksi.*');
                            $isRiwayatPesananAktif = request()->routeIs('admin.riwayat-pesanan.*');
                            $isLaporanAktif = request()->routeIs('admin.laporan.*');
                            $isAuditLogAktif = request()->routeIs('admin.audit-log.*');
                            $isPenggunaAktif = request()->routeIs('admin.pengguna.*');
                            $isPengaturanAktif = request()->routeIs('profile.*', 'security.*', 'appearance.*');
                        @endphp

                        {{-- Dashboard --}}
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl border-0 text-xs font-semibold transition {{ $isDashboardAktif ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/70 text-white hover:bg-[#1a273e]' }}" @if($isDashboardAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 {{ $isDashboardAktif ? 'text-white' : 'text-slate-300' }}" style="width: 18px; height: 18px; min-width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="9" x="3" y="3" rx="1"/>
                                <rect width="7" height="5" x="14" y="3" rx="1"/>
                                <rect width="7" height="9" x="14" y="12" rx="1"/>
                                <rect width="7" height="5" x="3" y="16" rx="1"/>
                            </svg>
                            <span translate="no" class="notranslate">Dashboard</span>
                        </a>

                        {{-- Data Pelanggan --}}
                        <a href="{{ route('admin.pelanggan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl border-0 text-xs font-semibold transition {{ $isPelangganAktif ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/70 text-white hover:bg-[#1a273e]' }}" @if($isPelangganAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 {{ $isPelangganAktif ? 'text-white' : 'text-slate-300' }}" style="width: 18px; height: 18px; min-width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <span>Data Pelanggan</span>
                        </a>

                        {{-- Manajemen Pesanan --}}
                        <a href="{{ route('admin.pesanan.index') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl border-0 text-xs font-semibold transition {{ $isPesananAktif ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/70 text-white hover:bg-[#1a273e]' }}" @if($isPesananAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-300" style="width: 18px; height: 18px; min-width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 3h8l2 2h3v16H3V5h3l2-2Z"/>
                                    <path d="M8 3v4h8V3M7 12h10M7 16h10"/>
                                </svg>
                                <span>Manajemen Pesanan</span>
                            </div>
                        </a>

                        {{-- Pembayaran --}}
                        <a href="{{ route('admin.pembayaran.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl border-0 text-xs font-semibold transition {{ $isPembayaranAktif ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/70 text-white hover:bg-[#1a273e]' }}" @if($isPembayaranAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 {{ $isPembayaranAktif ? 'text-white' : 'text-slate-300' }}" style="width: 18px; height: 18px; min-width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="5" rx="2"/>
                                <line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                            <span>Pembayaran</span>
                        </a>

                        {{-- Persediaan & Mutasi Stok --}}
                        <a href="{{ route('admin.persediaan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl border-0 text-xs font-semibold transition {{ $isPersediaanMutasiAktif ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/70 text-white hover:bg-[#1a273e]' }}" @if($isPersediaanMutasiAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 {{ $isPersediaanMutasiAktif ? 'text-white' : 'text-slate-300' }}" style="width: 18px; height: 18px; min-width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                <path d="m3.3 7 8.7 5 8.7-5"/>
                                <path d="M12 22V12"/>
                            </svg>
                            <span>Persediaan & Mutasi Stok</span>
                        </a>

                        {{-- Monitoring Produksi --}}
                        <a href="{{ route('admin.produksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl border-0 text-xs font-semibold transition {{ $isMonitoringProduksiAktif ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/70 text-white hover:bg-[#1a273e]' }}" @if($isMonitoringProduksiAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-300" style="width: 18px; height: 18px; min-width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                            </svg>
                            <span>Monitoring Produksi</span>
                        </a>

                        {{-- Riwayat Pesanan --}}
                        <a href="{{ route('admin.riwayat-pesanan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl border-0 text-xs font-semibold transition {{ $isRiwayatPesananAktif ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/70 text-white hover:bg-[#1a273e]' }}" @if($isRiwayatPesananAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-300" style="width: 18px; height: 18px; min-width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span>Riwayat Pesanan</span>
                        </a>
                    </nav>
                </div>

                {{-- TATA KELOLA --}}
                <div class="pt-2">
                    <p class="px-2 text-[11px] font-bold text-slate-400 tracking-wider uppercase mb-3 opacity-90">TATA KELOLA</p>
                    <nav class="space-y-1.5">
                        {{-- Laporan --}}
                        <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl border-0 text-white text-xs font-medium transition {{ $isLaporanAktif ? 'font-bold shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/50 hover:bg-[#1a273e]' }}" @if($isLaporanAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-slate-400" style="width: 16px; height: 16px; min-width: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" x2="18" y1="20" y2="10"/>
                                <line x1="12" x2="12" y1="20" y2="4"/>
                                <line x1="6" x2="6" y1="20" y2="14"/>
                            </svg>
                            <span>Laporan</span>
                        </a>

                        {{-- Audit Log --}}
                        <a href="{{ route('admin.audit-log.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl border-0 text-white text-xs font-medium transition {{ $isAuditLogAktif ? 'font-bold shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/50 hover:bg-[#1a273e]' }}" @if($isAuditLogAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 {{ $isAuditLogAktif ? 'text-white' : 'text-slate-400' }}" style="width: 16px; height: 16px; min-width: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" x2="8" y1="13" y2="13"/>
                                <line x1="16" x2="8" y1="17" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                            <span translate="no" class="notranslate">Audit Log</span>
                        </a>

                        {{-- Manajemen Pengguna --}}
                        <a href="{{ route('admin.pengguna.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl border-0 text-white text-xs font-medium transition {{ $isPenggunaAktif ? 'font-bold shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/50 hover:bg-[#1a273e]' }}" @if($isPenggunaAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 {{ $isPenggunaAktif ? 'text-white' : 'text-slate-400' }}" style="width: 16px; height: 16px; min-width: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <line x1="19" x2="19" y1="8" y2="14"/>
                                <line x1="22" x2="16" y1="11" y2="11"/>
                            </svg>
                            <span translate="no" class="notranslate">Manajemen Pengguna</span>
                        </a>

                        {{-- Pengaturan --}}
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl border-0 text-white text-xs font-medium transition {{ $isPengaturanAktif ? 'font-bold shadow-lg shadow-teal-950/60' : 'bg-[#121c2e]/50 hover:bg-[#1a273e]' }}" @if($isPengaturanAktif) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 {{ $isPengaturanAktif ? 'text-white' : 'text-slate-400' }}" style="width: 16px; height: 16px; min-width: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span translate="no" class="notranslate">Pengaturan</span>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- FOOTER KELUAR SISTEM DENGAN WATERMARK 3D LOGO DI BAGIAN BAWAH (CLEAN TANPA TEKS VERSI) --}}
            <div class="p-3 border-t border-slate-800/80 z-10 relative overflow-hidden shrink-0">
                {{-- Bayangan / Watermark 3D COTTON.id di area bawah dekat navigasi keluar --}}
                <div class="pointer-events-none absolute -bottom-5 -right-5 select-none overflow-hidden z-0" style="width: 130px; height: 130px; max-width: 130px; max-height: 130px;">
                    <img src="{{ asset('images/logo-cotton.jpg') }}" alt="Watermark 3D COTTON.id" style="width: 130px; height: 130px; max-width: 130px; max-height: 130px; object-fit: cover; border-radius: 9999px; opacity: 0.12; filter: drop-shadow(0 0 14px rgba(20, 184, 166, 0.4)); display: block;">
                </div>

                {{-- Form Keluar Sistem --}}
                <form method="POST" action="{{ route('logout') }}" class="relative z-10">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 rounded-2xl text-white hover:text-rose-400 hover:bg-rose-500/10 text-xs font-semibold transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-white/80 group-hover:text-rose-400" style="width: 16px; height: 16px; min-width: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- AREA KONTEN & HEADER --}}
        <div class="flex-1 min-w-0 flex flex-col min-h-screen">
            {{-- HEADER ATAS (Bersih: Breadcrumb "COTTON.id / Dashboard" & Tombol "+ Pesanan Baru" Aktif) --}}
            <header class="relative z-10 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 lg:px-8 shrink-0">
                {{-- Kiri: Tombol mobile + Breadcrumb Bersih --}}
                <div class="flex items-center gap-3">
                    <button type="button" @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex items-center text-sm">
                        <span class="font-extrabold text-gray-900 tracking-tight">COTTON.id</span>
                        <span class="mx-2 text-gray-300">/</span>
                        <span translate="no" class="notranslate font-bold text-gray-700">
                            {{ request()->routeIs('profile.*', 'security.*', 'appearance.*') ? 'Pengaturan Profil & Akun' : (request()->routeIs('admin.notifikasi.*') ? 'Pusat Notifikasi' : (request()->routeIs('admin.pengguna.*') ? 'Manajemen Pengguna & Hak Akses' : (request()->routeIs('admin.audit-log.*') ? 'Audit Log & Jejak Aktivitas' : (request()->routeIs('admin.laporan.*') ? 'Laporan Kinerja & Penjualan' : (request()->routeIs('admin.riwayat-pesanan.*') ? 'Riwayat Pesanan' : (request()->routeIs('admin.produksi.*') ? 'Pemantauan Produksi' : (request()->routeIs('admin.persediaan.*', 'admin.mutasi-stok.*') ? 'Persediaan & Mutasi Stok' : (request()->routeIs('admin.pembayaran.*') ? 'Pembayaran & Bukti Transfer' : (request()->routeIs('admin.pesanan.*') ? 'Manajemen Pemesanan' : (request()->routeIs('admin.pelanggan.*') ? 'Data Pelanggan' : 'Dashboard')))))))))) }}
                        </span>
                    </div>
                </div>

                {{-- Tengah: Search bar terintegrasi (Livewire Global Search) --}}
                <livewire:admin.global-search />

                {{-- Kanan: Aksi & Profil Pengguna --}}
                <div class="flex items-center gap-3">
                    {{-- Tombol + Pesanan Baru (Aktif Membuka Modal Form SPK Baru) --}}
                    @unless(request()->routeIs('admin.laporan.*', 'admin.notifikasi.*', 'profile.*', 'security.*', 'appearance.*'))
                    <button
                        type="button"
                        @if(request()->routeIs('admin.mutasi-stok.*', 'admin.persediaan.*'))
                            x-on:click="$dispatch('buka-modal-stok-masuk')"
                        @elseif(request()->routeIs('admin.pembayaran.*'))
                            x-on:click="$dispatch('buka-modal-pembayaran')"
                        @elseif(request()->routeIs('admin.produksi.*'))
                            x-on:click="window.location.href = '{{ route('admin.pesanan.index') }}'"
                        @elseif(request()->routeIs('admin.riwayat-pesanan.*'))
                            x-on:click="window.location.href = '{{ route('admin.pesanan.index') }}'"
                        @else
                            x-on:click="$dispatch('buka-modal-pesanan')"
                        @endif
                        class="flex items-center gap-1.5 rounded-full bg-black px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-gray-800 active:scale-95 transition cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>{{ request()->routeIs('admin.produksi.*') ? 'Kelola Pesanan' : (request()->routeIs('admin.persediaan.*', 'admin.mutasi-stok.*') ? 'Stok Masuk' : (request()->routeIs('admin.pembayaran.*') ? 'Catat Pembayaran' : 'Pesanan Baru')) }}</span>
                    </button>
                    @endunless

                    {{-- Ikon Notifikasi (Menuju Halaman Pusat Notifikasi Admin) --}}
                    @php
                        $unreadAdminNotifCount = \App\Models\NotifikasiAdmin::hitungBelumDibaca();
                        $isNotifikasiAktif = request()->routeIs('admin.notifikasi.*');
                    @endphp
                    <a
                        href="{{ route('admin.notifikasi.index') }}"
                        class="relative p-2 rounded-full transition {{ $isNotifikasiAktif ? 'bg-teal-50 text-teal-700 ring-2 ring-teal-600/30' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}"
                        title="Pemberitahuan Sistem ({{ $unreadAdminNotifCount }} Belum Dibaca)"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        @if($unreadAdminNotifCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-rose-500 text-white text-[10px] font-black ring-2 ring-white shadow-sm">
                                {{ $unreadAdminNotifCount > 99 ? '99+' : $unreadAdminNotifCount }}
                            </span>
                        @endif
                    </a>

                    <div class="h-6 w-px bg-gray-200"></div>

                    {{-- Profil Admin Dinamis --}}
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 font-bold text-gray-800 text-xs shadow-inner">
                            {{ auth()->user()->role === 'admin' ? 'M' : auth()->user()->initials() }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-gray-900 leading-tight">
                                {{ auth()->user()->role === 'admin' ? 'Michel' : auth()->user()->name }}
                            </p>
                            <p class="text-[11px] text-gray-500 font-medium">
                                {{ auth()->user()->role === 'admin' ? 'admin' : ucwords(str_replace('_', ' ', auth()->user()->role)) }}
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- KONTEN UTAMA --}}
            <main class="flex-1 p-4 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
