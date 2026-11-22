<div class="space-y-6" wire:poll.300s>

    {{-- ALERT SUKSES / NOTIFIKASI --}}
    @if ($pesanSukses)
        <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200/80 flex items-center justify-between gap-3 text-teal-800 text-xs font-semibold shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="9 11 12 14 22 4"/>
                </svg>
                <span>{{ $pesanSukses }}</span>
            </div>
            <button wire:click="$set('pesanSukses', '')" class="text-teal-600 hover:text-teal-900 cursor-pointer p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- 1. GREETING SECTION STAFF WORKSHOP --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>

            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1 max-w-2xl leading-relaxed">
                {{ auth()->user()->keterangan_departemen }} 
            </p>
        </div>

        {{-- Tombol Aksi Kanan Sesuai Desain --}}
        <div class="flex items-center gap-2.5 shrink-0">
            <button wire:click="segarkan"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 hover:border-slate-300 transition shadow-2xs cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                    <path d="M16 21h5v-5"/>
                </svg>
                <span>Segarkan Tugas</span>
            </button>

            <button wire:click="bukaSop"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                    <path d="M6 6h10"/>
                    <path d="M6 10h10"/>
                </svg>
                <span>Panduan Kerja SOP</span>
            </button>
        </div>
    </div>

    {{-- 2. RINGKASAN TUGAS (4 KARTU STATISTIK SESUAI SCREENSHOT STAFF) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Card 1: Tugas Hari Ini --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TUGAS HARI INI</p>
                <div class="mt-2 flex items-baseline gap-1.5">
                    <span class="text-3xl font-black text-slate-900">{{ $tugasHariIni }}</span>
                    <span class="text-xs font-bold text-slate-500">Pesanan</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium mt-1 truncate">Tercatat di shift pagi Anda</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                </svg>
            </div>
        </div>

        {{-- Card 2: Sedang Dikerjakan --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[11px] font-bold text-teal-600 uppercase tracking-wider">SEDANG DIKERJAKAN</p>
                <div class="mt-2 flex items-baseline gap-1.5">
                    <span class="text-3xl font-black text-teal-700">{{ $sedangDikerjakan }}</span>
                    <span class="text-xs font-bold text-slate-500">Pesanan</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium mt-1 truncate">{{ auth()->user()->nama_meja_kerja }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-teal-100/70 flex items-center justify-center text-teal-600 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="m14 12 6-6"/>
                    <path d="m14 12-4-4"/>
                    <circle cx="6" cy="18" r="3"/>
                    <circle cx="18" cy="6" r="3"/>
                    <path d="m6 15 8-8"/>
                </svg>
            </div>
        </div>

        {{-- Card 3: Menunggu Giliran --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">MENUNGGU GILIRAN</p>
                <div class="mt-2 flex items-baseline gap-1.5">
                    <span class="text-3xl font-black text-slate-900">{{ $menungguGiliran }}</span>
                    <span class="text-xs font-bold text-slate-500">Pesanan</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium mt-1 truncate">Bahan siap di rak antrean</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M5 22h14"/>
                    <path d="M5 2h14"/>
                    <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/>
                    <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/>
                </svg>
            </div>
        </div>

        {{-- Card 4: Selesai Shift Ini --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SELESAI SHIFT INI</p>
                <div class="mt-2 flex items-baseline gap-1.5">
                    <span class="text-3xl font-black text-slate-900">{{ $selesaiShiftIni }}</span>
                    <span class="text-xs font-bold text-slate-500">Pesanan</span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium mt-1 truncate">Sudah lolos periksa (QC)</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- 3. INFO STATUS DEPARTEMEN DINAMIS SESUAI ROLE OPERATOR --}}
    @if ($roleStaff === 'pemotongan' || $roleStaff === 'pemotongan_kain')
        {{-- Khusus Tim Pemotongan: Fokus pada Kesiapan Bahan & Meja Potong Pola --}}
        <div class="bg-teal-50/50 border border-teal-200/60 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="6" cy="6" r="3"/>
                        <circle cx="6" cy="18" r="3"/>
                        <line x1="20" y1="4" x2="8.12" y2="15.88"/>
                        <line x1="14.47" y1="14.48" x2="20" y2="20"/>
                        <line x1="8.12" y1="8.12" x2="12" y2="12"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900">Info Kesiapan Bahan & Pemotongan Kain</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">
                            Rol Bahan Siap Potong
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed max-w-3xl">
                        Rol kain katun combed 30s, combed 24s, dan fleece tebal telah tersedia di rak bahan. Pastikan pengecekan arah serat kain, susunan layer bahan, serta ketepatan master pola potong sebelum menggelar bahan di meja potong utama.
                    </p>
                </div>
            </div>

            <a href="{{ route('staff.antrean-produksi', ['filter' => 'potong']) }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-slate-800 text-xs font-bold hover:bg-slate-200 transition shrink-0 cursor-pointer">
                <span>Cek Antrean Potong Kain</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>
    @elseif ($roleStaff === 'penjahitan')
        {{-- Khusus Tim Penjahitan: Fokus pada Perakitan Pola & Jahit --}}
        <div class="bg-teal-50/50 border border-teal-200/60 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900">Info Antrean Jahit & Perakitan Pola</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">
                            Pola Potongan Siap Jahit
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed max-w-3xl">
                        Potongan bahan kain dari meja potong sudah dikelompokkan per ukuran di rak jahit siap rakit. Pastikan benang obras, benang jahit, dan aksesoris kerah/rib sudah lengkap sebelum proses penjahitan dimulai.
                    </p>
                </div>
            </div>

            <a href="{{ route('staff.antrean-produksi', ['filter' => 'jahit']) }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-slate-800 text-xs font-bold hover:bg-slate-200 transition shrink-0 cursor-pointer">
                <span>Cek Antrean Jahit</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>
    @elseif ($roleStaff === 'steam')
        {{-- Khusus Tim Steam & Finishing: Fokus pada Setrika Uap & QC --}}
        <div class="bg-teal-50/50 border border-teal-200/60 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900">Info Antrean Steam & Quality Control</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">
                            Siap Finishing & QC
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed max-w-3xl">
                        Pakaian yang telah selesai dijahit siap masuk proses steam setrika uap, pembersihan sisa benang, dan pemeriksaan kualitas (QC) sebelum pengemasan plastik polybag.
                    </p>
                </div>
            </div>

            <a href="{{ route('staff.antrean-produksi', ['filter' => 'steam']) }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-slate-800 text-xs font-bold hover:bg-slate-200 transition shrink-0 cursor-pointer">
                <span>Cek Antrean Steam</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>
    @else
        {{-- Khusus Tim Sablon & Operator Umum: Kluster Desain & Kuota Cetak Sablon --}}
        <div class="bg-teal-50/50 border border-teal-200/60 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/>
                        <circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/>
                        <circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/>
                        <circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/>
                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.992 6.012 17.48 2 12 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900">Info Antrean Sablon</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">
                            Tersimpan Aman
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed max-w-3xl">
                        Ada 1 desain hoodie yang masih menunggu 2 pesanan lagi agar hemat pasta sablon (saat ini <strong>3 dari minimal 5 potong</strong>). Baju lain tetap bisa dikerjakan normal tanpa hambatan.
                    </p>
                </div>
            </div>

            <a href="{{ route('staff.antrean-sablon') }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-slate-800 text-xs font-bold hover:bg-slate-200 transition shrink-0 cursor-pointer">
                <span>Cek Antrean Sablon</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>
    @endif

    {{-- 4. TUGAS YANG PERLU DISELESAIKAN SEGERA --}}
    <div class="space-y-3.5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Tugas yang Perlu Diselesaikan Segera</h2>
            </div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] font-semibold text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                    <path d="M16 21h5v-5"/>
                </svg>
                <span>Diperbarui otomatis tiap 5 menit</span>
            </div>
        </div>

        <div class="space-y-3">
            @forelse ($tugasSegera as $item)
                @php
                    $pesanan = $item->pemesanan;
                    $nomorSpk = $pesanan?->nomor_pemesanan ?? $item->kode_produksi;
                    $tahapAktif = $item->tahapan->first(fn($t) => in_array($t->status, ['sedang_dikerjakan', 'siap_dikerjakan', 'tertunda']))
                        ?? $item->tahapan->first();

                    $jenisKain = strtolower($pesanan?->jenis_kain ?? '');
                    $isKaos = str_contains($jenisKain, 'kaos') || str_contains($jenisKain, 'combed');
                    $isPolo = str_contains($jenisKain, 'polo') || str_contains($jenisKain, 'pique');
                    $isHoodie = str_contains($jenisKain, 'hoodie') || str_contains($jenisKain, 'fleece') || str_contains($jenisKain, 'jaket');

                    $isMendesak = $pesanan?->is_prioritas || ($pesanan?->tanggal_selesai_estimasi && \Carbon\Carbon::parse($pesanan->tanggal_selesai_estimasi)->diffInDays(now(), false) >= -2);
                @endphp

                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition hover:border-slate-300">
                    {{-- Kiri: Icon + Info Produk & SPK --}}
                    <div class="flex items-start gap-4 min-w-0 flex-1">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-700 shrink-0" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                            @if ($isKaos)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-700 shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2a3 3 0 0 0-3 3c0 1.3.8 2.4 2 2.8V9L3.5 15.5A2 2 0 0 0 5 19h14a2 2 0 0 0 1.5-3.5L13 9V7.8A3 3 0 0 0 12 2Z"/>
                                </svg>
                            @elseif ($isPolo)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-700 shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-700 shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                                    <polyline points="2 17 12 22 22 17"/>
                                    <polyline points="2 12 12 17 22 12"/>
                                </svg>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-black text-slate-900 tracking-tight">SPK {{ $nomorSpk }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                    {{ $pesanan?->jumlah ?? 0 }} Pcs
                                </span>

                                @if ($isMendesak)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Tenggat 2 Hari Lagi (Mendesak)
                                    </span>
                                @elseif ($nomorSpk === 'ORD-2026-003')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/50">
                                        Siap Dijahit
                                    </span>
                                @elseif ($nomorSpk === 'ORD-2026-004')
                                    @if ($roleStaff === 'pemotongan' || $roleStaff === 'pemotongan_kain')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/50">
                                            Kain Selesai Dipotong
                                        </span>
                                    @elseif ($roleStaff === 'sablon')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/50">
                                            Tertahan Sablon (3/5 Pcs)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                            Proses Sablon
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <h3 class="text-xs sm:text-sm font-black text-slate-900 mt-1">
                                {{ $pesanan?->jenis_kain ?? 'Kaos Polos Combed 30s' }}
                            </h3>

                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                                @if ($nomorSpk === 'ORD-2026-001')
                                    Ukuran: M (15 Pcs), L (15 Pcs) • Pola potongan sudah diplot di meja potong utama
                                @elseif ($nomorSpk === 'ORD-2026-003')
                                    <span class="text-teal-700 font-semibold">📁 Bahan potongan, kancing & kerah sudah lengkap siap ambil di <strong>Rak B3</strong></span>
                                @elseif ($nomorSpk === 'ORD-2026-004')
                                    @if ($roleStaff === 'pemotongan' || $roleStaff === 'pemotongan_kain')
                                        <span class="text-slate-600 font-medium">Ukuran: XL (1 Pcs), XXL (2 Pcs) • Pola kain cotton fleece 330gsm sudah selesai dipotong di meja potong</span>
                                    @elseif ($roleStaff === 'sablon')
                                        <span class="text-slate-500">ⓘ Menunggu kuota cetak sablon rubber terkumpul agar tidak boros screen & pasta</span>
                                    @else
                                        <span class="text-slate-500">Pelanggan: {{ $pesanan?->pelanggan?->nama ?? 'Umum' }} • Bahan: Siap Dikerjakan</span>
                                    @endif
                                @else
                                    Pelanggan: {{ $pesanan?->pelanggan?->nama ?? 'Umum' }} • Bahan: {{ $pesanan?->kesiapan_bahan ?? 'Siap Dikerjakan' }}
                                @endif
                            </p>

                            @if ($nomorSpk === 'ORD-2026-001')
                                <div class="mt-2.5 max-w-sm space-y-1">
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-600">
                                        <span>Proses Pemotongan Pola</span>
                                        <span class="text-teal-700 font-black">40% Selesai</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-black h-full rounded-full" style="width: 40%;"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kanan: Action Buttons Sesuai Role & Wewenang --}}
                    <div class="flex items-center gap-2.5 shrink-0 self-end md:self-center">
                        <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $pesanan?->id]) }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs font-bold hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                            <span>Detail SPK</span>
                        </a>

                        @php
                            $tahapRoleNama = match ($roleStaff) {
                                'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
                                'penjahitan' => 'penjahitan',
                                'steam' => 'steam',
                                'sablon' => 'sablon',
                                default => null,
                            };

                            $tahapDivisiItem = $tahapRoleNama ? $item->tahapan->firstWhere('nama_tahap', $tahapRoleNama) : $tahapAktif;
                            $tahapDivisiStatus = $tahapDivisiItem?->status;

                            $bolehKerjakan = true;
                            $alasanTunggu = '';
                            if ($tahapRoleNama === 'penjahitan') {
                                $prevTahap = $item->tahapan->firstWhere('nama_tahap', 'pemotongan_kain');
                                if ($prevTahap && $prevTahap->status !== 'selesai') {
                                    $bolehKerjakan = false;
                                    $alasanTunggu = 'Menunggu Pemotongan Selesai';
                                }
                            } elseif ($tahapRoleNama === 'steam') {
                                $prevTahap = $item->tahapan->firstWhere('nama_tahap', 'penjahitan');
                                if ($prevTahap && $prevTahap->status !== 'selesai') {
                                    $bolehKerjakan = false;
                                    $alasanTunggu = 'Menunggu Penjahitan Selesai';
                                }
                            } elseif ($tahapRoleNama === 'sablon') {
                                $prevTahap = $item->tahapan->firstWhere('nama_tahap', 'steam');
                                if ($prevTahap && $prevTahap->status !== 'selesai') {
                                    $bolehKerjakan = false;
                                    $alasanTunggu = 'Menunggu Steam Selesai';
                                }
                            }
                        @endphp

                        @if ($tahapRoleNama && $tahapDivisiItem)
                            @if ($tahapDivisiStatus === 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/80 text-xs font-bold shadow-2xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span>{{ $tahapDivisiItem->label_tahap }} Selesai</span>
                                </span>
                            @elseif (! $bolehKerjakan)
                                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-amber-50 text-amber-800 border border-amber-200/80 text-xs font-bold shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>{{ $alasanTunggu }}</span>
                                </span>
                            @elseif ($tahapDivisiStatus === 'sedang_dikerjakan')
                                <button wire:click="bukaModalAksi({{ $item->id }}, 'selesai', {{ $tahapDivisiItem->id }})"
                                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span>Selesaikan {{ $tahapDivisiItem->label_tahap }}</span>
                                </button>
                            @else
                                <button wire:click="bukaModalAksi({{ $item->id }}, 'mulai', {{ $tahapDivisiItem->id }})"
                                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="5 3 19 12 5 21 5 3"/>
                                    </svg>
                                    <span>Mulai {{ $tahapDivisiItem->label_tahap }}</span>
                                </button>
                            @endif
                        @else
                            {{-- Admin / Operator Umum Lini Produksi --}}
                            @if ($tahapAktif)
                                @if ($tahapAktif->status === 'sedang_dikerjakan')
                                    <button wire:click="bukaModalAksi({{ $item->id }}, 'selesai', {{ $tahapAktif->id }})"
                                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                                        <span>Selesaikan {{ $tahapAktif->label_tahap }}</span>
                                    </button>
                                @else
                                    <button wire:click="bukaModalAksi({{ $item->id }}, 'mulai', {{ $tahapAktif->id }})"
                                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer">
                                        <span>Mulai {{ $tahapAktif->label_tahap }}</span>
                                    </button>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center bg-white rounded-2xl border border-slate-200">
                    <p class="text-xs text-slate-500 font-medium">Tidak ada tugas mendesak di lantai workshop saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- 5. PENGINGAT KESELAMATAN & ISTIRAHAT SIANG SESUAI SCREENSHOT --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Card Keselamatan Kerja --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h4 class="text-xs font-bold text-slate-900 leading-snug">Keselamatan Kerja {{ auth()->user()->nama_departemen }}</h4>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                    Pastikan pelindung jari terpasang saat menggunakan mesin potong kain putar. Gunakan alas kaki tertutup dan jaga area lantai bebas dari sisa gulungan benang.
                </p>
            </div>
        </div>

        {{-- Card Pengingat Istirahat Siang --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                    <line x1="6" y1="1" x2="6" y2="4"/>
                    <line x1="10" y1="1" x2="10" y2="4"/>
                    <line x1="14" y1="1" x2="14" y2="4"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h4 class="text-xs font-bold text-slate-900 leading-snug">Pengingat Istirahat Siang</h4>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                    Waktu istirahat shift Anda adalah pukul <strong class="text-slate-800">12:00 – 13:00 WIB</strong>. Matikan saklar daya mesin sebelum meninggalkan stasiun kerja demi keamanan bersama.
                </p>
            </div>
        </div>
    </div>

    {{-- MODAL AKSI OPERATOR (MULAI / SELESAI / TUNDA) --}}
    @if ($showModalAksi)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 animate-scale-up space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900 capitalize">
                        Konfirmasi {{ $aksiTipe }} Pengerjaan Tahap
                    </h3>
                    <button wire:click="tutupModalAksi" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>

                <div class="space-y-3">
                    <p class="text-xs text-slate-600">
                        @if ($aksiTipe === 'mulai')
                            Anda akan memulai pengerjaan tahap ini. Jam mulai dan identitas Anda akan dicatat otomatis di sistem.
                        @elseif ($aksiTipe === 'selesai')
                            Pastikan hasil pengerjaan telah memenuhi standar mutu konveksi sebelum menandai selesai.
                        @else
                            Berikan catatan alasan mengapa pengerjaan tahap ini ditunda.
                        @endif
                    </p>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Catatan Operator {{ $aksiTipe === 'tunda' ? '(Wajib)' : '(Opsional)' }}
                        </label>
                        <textarea wire:model="catatanAksi"
                                  rows="3"
                                  placeholder="{{ $aksiTipe === 'tunda' ? 'Tuliskan alasan kendala atau penundaan...' : 'Tuliskan catatan pengerjaan meja potong/jahit...' }}"
                                  class="w-full text-xs rounded-xl border border-slate-200 p-2.5 focus:outline-hidden focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition text-slate-800"></textarea>
                        @error('catatanAksi')
                            <p class="text-rose-600 text-[11px] font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button wire:click="tutupModalAksi" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button wire:click="prosesAksi" class="px-4 py-2 rounded-xl text-xs font-bold text-white transition cursor-pointer {{ $aksiTipe === 'selesai' ? 'bg-emerald-600 hover:bg-emerald-700' : ($aksiTipe === 'tunda' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-black hover:bg-zinc-800') }}">
                        Konfirmasi {{ ucfirst($aksiTipe) }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL SOP KERJA --}}
    @if ($showModalSop)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 animate-scale-up space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                        <h3 class="text-sm font-black text-slate-900">Panduan SOP Pengerjaan Workshop COTTON.id</h3>
                    </div>
                    <button wire:click="tutupSop" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>

                <div class="space-y-3.5 text-xs text-slate-600 max-h-96 overflow-y-auto pr-1">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <h4 class="font-bold text-slate-900">1. Tahap Pemotongan Kain (Cutting)</h4>
                        <p class="mt-1 text-[11px]">Periksa serat kain (lurus/melintang) dan pola master sesuai pesanan SPK. Selesaikan potong bahan kain agar pesanan otomatis diteruskan ke tim Penjahitan.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <h4 class="font-bold text-slate-900">2. Tahap Penjahitan (Sewing)</h4>
                        <p class="mt-1 text-[11px]">Jahit dan rakit potongan kain sesuai pola pesanan. Gunakan jahitan rapi standar distro. Selesai jahit langsung otomatis diteruskan ke stasiun Steam.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <h4 class="font-bold text-slate-900">3. Tahap Steam & Finishing</h4>
                        <p class="mt-1 text-[11px]">Bersihkan sisa benang, setrika uap suhu stabil, dan rapikan bentuk pakaian. Setelah steam selesai, otomatis diteruskan ke stasiun Sablon.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <h4 class="font-bold text-slate-900">4. Tahap Sablon & QC Akhir</h4>
                        <p class="mt-1 text-[11px]">Cetak sablon sesuai desain SPK dan lakukan pemeriksaan mutu final (QC). Konfirmasi selesai produksi akan langsung diterima oleh Admin.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-3 border-t border-slate-100">
                    <button wire:click="tutupSop" class="px-4 py-2 rounded-xl text-xs font-bold bg-black text-white hover:bg-zinc-800 transition cursor-pointer">
                        Mengerti & Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
