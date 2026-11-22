<div class="space-y-6">

    {{-- ALERT SUKSES --}}
    @if ($pesanSukses)
        <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200/80 flex items-center justify-between gap-3 text-teal-800 text-xs font-semibold shadow-xs">
            <div class="flex items-center gap-2.5 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-600 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="9 11 12 14 22 4"/>
                </svg>
                <span class="truncate">{{ $pesanSukses }}</span>
            </div>
            <button wire:click="$set('pesanSukses', '')" class="text-teal-600 hover:text-teal-900 cursor-pointer p-1 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- 1. HEADER HALAMAN & ACTIONS SESUAI SCREENSHOT 4 --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="min-w-0">
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 mb-1">
                <span class="whitespace-nowrap">LANTAI PRODUKSI</span>
                <span>•</span>
                <span class="whitespace-nowrap">STASIUN #01</span>
                <span>•</span>
                <span class="text-teal-700 flex items-center gap-1 whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600 animate-pulse shrink-0"></span>
                    Siaran Langsung
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug">
                Notifikasi & Pesan Kerja Workshop
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1 leading-normal max-w-2xl">
                Instruksi operasional harian, peringatan batas waktu pesanan, dan konfirmasi kesiapan bahan untuk staf penjahit & sablon.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <button wire:click="tandaiSemuaDibaca"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs font-bold hover:bg-slate-50 transition shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500 shrink-0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span>Tandai Semua Sudah Dibaca</span>
            </button>

            <button class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer whitespace-nowrap shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 shrink-0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span>Pengaturan Bel Notifikasi</span>
            </button>
        </div>
    </div>

    {{-- 2. CATEGORY PILLS ROW SESUAI SCREENSHOT 4 --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1.5 no-scrollbar">
        <button wire:click="$set('filterTipe', 'semua')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap shrink-0 transition cursor-pointer {{ $filterTipe === 'semua' ? 'bg-black text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            <span>Semua Notifikasi</span>
            <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $filterTipe === 'semua' ? 'bg-zinc-800 text-white' : 'bg-slate-200 text-slate-700' }}">4</span>
        </button>

        <button wire:click="$set('filterTipe', 'pesanan_baru')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap shrink-0 transition cursor-pointer {{ $filterTipe === 'pesanan_baru' ? 'bg-black text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 inline-block mr-1 shrink-0"></span>
            <span>Pesanan Baru Masuk</span>
            <span class="ml-1 text-[10px] text-slate-500 font-medium">1</span>
        </button>

        <button wire:click="$set('filterTipe', 'tahap_siap')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap shrink-0 transition cursor-pointer {{ $filterTipe === 'tahap_siap' ? 'bg-black text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500 inline-block mr-1 shrink-0"></span>
            <span>Kesiapan Tahap Kerja</span>
            <span class="ml-1 text-[10px] text-slate-500 font-medium">2</span>
        </button>

        <button wire:click="$set('filterTipe', 'deadline')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap shrink-0 transition cursor-pointer {{ $filterTipe === 'deadline' ? 'bg-black text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block mr-1 shrink-0"></span>
            <span>Peringatan Batas Waktu</span>
            <span class="ml-1 text-[10px] text-slate-500 font-medium">1</span>
        </button>
    </div>

    {{-- 3. GRID 2 KOLOM: DAFTAR NOTIFIKASI (8/12) & SIDEBAR WIDGETS (4/12) SESUAI SCREENSHOT 4 --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- KOLOM KIRI (8/12): DAFTAR KARTU NOTIFIKASI --}}
        <div class="lg:col-span-8 space-y-4 min-w-0">

            {{-- NOTIFIKASI 1: MENDESAK TENGGAT WAKTU (RED BORDER) --}}
            <div class="bg-white rounded-2xl p-5 border-l-4 border-l-rose-500 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-rose-100 text-rose-800 whitespace-nowrap shrink-0">
                            Tenggat Waktu
                        </span>
                        <span class="text-xs font-black text-slate-900 whitespace-nowrap">SPK #ORD-2026-001</span>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">18 menit yang lalu</span>
                </div>

                <div class="flex items-start gap-3.5 sm:gap-4">
                    {{-- Status Badge Kotak Berbatas Ukuran Eksplisit --}}
                    <div class="rounded-xl bg-rose-50 text-rose-600 flex flex-col items-center justify-center shrink-0 border border-rose-200/70 p-1 overflow-hidden" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; max-width: 48px; max-height: 48px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-600 shrink-0 block" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        </svg>
                        <span class="text-[8px] font-extrabold uppercase mt-0.5 tracking-tight text-center leading-none whitespace-nowrap block" style="font-size: 8px; line-height: 9px;">Mendesak</span>
                    </div>

                    <div class="space-y-1 flex-1 min-w-0">
                        <h3 class="text-sm font-black text-slate-900 leading-snug">
                            Tenggat Waktu SPK ORD-2026-001 Tinggal 2 Hari
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pesanan Kaos Combed 30s Hitam (30 Pcs) ditargetkan selesai pemotongan siang ini agar tim sablon bisa mencetak tepat waktu.
                        </p>

                        <div class="flex items-center gap-2 sm:gap-3 text-[11px] text-slate-500 pt-1 flex-wrap font-medium">
                            <span class="whitespace-nowrap">Meja Potong 02</span>
                            <span>•</span>
                            <span class="whitespace-nowrap">30 Pcs (S:10, M:10, L:10)</span>
                            <span>•</span>
                            <span class="text-rose-600 font-bold whitespace-nowrap">! Sisa Waktu: 48 Jam</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3 flex-wrap">
                    <a href="{{ route('staff.detail-pekerjaan') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer whitespace-nowrap">
                        <span>Buka Lembar Pekerjaan</span>
                        <span>→</span>
                    </a>
                    <button class="text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer whitespace-nowrap">
                        Sembunyikan
                    </button>
                </div>
            </div>

            {{-- NOTIFIKASI 2: SIAP JAHIT (TEAL BORDER) --}}
            <div class="bg-white rounded-2xl p-5 border-l-4 border-l-teal-500 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-teal-100 text-teal-800 whitespace-nowrap shrink-0">
                            Tahap #2: Penjahitan
                        </span>
                        <span class="text-xs font-black text-slate-900 whitespace-nowrap">SPK #ORD-2026-003</span>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">42 menit yang lalu</span>
                </div>

                <div class="flex items-start gap-3.5 sm:gap-4">
                    {{-- Status Badge Kotak Berbatas Ukuran Eksplisit --}}
                    <div class="rounded-xl bg-teal-50 text-teal-700 flex flex-col items-center justify-center shrink-0 border border-teal-200/70 p-1 overflow-hidden" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; max-width: 48px; max-height: 48px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600 shrink-0 block" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23z"/>
                        </svg>
                        <span class="text-[8px] font-extrabold uppercase mt-0.5 tracking-tight text-center leading-none whitespace-nowrap block" style="font-size: 8px; line-height: 9px;">Siap Jahit</span>
                    </div>

                    <div class="space-y-1 flex-1 min-w-0">
                        <h3 class="text-sm font-black text-slate-900 leading-snug">
                            Pola Kaos Polo ORD-2026-003 Selesai Dipotong – Siap Masuk Penjahitan
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Potongan pola badan dan rib selesai dari meja potong, siap dirakit oleh regu jahit ke tahap penjahitan.
                        </p>

                        <div class="flex items-center gap-2 sm:gap-3 text-[11px] text-slate-500 pt-1 flex-wrap font-medium">
                            <span class="whitespace-nowrap">Meja Jahit Line A</span>
                            <span>•</span>
                            <span class="text-teal-700 font-bold whitespace-nowrap">✓ Pola Potong Lengkap (100%)</span>
                            <span>•</span>
                            <span class="whitespace-nowrap">Alur: Jahit → Steam → Packing</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3 flex-wrap">
                    <a href="{{ route('staff.antrean-produksi') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-teal-700 text-white text-xs font-bold hover:bg-teal-800 transition shadow-xs cursor-pointer whitespace-nowrap">
                        <span>Mulai Menjahit</span>
                        <span>▷</span>
                    </a>
                    <button class="text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer whitespace-nowrap">
                        Nanti
                    </button>
                </div>
            </div>

            {{-- NOTIFIKASI 3: AMBANG SABLON TERCAPAI (BLUE BORDER) --}}
            <div class="bg-white rounded-2xl p-5 border-l-4 border-l-blue-500 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 text-blue-800 whitespace-nowrap shrink-0">
                            Ambang Sablon Tercapai
                        </span>
                        <span class="text-xs font-black text-slate-900 whitespace-nowrap">Desain Typo Minimalis</span>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">1 jam yang lalu</span>
                </div>

                <div class="flex items-start gap-3.5 sm:gap-4">
                    {{-- Status Badge Kotak Berbatas Ukuran Eksplisit --}}
                    <div class="rounded-xl bg-blue-50 text-blue-700 flex flex-col items-center justify-center shrink-0 border border-blue-200/70 p-1 overflow-hidden" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; max-width: 48px; max-height: 48px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0 block" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                        <span class="text-[8px] font-extrabold uppercase mt-0.5 tracking-tight text-center leading-none whitespace-nowrap block" style="font-size: 8px; line-height: 9px;">Cetak Siap</span>
                    </div>

                    <div class="space-y-1 flex-1 min-w-0">
                        <h3 class="text-sm font-black text-slate-900 leading-snug">
                            Info Sablon: Desain Typo Minimalis Sudah Cukup Kuota (8 Pcs)
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pesanan terkumpul 8 potong, melebihi batas hemat 5 potong. Meja cetak sablon sudah siap dioperasikan.
                        </p>

                        <div class="flex items-center gap-2 sm:gap-3 text-[11px] text-slate-500 pt-1 flex-wrap font-medium">
                            <span class="whitespace-nowrap">Tinta Plastisol Hitam Doff</span>
                            <span>•</span>
                            <span class="text-teal-700 font-bold whitespace-nowrap">8 / 5 Pcs (Optimal)</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3 flex-wrap">
                    <a href="{{ route('staff.antrean-sablon') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold transition shadow-2xs cursor-pointer whitespace-nowrap">
                        <span>Cek Meja Sablon</span>
                        <span>⎙</span>
                    </a>
                    <button class="text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer whitespace-nowrap">
                        Arsipkan
                    </button>
                </div>
            </div>

            {{-- NOTIFIKASI 4: SPK BARU MASUK (GRAY BORDER) --}}
            <div class="bg-white rounded-2xl p-5 border-l-4 border-l-slate-400 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-slate-100 text-slate-700 whitespace-nowrap shrink-0">
                            Pesanan Baru Masuk
                        </span>
                        <span class="text-xs font-black text-slate-900 whitespace-nowrap">SPK #ORD-2026-005</span>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">2 jam yang lalu</span>
                </div>

                <div class="flex items-start gap-3.5 sm:gap-4">
                    {{-- Status Badge Kotak Berbatas Ukuran Eksplisit --}}
                    <div class="rounded-xl bg-slate-100 text-slate-700 flex flex-col items-center justify-center shrink-0 border border-slate-200/70 p-1 overflow-hidden" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; max-width: 48px; max-height: 48px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500 shrink-0 block" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        </svg>
                        <span class="text-[8px] font-extrabold uppercase mt-0.5 tracking-tight text-center leading-none whitespace-nowrap block" style="font-size: 8px; line-height: 9px;">Tahap #1</span>
                    </div>

                    <div class="space-y-1 flex-1 min-w-0">
                        <h3 class="text-sm font-black text-slate-900 leading-snug">
                            SPK Baru: ORD-2026-005 (Jaket Parasut 50 Pcs) Masuk Antrean Pemotongan
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            50 pcs jaket telah dijadwalkan masuk meja potong untuk shift kerja besok pagi sebelum dialirkan ke regu jahit.
                        </p>

                        <div class="flex items-center gap-2 sm:gap-3 text-[11px] text-slate-500 pt-1 flex-wrap font-medium">
                            <span class="whitespace-nowrap">Mulai Potong: Besok, 08:30 WIB</span>
                            <span>•</span>
                            <span class="whitespace-nowrap">Target: 50 Pcs Jaket</span>
                            <span>•</span>
                            <span class="whitespace-nowrap">Tahap 1 dari 4 Pabrik</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3 flex-wrap">
                    <a href="{{ route('staff.antrean-produksi') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold transition shadow-2xs cursor-pointer whitespace-nowrap">
                        <span>Lihat Rincian</span>
                        <span>👁</span>
                    </a>
                    <button class="text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer whitespace-nowrap">
                        Mengerti
                    </button>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (4/12): SIDEBAR WIDGETS SESUAI SCREENSHOT 4 --}}
        <div class="lg:col-span-4 space-y-5 min-w-0">

            {{-- 1. PERGANTIAN SHIFT WIDGET --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">PERGANTIAN SHIFT</span>
                    <span class="text-lg sm:text-xl font-black text-slate-900">16:30 <span class="text-xs font-bold text-slate-500">WIB</span></span>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-800 whitespace-nowrap shrink-0">
                    1 Jam 45 Menit Lagi
                </span>
            </div>

            {{-- 2. PENGINGAT SHIFT KERJA CARD --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-teal-600 font-bold shrink-0">⏱</span>
                    <h3 class="text-sm font-black text-slate-900 truncate">Pengingat Shift Kerja</h3>
                </div>

                <div class="p-3 rounded-xl bg-teal-50/70 border border-teal-200/60 space-y-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-bold text-teal-900 whitespace-nowrap">Pergantian Shift Sore</span>
                        <span class="text-xs font-black text-teal-900 whitespace-nowrap">16:30 WIB</span>
                    </div>
                    <p class="text-[11px] text-teal-800 leading-relaxed">
                        Tolong rapikan meja kerja, matikan saklar mesin obras/jarum 1, dan pastikan form serah terima SPK telah ditandatangani sebelum jam pulang.
                    </p>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-600 gap-2">
                        <span class="flex items-center gap-1.5 min-w-0">
                            <span class="w-2 h-2 rounded-full bg-teal-600 shrink-0"></span>
                            <span class="truncate">Target Terpenuhi Hari Ini</span>
                        </span>
                        <span class="font-bold text-slate-900 whitespace-nowrap shrink-0">88 Pcs / 100 Pcs</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600 gap-2">
                        <span class="flex items-center gap-1.5 min-w-0">
                            <span class="w-2 h-2 rounded-full bg-slate-400 shrink-0"></span>
                            <span class="truncate">Operator On-Duty</span>
                        </span>
                        <span class="font-bold text-slate-900 whitespace-nowrap shrink-0">6 Penjahit • 2 Sablon</span>
                    </div>
                </div>

                <button class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-600 shrink-0" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    </svg>
                    <span>Buka Log Serah Terima Shift</span>
                </button>
            </div>

            {{-- 3. ALUR 4 TAHAPAN PRODUKSI CARD --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3.5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ALUR 4 TAHAPAN PRODUKSI</span>
                    <span class="text-teal-600 font-bold shrink-0">⇄</span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 space-y-0.5">
                        <span class="font-bold text-slate-900 block truncate">1 Pemotongan</span>
                        <span class="text-[10px] text-slate-400 block truncate">Pola kain disiapkan</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-teal-50 border border-teal-200 space-y-0.5">
                        <span class="font-bold text-teal-900 block truncate">2 Penjahitan</span>
                        <span class="text-[10px] text-teal-700 block truncate">Perakitan garmen</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 space-y-0.5">
                        <span class="font-bold text-slate-900 block truncate">3 Steam</span>
                        <span class="text-[10px] text-slate-400 block truncate">Finishing & kerapian</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 space-y-0.5">
                        <span class="font-bold text-slate-900 block truncate">4 Sablon & Pack</span>
                        <span class="text-[10px] text-slate-400 block truncate">Cetak & kemas akhir</span>
                    </div>
                </div>
            </div>

            {{-- 4. PANDUAN KODE LABEL CARD --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">PANDUAN KODE LABEL</span>
                    <span class="text-slate-400 text-xs shrink-0">ⓘ</span>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-start gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mt-1 shrink-0"></span>
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 block">Tenggat Segera (&lt; 2 Hari)</span>
                            <span class="text-[11px] text-slate-500 block">Prioritaskan urutan jahit nomor satu.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-600 mt-1 shrink-0"></span>
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 block">Pola & Antrean Siap</span>
                            <span class="text-[11px] text-slate-500 block">Pola potong siap dikerjakan oleh regu jahit.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-500 mt-1 shrink-0"></span>
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 block">Ambang Minimal Cetak</span>
                            <span class="text-[11px] text-slate-500 block">Cukup kuota afdruk untuk menghemat screen.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. KEBERSIHAN WORKSHOP CARD --}}
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-1.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">KEBERSIHAN WORKSHOP</span>
                <h4 class="text-xs font-black text-slate-900">5R Terjaga, Hasil Prima</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Ringkas, Rapi, Resik, Rawat, Rajin. Lindungi kain katun putih dari oli mesin.
                </p>
                <span class="text-[9px] font-mono text-slate-400 font-bold block pt-1">
                    STANDAR OPERASIONAL 2026
                </span>
            </div>

        </div>

    </div>

</div>
