<!DOCTYPE html>
<html lang="id" class="h-full notranslate" translate="no">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="google" content="notranslate" />
    <title>{{ $title ?? 'Portal Kerja Workshop - COTTON.id' }}</title>
    
    <link rel="icon" href="{{ asset('images/logo-cotton.jpg') }}" type="image/jpeg">
    
    {{-- Google Fonts Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Chart.js untuk visual grafik bulanan workshop jika dibutuhkan --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            box-sizing: border-box;
        }
        body {
            background-color: #F8F9FA;
            color: #111827;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
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
        /* Proteksi aset visual agar tidak membesar raksasa atau merusak layout */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        svg {
            max-width: 100%;
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
        }
        /* Grid layout pasti rapi & proporsional untuk detail pekerjaan (Persis Gambar 2) */
        .staff-detail-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
            width: 100% !important;
            align-items: start !important;
        }
        @media (min-width: 1024px) {
            .staff-detail-grid {
                grid-template-columns: minmax(0, 7fr) minmax(0, 5fr) !important;
            }
        }
        .staff-col-left {
            width: 100% !important;
            min-width: 0 !important;
        }
        .staff-col-right {
            width: 100% !important;
            min-width: 0 !important;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full text-gray-900 antialiased" x-data="{ mobileSidebarOpen: false }">
    <div class="flex min-h-screen bg-[#F8F9FA] w-full" style="min-height: 100vh; width: 100%;">

        @php
            $user = auth()->user();
            $unreadNotif = \App\Models\NotifikasiStaff::untukUser($user?->id)->belumDibaca()->count();
            if ($unreadNotif === 0) {
                $unreadNotif = 4; // default prototype
            }
            $antreanSablonCount = \App\Models\Pemesanan::where('perlu_sablon', true)
                ->whereIn('status_pemesanan', ['antrian_produksi', 'dalam_produksi'])
                ->whereHas('produksi', fn($q) => $q->where('status_sablon', '!=', 'selesai'))
                ->count();
            if ($antreanSablonCount === 0) {
                $antreanSablonCount = 3; // default prototype
            }

            $roleStaff = $user?->role ?? 'staff';
            $isRolePemotongan = in_array($roleStaff, ['pemotongan', 'pemotongan_kain']);
            $isRolePenjahitan = $roleStaff === 'penjahitan';
            $isRoleSteam = $roleStaff === 'steam';
            $isRoleSablon = $roleStaff === 'sablon';

            $antreanPotongCount = \App\Models\Produksi::where('tahap_saat_ini', 'pemotongan_kain')
                ->whereIn('status_produksi', ['antrian', 'proses'])->count();
            if ($antreanPotongCount === 0) $antreanPotongCount = 4;

            $antreanJahitCount = \App\Models\Produksi::where('tahap_saat_ini', 'penjahitan')
                ->whereIn('status_produksi', ['antrian', 'proses'])->count();
            if ($antreanJahitCount === 0) $antreanJahitCount = 2;

            $antreanSteamCount = \App\Models\Produksi::where('tahap_saat_ini', 'steam')
                ->whereIn('status_produksi', ['antrian', 'proses'])->count();
            if ($antreanSteamCount === 0) $antreanSteamCount = 2;

            $isDashboard = request()->routeIs('staff.dashboard');
            $isDetailPekerjaan = request()->routeIs('staff.detail-pekerjaan', 'staff.detail-pekerjaan.*');
            $isAntreanProduksi = request()->routeIs('staff.antrean-produksi', 'staff.antrean-produksi.*');
            $isAntreanSablon = request()->routeIs('staff.antrean-sablon', 'staff.antrean-sablon.*');
            $isRiwayatPekerjaan = request()->routeIs('staff.riwayat-pekerjaan', 'staff.riwayat-pekerjaan.*');
            $isRiwayatSaya = request()->routeIs('staff.riwayat-saya', 'staff.riwayat-saya.*');
            $isNotifikasi = request()->routeIs('staff.notifikasi', 'staff.notifikasi.*');
            $isProfil = request()->routeIs('staff.profil', 'staff.profil.*');
        @endphp

        {{-- ============================================================== --}}
        {{-- 1. SIDEBAR GELAP SOFT (SAMA SEPERTI SIDEBAR ADMIN)              --}}
        {{-- ============================================================== --}}
        <aside class="w-64 shrink-0 hidden lg:flex flex-col min-h-screen sticky top-0 h-screen z-30 border-r border-slate-800/80 shadow-2xl justify-between" style="background: linear-gradient(180deg, #0B0F19 0%, #111827 100%); width: 256px; min-width: 256px; max-width: 256px;">

            {{-- Bagian Atas: Logo & Navigasi --}}
            <div class="flex-1 flex flex-col min-h-0">
                {{-- Logo Header --}}
                <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800/80 shrink-0">
                    <div class="relative shrink-0 flex items-center justify-center rounded-full bg-black p-0.5 shadow-xs overflow-hidden border border-white/15" style="width: 38px; height: 38px; min-width: 38px; min-height: 38px;">
                        <img src="{{ asset('images/logo-cotton.jpg') }}" alt="COTTON.id" class="object-cover rounded-full w-full h-full block">
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <h1 class="text-sm font-black tracking-tight text-white leading-tight">COTTON.id</h1>
                        </div>
                        <p class="text-[11px] text-teal-400 font-semibold truncate">{{ auth()->user()?->nama_departemen ?? 'Portal Kerja Workshop' }}</p>
                    </div>
                </div>

                {{-- Navigasi Menu Sidebar --}}
                <div class="flex-1 overflow-y-auto px-4 pt-5 pb-4 space-y-6 custom-sidebar-scroll">

                    {{-- GROUP 1: TUGAS HARIAN --}}
                    <div>
                        <p class="px-2 text-[10px] font-bold text-slate-400 tracking-wider uppercase mb-2">TUGAS HARIAN</p>
                        <nav class="space-y-1">
                            {{-- 1. Dashboard Tugas --}}
                            <a href="{{ route('staff.dashboard') }}"
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition {{ $isDashboard ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                               @if($isDashboard) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isDashboard ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                                </svg>
                                <span>Dashboard Tugas</span>
                            </a>

                            {{-- 2. Detail Pekerjaan --}}
                            <a href="{{ route('staff.detail-pekerjaan') }}"
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition {{ $isDetailPekerjaan ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                               @if($isDetailPekerjaan) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isDetailPekerjaan ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                                <span>Detail Pekerjaan</span>
                            </a>

                            {{-- 3. Antrean Produksi --}}
                            <a href="{{ route('staff.antrean-produksi') }}"
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition {{ $isAntreanProduksi ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                               @if($isAntreanProduksi) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isAntreanProduksi ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                    <path d="m3.3 7 8.7 5 8.7-5"/>
                                    <path d="M12 22V12"/>
                                </svg>
                                <span>Antrean Produksi</span>
                            </a>

                            {{-- 4. MENU SPESIFIK DEPARTEMEN --}}
                            @if ($isRolePemotongan)
                                <a href="{{ route('staff.antrean-produksi', ['filter' => 'potong']) }}"
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition {{ ($isAntreanProduksi && request('filter') === 'potong') ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                                   @if($isAntreanProduksi && request('filter') === 'potong') style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ ($isAntreanProduksi && request('filter') === 'potong') ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="6" cy="6" r="3"/>
                                            <circle cx="6" cy="18" r="3"/>
                                            <line x1="20" y1="4" x2="8.12" y2="15.88"/>
                                            <line x1="14.47" y1="14.48" x2="20" y2="20"/>
                                            <line x1="8.12" y1="8.12" x2="12" y2="12"/>
                                        </svg>
                                        <span>Antrean Potong Kain</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/15 text-teal-300 border border-teal-500/30">
                                        {{ $antreanPotongCount }} SPK
                                    </span>
                                </a>
                            @elseif ($isRolePenjahitan)
                                <a href="{{ route('staff.antrean-produksi', ['filter' => 'jahit']) }}"
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition {{ ($isAntreanProduksi && request('filter') === 'jahit') ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                                   @if($isAntreanProduksi && request('filter') === 'jahit') style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ ($isAntreanProduksi && request('filter') === 'jahit') ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
                                        </svg>
                                        <span>Antrean Jahit</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/15 text-teal-300 border border-teal-500/30">
                                        {{ $antreanJahitCount }} SPK
                                    </span>
                                </a>
                            @elseif ($isRoleSteam)
                                <a href="{{ route('staff.antrean-produksi', ['filter' => 'steam']) }}"
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition {{ ($isAntreanProduksi && request('filter') === 'steam') ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                                   @if($isAntreanProduksi && request('filter') === 'steam') style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ ($isAntreanProduksi && request('filter') === 'steam') ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                        </svg>
                                        <span>Antrean Steam & QC</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/15 text-teal-300 border border-teal-500/30">
                                        {{ $antreanSteamCount }} SPK
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('staff.antrean-sablon') }}"
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition {{ $isAntreanSablon ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                                   @if($isAntreanSablon) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isAntreanSablon ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                        <span>Antrean Sablon</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/15 text-teal-300 border border-teal-500/30">
                                        {{ $antreanSablonCount }} Desain
                                    </span>
                                </a>
                            @endif
                        </nav>
                    </div>

                    {{-- GROUP 2: CATATAN & HISTORI --}}
                    <div>
                        <p class="px-2 text-[10px] font-bold text-slate-400 tracking-wider uppercase mb-2">CATATAN & HISTORI</p>
                        <nav class="space-y-1">
                            {{-- 5. Riwayat Pekerjaan --}}
                            <a href="{{ route('staff.riwayat-pekerjaan') }}"
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition {{ $isRiwayatPekerjaan ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                               @if($isRiwayatPekerjaan) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isRiwayatPekerjaan ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 14 14"/>
                                </svg>
                                <span>Riwayat Pekerjaan</span>
                            </a>

                            {{-- 6. Riwayat Saya --}}
                            <a href="{{ route('staff.riwayat-saya') }}"
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition {{ $isRiwayatSaya ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                               @if($isRiwayatSaya) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isRiwayatSaya ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <polyline points="16 11 18 13 22 9"/>
                                </svg>
                                <span>Riwayat Saya</span>
                            </a>
                        </nav>
                    </div>

                    {{-- GROUP 3: AKUN & BANTUAN --}}
                    <div>
                        <p class="px-2 text-[10px] font-bold text-slate-400 tracking-wider uppercase mb-2">AKUN & BANTUAN</p>
                        <nav class="space-y-1">
                            {{-- 7. Notifikasi Staff --}}
                            <a href="{{ route('staff.notifikasi') }}"
                               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition {{ $isNotifikasi ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                               @if($isNotifikasi) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isNotifikasi ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                    </svg>
                                    <span>Notifikasi Staff</span>
                                </div>
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-white/10 text-slate-300">
                                    {{ $unreadNotif }}
                                </span>
                            </a>

                            {{-- 8. Profil & Akun --}}
                            <a href="{{ route('staff.profil') }}"
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition {{ $isProfil ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}"
                               @if($isProfil) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 {{ $isProfil ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="5"/>
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                </svg>
                                <span>Profil & Akun</span>
                            </a>
                        </nav>
                    </div>

                </div>
            </div>

            {{-- Bagian Bawah: Keluar Sistem --}}
            <div class="p-4 border-t border-slate-800/80 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============================================================== --}}
        {{-- 2. DRAWER SIDEBAR MOBILE (Untuk layar HP/Tablet)               --}}
        {{-- ============================================================== --}}
        <div x-show="mobileSidebarOpen" x-cloak class="fixed inset-0 z-50 flex lg:hidden">
            <div @click="mobileSidebarOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity"></div>

            <aside class="relative flex w-64 flex-col min-h-screen z-10 border-r border-slate-800/80 shadow-2xl justify-between" style="background: linear-gradient(180deg, #0B0F19 0%, #111827 100%);">
                <div class="p-5 border-b border-slate-800/80 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0 flex items-center justify-center rounded-full bg-black p-0.5 border border-white/15 overflow-hidden" style="width: 36px; height: 36px;">
                            <img src="{{ asset('images/logo-cotton.jpg') }}" alt="COTTON.id" class="object-cover rounded-full w-full h-full block">
                        </div>
                        <div>
                            <div class="flex items-center gap-1">
                                <h1 class="text-sm font-black text-white">COTTON.id</h1>
                            </div>
                            <p class="text-[11px] text-teal-400 font-semibold truncate">{{ auth()->user()?->nama_departemen ?? 'Portal Workshop' }}</p>
                        </div>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="p-1.5 text-slate-400 hover:text-white rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4 custom-sidebar-scroll">
                    <div>
                        <p class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">TUGAS HARIAN</p>
                        <nav class="space-y-1">
                            <a href="{{ route('staff.dashboard') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs transition {{ $isDashboard ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                               @if($isDashboard) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <span>Dashboard Tugas</span>
                            </a>
                            <a href="{{ route('staff.detail-pekerjaan') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs transition {{ $isDetailPekerjaan ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                               @if($isDetailPekerjaan) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <span>Detail Pekerjaan</span>
                            </a>
                            <a href="{{ route('staff.antrean-produksi') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs transition {{ $isAntreanProduksi ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                               @if($isAntreanProduksi) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <span>Antrean Produksi</span>
                            </a>
                            @if ($isRolePemotongan)
                                <a href="{{ route('staff.antrean-produksi', ['filter' => 'potong']) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition {{ ($isAntreanProduksi && request('filter') === 'potong') ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                                   @if($isAntreanProduksi && request('filter') === 'potong') style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <span>Antrean Potong Kain</span>
                                    <span class="text-[10px] bg-teal-500/15 text-teal-300 border border-teal-500/30 font-bold px-1.5 py-0.5 rounded">{{ $antreanPotongCount }} SPK</span>
                                </a>
                            @elseif ($isRolePenjahitan)
                                <a href="{{ route('staff.antrean-produksi', ['filter' => 'jahit']) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition {{ ($isAntreanProduksi && request('filter') === 'jahit') ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                                   @if($isAntreanProduksi && request('filter') === 'jahit') style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <span>Antrean Jahit</span>
                                    <span class="text-[10px] bg-teal-500/15 text-teal-300 border border-teal-500/30 font-bold px-1.5 py-0.5 rounded">{{ $antreanJahitCount }} SPK</span>
                                </a>
                            @elseif ($isRoleSteam)
                                <a href="{{ route('staff.antrean-produksi', ['filter' => 'steam']) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition {{ ($isAntreanProduksi && request('filter') === 'steam') ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                                   @if($isAntreanProduksi && request('filter') === 'steam') style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <span>Antrean Steam & QC</span>
                                    <span class="text-[10px] bg-teal-500/15 text-teal-300 border border-teal-500/30 font-bold px-1.5 py-0.5 rounded">{{ $antreanSteamCount }} SPK</span>
                                </a>
                            @else
                                <a href="{{ route('staff.antrean-sablon') }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition {{ $isAntreanSablon ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                                   @if($isAntreanSablon) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                    <span>Antrean Sablon</span>
                                    <span class="text-[10px] bg-teal-500/15 text-teal-300 border border-teal-500/30 font-bold px-1.5 py-0.5 rounded">{{ $antreanSablonCount }} Desain</span>
                                </a>
                            @endif
                        </nav>
                    </div>

                    <div>
                        <p class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">CATATAN & HISTORI</p>
                        <nav class="space-y-1">
                            <a href="{{ route('staff.riwayat-pekerjaan') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs transition {{ $isRiwayatPekerjaan ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                               @if($isRiwayatPekerjaan) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <span>Riwayat Pekerjaan</span>
                            </a>
                            <a href="{{ route('staff.riwayat-saya') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs transition {{ $isRiwayatSaya ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                               @if($isRiwayatSaya) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <span>Riwayat Saya</span>
                            </a>
                        </nav>
                    </div>

                    <div>
                        <p class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">AKUN & BANTUAN</p>
                        <nav class="space-y-1">
                            <a href="{{ route('staff.notifikasi') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition {{ $isNotifikasi ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                               @if($isNotifikasi) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <span>Notifikasi Staff</span>
                                <span class="text-[10px] bg-white/10 text-slate-300 font-bold px-1.5 py-0.5 rounded">{{ $unreadNotif }}</span>
                            </a>
                            <a href="{{ route('staff.profil') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs transition {{ $isProfil ? 'font-bold text-white shadow-lg shadow-teal-950/60' : 'text-slate-300 hover:text-white hover:bg-white/5' }}"
                               @if($isProfil) style="background: linear-gradient(135deg, #0D9488 0%, #059669 100%);" @endif>
                                <span>Profil & Akun</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-800/80">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 px-3 py-2 rounded-xl text-rose-400 text-xs font-semibold hover:bg-rose-500/10 hover:text-rose-300">
                            <span>Keluar Sistem</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>

        {{-- ============================================================== --}}
        {{-- 3. AREA KONTEN & TOP HEADER SESUAI PROTOTYPE GAMBAR 2           --}}
        {{-- ============================================================== --}}
        <div class="flex-1 min-w-0 flex flex-col min-h-screen" style="flex: 1 1 0%; min-width: 0;">

            {{-- HEADER ATAS WORKSHOP (PERSIS GAMBAR 2) --}}
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-gray-200/80 bg-white px-4 lg:px-8 shrink-0">

                {{-- Sisi Kiri: Workshop Title & Shift Badge --}}
                <div class="flex items-center gap-3">
                    <button type="button" @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                        </svg>
                        <span class="text-xs font-black text-gray-900 tracking-tight whitespace-nowrap">{{ auth()->user()?->judul_workshop ?? 'Lantai Jahit & Workshop #01' }}</span>
                    </div>
                </div>

                {{-- Sisi Tengah: Search Bar Bulat Sesuai Gambar 2 --}}
                <div class="hidden md:flex items-center flex-1 max-w-sm mx-6">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>
                        <form method="GET" action="{{ route('staff.antrean-produksi') }}" class="w-full">
                            <input
                                type="text"
                                name="search"
                                placeholder="Cari nomor SPK, jenis baju..."
                                class="w-full rounded-full border border-gray-200 bg-gray-50/80 py-1.5 pl-9 pr-4 text-xs placeholder-gray-400 focus:border-black focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-black transition"
                            />
                        </form>
                    </div>
                </div>

                {{-- Sisi Kanan: Tanggal + Notifikasi Bell + User Profile --}}
                <div class="flex items-center gap-4">
                    {{-- Tanggal Realtime (Menyesuaikan Hari Ini & Terupdate Realtime) --}}
                    <div
                        x-data="{
                            currentDate: '',
                            updateDate() {
                                const now = new Date();
                                const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                const dayName = days[now.getDay()];
                                const dateNum = String(now.getDate()).padStart(2, '0');
                                const monthName = months[now.getMonth()];
                                const year = now.getFullYear();
                                this.currentDate = `${dayName}, ${dateNum} ${monthName} ${year}`;
                            }
                        }"
                        x-init="updateDate(); setInterval(() => updateDate(), 1000)"
                        class="hidden lg:flex items-center gap-1.5 text-xs font-medium text-gray-600 shrink-0"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        <span class="whitespace-nowrap" x-text="currentDate">{{ now()->translatedFormat('D, d M Y') }}</span>
                    </div>

                    {{-- Notifikasi Bell --}}
                    <a href="{{ route('staff.notifikasi') }}" class="relative p-1.5 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100 transition" title="Notifikasi Staff">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        @if($unreadNotif > 0)
                            <span class="absolute top-1 right-1 h-1.5 w-1.5 rounded-full bg-teal-500"></span>
                        @endif
                    </a>

                    {{-- Profil Avatar & Nama --}}
                    <a href="{{ route('staff.profil') }}" class="flex items-center gap-2 group shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-black text-white text-xs font-bold shadow-2xs overflow-hidden border border-gray-200 shrink-0" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                            <img src="{{ asset('images/logo-cotton.jpg') }}" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-gray-900 leading-tight">
                                {{ auth()->user()->name ?? 'Budi Santoso' }}
                            </p>
                            <p class="text-[10px] text-gray-400 font-medium">
                                {{ auth()->user()?->jabatan ?? 'Operator Produksi' }}
                            </p>
                        </div>
                    </a>
                </div>
            </header>

            {{-- KONTEN UTAMA --}}
            <main class="flex-1 p-4 lg:p-8 min-w-0 w-full" style="width: 100%; box-sizing: border-box;">
                <div class="max-w-7xl mx-auto w-full" style="width: 100%;">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
