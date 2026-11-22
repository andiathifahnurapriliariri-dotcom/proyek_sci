<div class="space-y-5 max-w-4xl">

    {{-- 1. HEADER PROFILE & STATUS PERANGKAT SESUAI SCREENSHOT 2 --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M7 7h10M7 12h10M7 17h6"/>
                </svg>
                <span>IDENTITAS STAF LANTAI PRODUKSI</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Profil Akun & Pengaturan Kerja Staf
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Informasi identitas operator workshop, pembagian stasiun kerja, hak akses operasional, serta pengaturan kenyamanan perangkat kerja.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 shadow-2xs shrink-0">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-bold text-slate-700">Status Perangkat:</span>
            <span class="text-xs font-medium text-slate-500">Terminal Workshop #01 — Terhubung</span>
        </div>
    </div>

    {{-- ALERT SUKSES JIKA ADA --}}
    @if ($pesanSuksesProfil)
        <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200 text-teal-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <span>{{ $pesanSuksesProfil }}</span>
            <button wire:click="$set('pesanSuksesProfil', '')" class="text-teal-600 hover:text-teal-900">✕</button>
        </div>
    @endif

    {{-- 2. USER PROFILE BANNER CARD SESUAI SCREENSHOT 2 --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center sm:items-start gap-5">
        <div class="relative shrink-0">
            <div class="w-20 h-20 rounded-2xl bg-slate-900 border-2 border-slate-200 overflow-hidden shadow-xs">
                <img src="{{ asset('images/logo-cotton.jpg') }}" alt="Budi Santoso" class="w-full h-full object-cover">
            </div>
            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold border-2 border-white shadow-xs">
                ✓
            </div>
        </div>

        <div class="text-center sm:text-left space-y-1 flex-1">
            <div class="flex items-center justify-center sm:justify-start gap-2.5 flex-wrap">
                <h2 class="text-lg sm:text-xl font-black text-slate-900">{{ auth()->user()->name }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                    Shift Pagi
                </span>
            </div>

            <p class="text-xs text-slate-500 font-bold">
                {{ auth()->user()->jabatan }}
            </p>

            <div class="pt-1 flex items-center justify-center sm:justify-start gap-3 text-xs text-slate-500 font-medium">
                <span class="flex items-center gap-1 font-mono">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                    </svg>
                    <span>STF-PRD-024</span>
                </span>
                <span>•</span>
                <span class="text-teal-700 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                    Aktif Sejak 2022
                </span>
            </div>
        </div>
    </div>

    {{-- 3. CARD STASIUN PENUGASAN HARI INI SESUAI SCREENSHOT 2 --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="6" cy="6" r="3"/>
                <circle cx="6" cy="18" r="3"/>
                <line x1="20" y1="4" x2="8.12" y2="15.88"/>
            </svg>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">STASIUN PENUGASAN HARI INI</span>
            <h3 class="text-sm font-black text-slate-900 mt-0.5">
                {{ auth()->user()->stasiun_kerja ?: auth()->user()->nama_meja_kerja }} ({{ auth()->user()->nama_departemen }})
            </h3>
            <p class="text-xs text-slate-500 font-medium mt-1">
                {{ auth()->user()->judul_workshop }} — Jalur Produksi Pakaian Jadi
            </p>
        </div>
    </div>

    {{-- 4. CARD JADWAL SHIFT KERJA SESUAI SCREENSHOT 2 --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 14 14"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">JADWAL SHIFT KERJA</span>
                <h3 class="text-base font-black text-slate-900 mt-0.5">
                    08:00 – 17:00 WIB
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1.5">
                    <span class="text-teal-700 font-bold">☕</span>
                    <span>Istirahat Siang: 12:00 – 13:00 WIB</span>
                </p>
            </div>
        </div>

        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 self-start sm:self-auto">
            Durasi: 8.5 Jam
        </span>
    </div>

    {{-- 5. CARD OUTPUT POTONG MINGGU INI (92%) SESUAI SCREENSHOT 2 --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 block">Output Potong Minggu Ini</span>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">1.480</span>
                <span class="text-xs font-bold text-slate-500">Pcs Kain</span>
            </div>
        </div>

        {{-- Circular Progress Ring 92% Sesuai Screenshot 2 --}}
        <div class="relative w-14 h-14 flex items-center justify-center">
            <svg class="w-14 h-14 transform -rotate-90" viewBox="0 0 36 36">
                <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                <path class="text-teal-700" stroke-dasharray="92, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
            </svg>
            <span class="absolute text-xs font-black text-slate-900">92%</span>
        </div>
    </div>

    {{-- 6. CARD CAKUPAN WEWENANG STAF PRODUKSI (RBAC LEVEL 1) SESUAI SCREENSHOT 2 --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                    </svg>
                </div>
                <h3 class="text-sm font-black text-slate-900">Cakupan Wewenang Staf Produksi</h3>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                RBAC Level 1
            </span>
        </div>

        <p class="text-xs text-slate-600 leading-relaxed">
            Sebagai Staf Produksi, Anda bertugas mencatat waktu mulai dan selesai pengerjaan pada 4 tahapan berurutan (Pemotongan, Penjahitan, Steam, serta Sablon & Packing). Sistem ini berfokus pada pengerjaan pakaian jadi, tidak mencakup pengadaan atau pengelolaan stok bahan baku mentah. Data komersial dan keuangan dikelola oleh Admin Kantor.
        </p>
    </div>

    {{-- 7. BIG RED LOGOUT BUTTON SESUAI SCREENSHOT 2 --}}
    <form method="POST" action="{{ route('logout') }}" class="pt-2">
        @csrf
        <button type="submit"
                class="w-full py-3.5 px-6 rounded-2xl bg-[#B91C1C] hover:bg-red-800 text-white font-bold text-xs sm:text-sm transition flex items-center justify-center gap-2 shadow-sm cursor-pointer active:scale-99">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Keluar dari Sistem (Logout)</span>
        </button>
    </form>

</div>
