<div class="space-y-6">

    {{-- 1. HEADER PROFILE CARD OPERATOR SESUAI SCREENSHOT 3 --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        <div class="flex items-start sm:items-center gap-4 min-w-0">
            <div class="relative shrink-0" style="width: 64px; height: 64px; min-width: 64px; min-height: 64px;">
                <div class="w-16 h-16 rounded-2xl bg-slate-900 border-2 border-slate-200 overflow-hidden shrink-0 shadow-xs" style="width: 64px; height: 64px; min-width: 64px; min-height: 64px; max-width: 64px; max-height: 64px;">
                    <img src="{{ asset('images/logo-cotton.jpg') }}" alt="Budi Santoso" class="w-full h-full object-cover shrink-0 block" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold border-2 border-white shadow-xs shrink-0" style="width: 20px; height: 20px;">
                    ✓
                </div>
            </div>

            <div class="space-y-1 min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-black text-white whitespace-nowrap shrink-0">
                        OPERATOR GRADE A
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 whitespace-nowrap shrink-0">
                        ID #OPR-8841
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/50 whitespace-nowrap shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                        Shift Pagi Aktif
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug">
                    Catatan Kerja Saya ({{ auth()->user()->name }})
                </h1>

                <p class="text-xs text-slate-500 font-medium max-w-2xl leading-relaxed">
                    Daftar tugas pemotongan pola dan inspeksi kain yang telah Anda selesaikan secara pribadi di stasiun meja potong selama bertugas di shift kerja. Tetap konsisten dan utamakan presisi pola kain.
                </p>
            </div>
        </div>

        {{-- Button Unduh Kanan --}}
        <div class="shrink-0 flex flex-col items-start lg:items-end gap-1">
            <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span>Unduh Laporan Kerja Pribadi</span>
            </button>
            <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1">
                <span class="text-emerald-600 font-bold">✓</span> Tervalidasi Supervisor
            </span>
        </div>
    </div>

    {{-- 2. EMPAT KARTU METRIK KINERJA OPERATOR SESUAI SCREENSHOT 3 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Volume Produksi --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">VOLUME PRODUKSI</span>
                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2a3 3 0 0 0-3 3c0 1.3.8 2.4 2 2.8V9L3.5 15.5A2 2 0 0 0 5 19h14a2 2 0 0 0 1.5-3.5L13 9V7.8A3 3 0 0 0 12 2Z"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-black text-slate-900">40</span>
                    <span class="text-xs font-bold text-slate-500">Pcs</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Baju Yang Saya Tangani Shift Ini</p>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] font-bold text-teal-700 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="18 15 12 9 6 15"/>
                </svg>
                <span>Target shift 45 pcs (89% tercapai)</span>
            </div>
        </div>

        {{-- Card 2: Disiplin Waktu --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">DISIPLIN WAKTU</span>
                <div class="w-7 h-7 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 14 14"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-black text-slate-900">98.4</span>
                    <span class="text-xs font-bold text-slate-500">%</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Ketepatan Waktu Kerja</p>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] font-bold text-teal-700 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span>Tepat Waktu (+1.2% dari minggu lalu)</span>
            </div>
        </div>

        {{-- Card 3: Standar Kualitas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">STANDAR KUALITAS</span>
                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-black text-slate-900">99.1</span>
                    <span class="text-xs font-bold text-slate-500">%</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Status Lolos QC Potong Pola</p>
            </div>
            <div class="pt-2 border-t border-slate-100 text-[11px] font-bold text-teal-700 flex items-center gap-1">
                <span>☆</span>
                <span>Rapi & Sempurna (0 Cacat Kain)</span>
            </div>
        </div>

        {{-- Card 4: Durasi Kerja (Black Card Sesuai Screenshot 3) --}}
        <div class="bg-black text-white rounded-2xl p-5 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">DURASI KERJA</span>
                <div class="w-7 h-7 rounded-lg bg-zinc-800 flex items-center justify-center text-zinc-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 14 14"/>
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black text-white leading-tight">6j 15m</div>
                <p class="text-xs text-zinc-400 font-medium mt-0.5">Total Jam Kerja Hari Ini (Aktif)</p>
            </div>
            <div class="space-y-1.5 pt-1">
                <div class="w-full bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-teal-400 h-full rounded-full" style="width: 74%;"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] font-bold text-zinc-400">
                    <span class="text-teal-300 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                        Sedang Berjalan
                    </span>
                    <span>Sisa Shift: ~2j 15m</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. FILTER TABS & SEARCH SECTION SESUAI SCREENSHOT 3 --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center p-1 rounded-xl bg-slate-100 border border-slate-200/80">
            <button wire:click="setFilter('hari_ini')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $activeFilter === 'hari_ini' ? 'bg-black text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                <span>Tugas Hari Ini</span>
                <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $activeFilter === 'hari_ini' ? 'bg-zinc-800 text-white' : 'bg-slate-200 text-slate-700' }}">2</span>
            </button>
            <button wire:click="setFilter('minggu_ini')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $activeFilter === 'minggu_ini' ? 'bg-black text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                <span>Minggu Ini</span>
                <span class="ml-1 text-[10px] font-medium text-slate-500">14</span>
            </button>
            <button wire:click="setFilter('bulan_ini')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $activeFilter === 'bulan_ini' ? 'bg-black text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                <span>Bulan Ini</span>
                <span class="ml-1 text-[10px] font-medium text-slate-500">58</span>
            </button>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="relative flex-1 sm:w-60">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari tugas/kain..."
                       class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-white focus:outline-hidden focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition text-slate-800 placeholder-slate-400 font-medium shadow-2xs">
            </div>

            <select wire:model.live="filterKategori"
                    class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-slate-200 bg-white focus:outline-hidden focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition cursor-pointer shadow-2xs">
                <option value="semua">Filter Kategori: Semua</option>
                <option value="kaos">Kaos Combed</option>
                <option value="polo">Polo Shirt</option>
                <option value="hoodie">Hoodie Fleece</option>
            </select>

            <button class="p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 transition shadow-2xs cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                    <path d="M16 21h5v-5"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- 4. TABEL RINCIAN TUGAS PRIBADI SESUAI SCREENSHOT 3 --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    TABEL RINCIAN TUGAS PRIBADI {{ strtoupper(auth()->user()->name) }}
                </h3>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500 font-medium">3 Catatan Tercatat</span>
            </div>
            <span class="text-xs text-slate-400 font-medium">Pembaruan Terakhir: 10:45 WIB</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-5 py-3.5">WAKTU KERJA</th>
                        <th class="px-5 py-3.5">NOMOR SPK</th>
                        <th class="px-5 py-3.5">DETAIL PRODUK & BATCH</th>
                        <th class="px-5 py-3.5">TINDAKAN / AKSI KERJA</th>
                        <th class="px-5 py-3.5">STATUS & HASIL</th>
                        <th class="px-5 py-3.5 text-right">OPSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- Row 1: ORD-2026-000 Selesai Rapi --}}
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2 font-black text-slate-900 text-xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>08:30 – 10:15 WIB</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium pl-4 block mt-0.5">Shift Pagi • 1j 45m</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-black text-slate-900 text-xs block">ORD-2026-000</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                    ✂
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block text-xs">Kaos Raglan Combed 24s</span>
                                    <span class="text-[10px] text-slate-500 font-medium">Volume: <strong>40 Pcs</strong> • Ukuran: M/L</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 block text-xs">Potong Pola Raglan & Lengan Presisi (Meja 01)</span>
                            <span class="text-[11px] text-slate-500 leading-relaxed block mt-0.5">
                                Pemotongan pola badan, pola lengan raglan, & rib leher selesai presisi
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/60">
                                <span>✓</span> Selesai Rapi
                            </span>
                            <span class="text-[10px] text-teal-800 font-bold block mt-1">100% Lolos Inspeksi Pola</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('staff.detail-pekerjaan') }}" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg inline-block transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        </td>
                    </tr>

                    {{-- Row 2: ORD-2026-001 Aktif Dikerjakan --}}
                    <tr class="hover:bg-slate-50/70 transition bg-teal-50/10">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2 font-black text-slate-900 text-xs">
                                <span class="w-2 h-2 rounded-full bg-teal-600 animate-pulse"></span>
                                <span>10:30 – Sekarang</span>
                            </div>
                            <span class="text-[10px] text-teal-700 font-bold pl-4 block mt-0.5">Aktif Dikerjakan</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-black text-slate-900 text-xs block">ORD-2026-001</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-teal-100 flex items-center justify-center text-teal-700 shrink-0">
                                    ✂
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block text-xs">Kaos Combed 30s Hitam</span>
                                    <span class="text-[10px] text-slate-500 font-medium">Volume: <strong>30 Pcs</strong> • Ukuran: M/L/XL</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 block text-xs">Potong Pola Kaos Oblong O-Neck (Meja 02)</span>
                            <span class="text-[11px] text-slate-500 leading-relaxed block mt-0.5">
                                Mesin potong vertikal KM 8-inch, pisau baru diasah, pemotongan pola badan & lengan
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="space-y-1 max-w-[130px]">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-700">
                                    <span>Progres 40%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-black h-full rounded-full" style="width: 40%;"></div>
                                </div>
                                <span class="text-[10px] text-slate-500 font-medium block">(Sedang Berjalan)</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button class="px-3 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold transition shadow-2xs">
                                Update Pcs
                            </button>
                        </td>
                    </tr>

                    {{-- Row 3: ORD-2025-990 Tuntas 100% --}}
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2 font-bold text-slate-700 text-xs">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Kemarin 14:00 – 17:00 WIB</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium pl-4 block mt-0.5">Shift Sore • 2j 30m</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-black text-slate-900 text-xs block">ORD-2025-990</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                                    ✂
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block text-xs">Hoodie Baby Terry</span>
                                    <span class="text-[10px] text-slate-500 font-medium">Volume: <strong>50 Pcs</strong> • Misty Grey</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 block text-xs">Gelar & Potong Pola Hoodie (Meja 01)</span>
                            <span class="text-[11px] text-slate-500 leading-relaxed block mt-0.5">
                                Potong pola badan hoodie, saku kantong doraemon, kupluk & rib
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                <span>✓</span> Tuntas 100%
                            </span>
                            <span class="text-[10px] text-slate-500 block mt-1">Diserahkan ke Lini Penjahitan</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('staff.detail-pekerjaan') }}" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg inline-block transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <span class="text-slate-500 font-medium">
                Menampilkan 3 dari total <strong class="text-slate-900">3</strong> tugas yang dikerjakan langsung oleh {{ auth()->user()->name }}
            </span>

            <div class="flex items-center gap-1.5">
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">
                    Sebelumnya
                </button>
                <button class="w-8 h-8 rounded-lg bg-black text-white font-bold text-xs">
                    1
                </button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold transition">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>

    {{-- 5. CATATAN STANDAR OPERASIONAL & APRESIASI SESUAI SCREENSHOT 3 --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="text-teal-600 font-bold">💡</span>
                <h3 class="text-sm font-black text-slate-900">Catatan Standar Operasional & Apresiasi</h3>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                Regulasi Workshop v2.4
            </span>
        </div>

        <p class="text-xs text-slate-600 leading-relaxed">
            Kinerja Anda pada shift pagi ini sangat memuaskan. Tingkat reject kain berada di bawah 0.2%, melampaui standar pabrik (maks. 1.5%). Pastikan setiap bundel potongan pola kain Combed 30s telah diikat rapi dan diberi label tag ukuran sebelum diserahkan ke lini penjahitan (sewing line).
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
            <div class="p-4 rounded-xl bg-teal-50/60 border border-teal-200/50 flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-teal-700 shrink-0">
                    🏆
                </div>
                <div>
                    <h4 class="text-xs font-black text-teal-900">Insentif Produktivitas Harian</h4>
                    <p class="text-[11px] text-teal-700 mt-0.5 leading-relaxed">
                        Pencapaian >35 pcs/hari memenuhi syarat bonus shift aktif (+Rp 45.000).
                    </p>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-slate-700 shrink-0">
                    🛠
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900">Perawatan Meja Potong 02</h4>
                    <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                        Pembersihan sisa serat kain dijadwalkan pukul 16:15 WIB sebelum serah terima shift.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
