<div class="space-y-6" x-data="{}" x-on:cetak-dokumen.window="window.print()">

    {{-- ALERT NOTIFIKASI SUKSES --}}
    @if($pesanSukses)
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-emerald-900">Operasi Berhasil!</h4>
                    <p class="text-xs text-emerald-700 font-medium mt-0.5">{{ $pesanSukses }}</p>
                </div>
            </div>
            <button type="button" wire:click="$set('pesanSukses', '')" class="text-emerald-500 hover:text-emerald-800 text-sm font-bold p-1">
                ✕
            </button>
        </div>
    @endif

    {{-- HEADER HALAMAN (SESUAI GAMBAR 3) --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            {{-- Judul Utama --}}
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Manajemen Pesanan Masuk</h1>
            
            {{-- Subteks Deskripsi --}}
            <p class="text-xs sm:text-sm text-gray-500 font-normal mt-1 max-w-2xl leading-relaxed">
                Kelola seluruh pesanan pakaian dari pelanggan, status pembayaran DP, dan penerbitan surat perintah kerja (SPK) pabrik.
            </p>
        </div>

        {{-- Tombol Aksi Kanan --}}
        <div class="flex items-center gap-2.5 self-start lg:self-auto shrink-0">
            {{-- Cetak Rekap Pesanan --}}
            <button
                type="button"
                wire:click="cetakRekap"
                class="flex items-center gap-2 rounded-xl bg-gray-100 hover:bg-gray-200 border border-gray-200/80 px-4 py-2.5 text-xs font-bold text-gray-800 transition active:scale-95 shadow-sm"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect width="12" height="8" x="6" y="14"/>
                </svg>
                <span>Cetak Rekap Pesanan</span>
            </button>

            {{-- Buat Pesanan Baru --}}
            <button
                type="button"
                wire:click="bukaModalCreate"
                class="flex items-center gap-2 rounded-xl bg-black hover:bg-gray-800 px-4 py-2.5 text-xs font-bold text-white shadow-md transition active:scale-95 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span>Buat Pesanan Baru</span>
            </button>
        </div>
    </div>

    {{-- 5 KARTU METRIK TAHAP LANGKAH RINGKASAN (INTERAKTIF & BISA DIKLIK) --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5">
        {{-- Card 1: LANGKAH RINGKASAN - Semua Pesanan Berjalan --}}
        <button
            type="button"
            wire:click="setTab('semua')"
            class="text-left bg-white rounded-2xl p-4 border transition-all duration-200 cursor-pointer select-none flex flex-col justify-between relative overflow-hidden active:scale-95 group {{ $tabStatus === 'semua' ? 'border-black ring-2 ring-black shadow-md scale-[1.02]' : 'border-gray-100 shadow-sm hover:border-gray-300 hover:shadow-md' }}"
        >
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block leading-none">LANGKAH RINGKASAN</span>
                    <h4 class="text-xs font-bold text-gray-800 mt-1 leading-tight group-hover:text-black transition">Semua Pesanan Berjalan</h4>
                </div>
                <div class="w-8 h-8 rounded-full bg-cyan-50 text-cyan-700 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 2 10 5-10 5L2 7z"/>
                        <path d="m2 17 10 5 10-5"/>
                        <path d="m2 12 10 5 10-5"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between mt-4 w-full">
                <span class="text-3xl font-black text-gray-900 tracking-tight">{{ $ringkasan['total_aktif'] }}</span>
                <span class="text-[10px] text-gray-400 font-medium">Total aktif</span>
            </div>
        </button>

        {{-- Card 2: TAHAP 1 - Menunggu Konfirmasi & DP --}}
        <button
            type="button"
            wire:click="setTab('perlu_konfirmasi')"
            class="text-left bg-white rounded-2xl p-4 border transition-all duration-200 cursor-pointer select-none flex flex-col justify-between relative overflow-hidden active:scale-95 group {{ $tabStatus === 'perlu_konfirmasi' ? 'border-amber-500 ring-2 ring-amber-500 shadow-md scale-[1.02]' : 'border-gray-100 shadow-sm hover:border-gray-300 hover:shadow-md' }}"
        >
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block leading-none">TAHAP 1</span>
                    <h4 class="text-xs font-bold text-gray-800 mt-1 leading-tight group-hover:text-amber-700 transition">Menunggu Konfirmasi & DP</h4>
                </div>
                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between mt-4 w-full">
                <span class="text-3xl font-black text-gray-900 tracking-tight">{{ $ringkasan['konfirmasi'] }}</span>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">Perlu respon</span>
            </div>
        </button>

        {{-- Card 3: TAHAP 2 - Kain Siap & Masuk Antrean --}}
        <button
            type="button"
            wire:click="setTab('masuk_antrean')"
            class="text-left bg-white rounded-2xl p-4 border transition-all duration-200 cursor-pointer select-none flex flex-col justify-between relative overflow-hidden active:scale-95 group {{ $tabStatus === 'masuk_antrean' ? 'border-emerald-500 ring-2 ring-emerald-500 shadow-md scale-[1.02]' : 'border-gray-100 shadow-sm hover:border-gray-300 hover:shadow-md' }}"
        >
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block leading-none">TAHAP 2</span>
                    <h4 class="text-xs font-bold text-gray-800 mt-1 leading-tight group-hover:text-emerald-700 transition">Kain Siap & Masuk Antrean</h4>
                </div>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                        <path d="M3 9h18"/>
                        <path d="M9 21V9"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between mt-4 w-full">
                <span class="text-3xl font-black text-gray-900 tracking-tight">{{ $ringkasan['kain_siap'] }}</span>
                <span class="text-[10px] text-emerald-600 font-bold">Bahan lengkap</span>
            </div>
        </button>

        {{-- Card 4: TAHAP 3 • INTI - Sedang Dikerjakan Pabrik (HIGHLIGHTED DARK CARD PERSIS GAMBAR 3) --}}
        <button
            type="button"
            wire:click="setTab('sedang_dibuat')"
            class="text-left rounded-2xl p-4 text-white shadow-xl relative overflow-hidden flex flex-col justify-between transition-all duration-200 cursor-pointer select-none active:scale-95 group border {{ $tabStatus === 'sedang_dibuat' ? 'border-teal-400 ring-2 ring-teal-400 shadow-teal-950/40 scale-[1.02]' : 'border-slate-700/80 hover:border-teal-500/60' }}"
            style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);"
        >
            {{-- Efek Radial Watermark di Background --}}
            <div class="pointer-events-none absolute -right-4 -bottom-4 w-28 h-28 rounded-full border border-teal-500/20 opacity-30"></div>
            <div class="flex items-start justify-between relative z-10 w-full">
                <div>
                    <span class="text-[9px] font-bold text-teal-300 uppercase tracking-widest block leading-none">TAHAP 3 • INTI</span>
                    <h4 class="text-xs font-bold text-white mt-1 leading-tight group-hover:text-teal-200 transition">Sedang Dikerjakan Pabrik</h4>
                </div>
                <div class="w-8 h-8 rounded-full bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0 border border-teal-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between mt-4 relative z-10 w-full">
                <span class="text-3xl font-black text-white tracking-tight">{{ $ringkasan['sedang_pabrik'] }}</span>
                <span class="text-[9px] text-slate-300 font-medium">Potong, jahit, steam, sablon</span>
            </div>
        </button>

        {{-- Card 5: TAHAP 4 - Selesai --}}
        <button
            type="button"
            wire:click="setTab('siap_kirim')"
            class="text-left bg-white rounded-2xl p-4 border transition-all duration-200 cursor-pointer select-none flex flex-col justify-between relative overflow-hidden active:scale-95 group col-span-2 lg:col-span-1 {{ ($tabStatus === 'siap_kirim' || $tabStatus === 'selesai') ? 'border-cyan-500 ring-2 ring-cyan-500 shadow-md scale-[1.02]' : 'border-gray-100 shadow-sm hover:border-gray-300 hover:shadow-md' }}"
        >
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block leading-none">TAHAP 4</span>
                    <h4 class="text-xs font-bold text-gray-800 mt-1 leading-tight group-hover:text-cyan-700 transition">Selesai</h4>
                </div>
                <div class="w-8 h-8 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="16" height="13" x="1" y="3" rx="2"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/>
                        <circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between mt-4 w-full">
                <span class="text-3xl font-black text-gray-900 tracking-tight">{{ $ringkasan['siap_kirim'] }}</span>
                <span class="text-[10px] text-teal-600 font-bold">Siap ambil</span>
            </div>
        </button>
    </div>

    {{-- BARIS TAB FILTER CEPAT & DROPDOWN URUTAN (SESUAI GAMBAR 3) --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
        {{-- Tombol Tab Filter --}}
        <div class="flex flex-wrap items-center gap-1.5 bg-gray-100/80 p-1.5 rounded-2xl">
            {{-- Tab: Semua --}}
            <button
                type="button"
                wire:click="setTab('semua')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $tabStatus === 'semua' ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
            >
                Semua ({{ $ringkasan['total_aktif'] }})
            </button>

            {{-- Tab: Perlu Konfirmasi --}}
            <button
                type="button"
                wire:click="setTab('perlu_konfirmasi')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $tabStatus === 'perlu_konfirmasi' ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
            >
                Perlu Konfirmasi ({{ $ringkasan['konfirmasi'] }})
            </button>

            {{-- Tab: Masuk Antrean --}}
            <button
                type="button"
                wire:click="setTab('masuk_antrean')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $tabStatus === 'masuk_antrean' ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
            >
                Masuk Antrean ({{ $ringkasan['kain_siap'] }})
            </button>

            {{-- Tab: Sedang Dibuat --}}
            <button
                type="button"
                wire:click="setTab('sedang_dibuat')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ $tabStatus === 'sedang_dibuat' ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
            >
                Sedang Dibuat ({{ $ringkasan['sedang_pabrik'] }})
            </button>

            {{-- Tab: Siap Kirim / Selesai --}}
            <button
                type="button"
                wire:click="setTab('siap_kirim')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ ($tabStatus === 'siap_kirim' || $tabStatus === 'selesai') ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
            >
                Siap Kirim ({{ $ringkasan['siap_kirim'] }})
            </button>
        </div>

        {{-- Dropdown Urutan --}}
        <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="21" x2="14" y1="4" y2="4"/>
                <line x1="10" x2="3" y1="4" y2="4"/>
                <line x1="21" x2="12" y1="12" y2="12"/>
                <line x1="8" x2="3" y1="12" y2="12"/>
                <line x1="21" x2="16" y1="20" y2="20"/>
                <line x1="12" x2="3" y1="20" y2="20"/>
                <line x1="14" x2="14" y1="2" y2="6"/>
                <line x1="8" x2="8" y1="10" y2="14"/>
                <line x1="16" x2="16" y1="18" y2="22"/>
            </svg>
            <span>Urutan:</span>
            <select
                wire:model.live="urutan"
                class="rounded-xl border-0 bg-transparent py-1 pl-1 pr-6 text-xs font-bold text-gray-800 focus:outline-none focus:ring-0 cursor-pointer"
            >
                <option value="tenggat_terdekat">Batas Waktu Terdekat</option>
                <option value="terbaru">Pesanan Terbaru Masuk</option>
                <option value="terlama">Pesanan Terlama</option>
                <option value="jumlah_terbanyak">Jumlah Pcs Terbanyak</option>
            </select>
        </div>
    </div>

    {{-- BARIS PENCARIAN & FILTER DROPDOWN JENIS PAKAIAN (SESUAI GAMBAR 3) --}}
    <div class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            {{-- Input Pencarian --}}
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" x2="16.65" y1="21" y2="16.65"/>
                    </svg>
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nomor pesanan (ORD-...), nama pemesan, atau jenis pakaian..."
                    class="w-full rounded-xl border border-gray-100 bg-gray-50 py-2.5 pl-10 pr-4 text-xs placeholder-gray-400 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black transition"
                />
            </div>

            {{-- Filter Jenis Pakaian --}}
            <div class="flex items-center gap-2">
                <select
                    wire:model.live="filterPakaian"
                    class="rounded-xl border border-gray-200 bg-gray-50 py-2 px-3 text-xs font-bold text-gray-700 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black cursor-pointer"
                >
                    <option value="semua">Semua Pakaian</option>
                    <option value="Kaos">Kaos & T-Shirt</option>
                    <option value="Polo">Polo Shirt</option>
                    <option value="Jaket">Jaket & Windbreaker</option>
                    <option value="Kemeja">Kemeja & PDL</option>
                    <option value="Hoodie">Hoodie & Fleece</option>
                </select>

                {{-- Filter Icon Button --}}
                <button
                    type="button"
                    class="p-2.5 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-600 transition"
                    title="Filter Lanjutan"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- TABEL DAFTAR PESANAN MASUK LENGKAP (SESUAI GAMBAR 3) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/75 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="py-3.5 px-5">NO. PESANAN</th>
                        <th class="py-3.5 px-5">PELANGGAN</th>
                        <th class="py-3.5 px-5">JENIS PAKAIAN & JUMLAH</th>
                        <th class="py-3.5 px-5">BATAS WAKTU SELESAI</th>
                        <th class="py-3.5 px-5">STATUS PEMBAYARAN</th>
                        <th class="py-3.5 px-5">TAHAP DI WORKSHOP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($daftarPesanan as $pesanan)
                        @php
                            $tenggatCarbon = $pesanan->tanggal_selesai_estimasi ? \Carbon\Carbon::parse($pesanan->tanggal_selesai_estimasi)->startOfDay() : null;
                            $hariIni = now()->startOfDay();
                            $sisaHari = $tenggatCarbon ? (int) $hariIni->diffInDays($tenggatCarbon, false) : null;
                            
                            // Inisial Pelanggan
                            $namaPelanggan = $pesanan->pelanggan?->nama ?? 'Study Club Informatika';
                            $words = explode(' ', $namaPelanggan);
                            $inisial = strtoupper(substr($words[0] ?? 'C', 0, 1) . substr($words[1] ?? 'S', 0, 1));
                            
                            // Tahap Workshop & Lokasi Lini
                            $tahapJudul = 'Sedang Sablon';
                            $tahapLini = 'Meja Sablon B';
                            if ($pesanan->produksi) {
                                if ($pesanan->produksi->tahap_saat_ini === 'penjahitan') {
                                    $tahapJudul = 'Sedang Dijahit';
                                    $tahapLini = $pesanan->produksi->catatan_produksi ?: 'Lini Mesin 04';
                                } elseif ($pesanan->produksi->tahap_saat_ini === 'pemotongan_kain') {
                                    $tahapJudul = 'Siap Dipotong';
                                    $tahapLini = $pesanan->produksi->catatan_produksi ?: 'Antrean Pemotongan';
                                } elseif ($pesanan->produksi->tahap_saat_ini === 'selesai' || $pesanan->status_pemesanan === 'siap_diambil') {
                                    $tahapJudul = 'Siap Diambil';
                                    $tahapLini = 'Gudang Siap Kirim';
                                } else {
                                    $tahapJudul = 'Sedang Sablon';
                                    $tahapLini = $pesanan->produksi->catatan_produksi ?: 'Meja Sablon B';
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition cursor-pointer" wire:click="lihatDetail({{ $pesanan->id }})">
                            {{-- 1. NO. PESANAN --}}
                            <td class="py-4 px-5">
                                <span class="font-bold text-gray-900 block leading-tight">{{ $pesanan->nomor_pemesanan }}</span>
                                <span class="text-[11px] text-gray-400 mt-0.5 block">
                                    Masuk: {{ \Carbon\Carbon::parse($pesanan->tanggal_pemesanan)->format('d M') }}
                                </span>
                            </td>

                            {{-- 2. PELANGGAN --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 text-gray-600 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ $inisial }}
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-gray-900 leading-tight">{{ $namaPelanggan }}</h5>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $pesanan->pelanggan?->nomor_telepon }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- 3. JENIS PAKAIAN & JUMLAH --}}
                            <td class="py-4 px-5">
                                <span class="font-bold text-gray-900 block leading-tight">{{ $pesanan->jumlah }} pcs</span>
                                <span class="text-[11px] text-gray-500 mt-0.5 block">{{ $pesanan->jenis_kain }}</span>
                            </td>

                            {{-- 4. BATAS WAKTU SELESAI --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-gray-900">
                                        {{ $tenggatCarbon ? $tenggatCarbon->format('d M Y') : '26 Feb 2026' }}
                                    </span>

                                    {{-- Badge Tenggat Waktu (Mendesak / Sisa Hari / Hari Ini Selesai) --}}
                                    @if($pesanan->status_pemesanan === 'siap_diambil' || ($sisaHari !== null && $sisaHari === 0))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                            ✓ Hari Ini Selesai
                                        </span>
                                    @elseif($sisaHari !== null && $sisaHari <= 2 && $sisaHari > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                            Mendesak ({{ $sisaHari }} Hari)
                                        </span>
                                    @elseif($sisaHari !== null && $sisaHari > 2)
                                        <span class="text-[11px] text-gray-400 font-medium">
                                            ({{ $sisaHari }} Hari Lagi)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                            Mendesak (2 Hari)
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- 5. STATUS PEMBAYARAN --}}
                            <td class="py-4 px-5">
                                @if($pesanan->status_pembayaran === 'lunas')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ✓ Sudah Lunas
                                    </span>
                                @elseif($pesanan->status_pembayaran === 'dp')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                        ✓ DP 50% Diterima
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>

                            {{-- 6. TAHAP DI WORKSHOP --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-slate-700 shrink-0"></span>
                                    <div>
                                        <span class="font-bold text-gray-900 block leading-tight">{{ $tahapJudul }}</span>
                                        <span class="text-[11px] text-gray-400 mt-0.5 block">{{ $tahapLini }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                Tidak ada data pesanan yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- BARIS PAGINATION BAWAH TABEL (SESUAI GAMBAR 3) --}}
        <div class="px-5 py-3.5 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                Menampilkan <span class="font-bold text-gray-800">{{ $daftarPesanan->firstItem() ?? 1 }} - {{ $daftarPesanan->lastItem() ?? $daftarPesanan->count() }}</span> dari <span class="font-bold text-gray-800">{{ $daftarPesanan->total() }}</span> pesanan aktif
            </p>
            <div>
                {{ $daftarPesanan->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL BUAT / EDIT PESANAN (SPK) --}}
    @include('livewire.admin.partials.modal-form-spk')

    {{-- MODAL DETAIL SPK PESANAN --}}
    @if($showModalDetail && $selectedPesanan)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-6 sm:p-8 space-y-5 relative my-8 animate-in fade-in duration-200">
                <div class="flex items-start justify-between border-b border-gray-100 pb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-black text-gray-900">{{ $selectedPesanan->nomor_pemesanan }}</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                SPK Aktif
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">Diterbitkan: {{ \Carbon\Carbon::parse($selectedPesanan->tanggal_pemesanan)->format('d M Y') }}</p>
                    </div>
                    <button type="button" wire:click="tutupModalDetail" class="text-gray-400 hover:text-gray-600 text-sm font-bold">✕</button>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Pelanggan</span>
                        <span class="font-bold text-gray-900">{{ $selectedPesanan->pelanggan?->nama }}</span>
                        <span class="mt-1 block text-[10px] text-gray-500">{{ $selectedPesanan->pelanggan?->nomor_telepon }}</span>
                        <span class="mt-1 block text-[10px] text-gray-500">{{ $selectedPesanan->pelanggan?->alamat }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Target Deadline</span>
                        <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($selectedPesanan->tanggal_selesai_estimasi)->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Item & Jumlah</span>
                        <span class="font-bold text-gray-900">{{ $selectedPesanan->jumlah }} pcs • {{ $selectedPesanan->jenis_kain }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Total Nilai SPK</span>
                        <span class="font-bold text-teal-700">Rp {{ number_format($selectedPesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Warna Kain Utama</span>
                        <span class="whitespace-pre-line font-bold text-gray-900">{{ $selectedPesanan->warna }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Ringkasan Ukuran</span>
                        <span class="whitespace-pre-line font-bold text-gray-900">{{ $selectedPesanan->ukuran_baju }}</span>
                    </div>
                </div>

                {{-- TABEL RINCIAN VARIASI PESANAN (KATEGORI, WARNA, UKURAN, JUMLAH) --}}
                @if($selectedPesanan->variasi && $selectedPesanan->variasi->count() > 0)
                    <div class="rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-3.5 py-2 border-b border-gray-200 flex items-center justify-between">
                            <span class="text-xs font-black text-gray-900 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                                    <line x1="3" y1="9" x2="21" y2="9"/>
                                    <line x1="9" y1="21" x2="9" y2="9"/>
                                </svg>
                                Tabel Rincian Variasi Pesanan (SPK)
                            </span>
                            <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200/50">
                                Total: {{ $selectedPesanan->jumlah }} Pcs
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-gray-100/70 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                    <tr>
                                        <th class="py-2.5 px-3">No</th>
                                        <th class="py-2.5 px-3">Kode SKU Ukuran</th>
                                        <th class="py-2.5 px-3">Produk</th>
                                        <th class="py-2.5 px-3">Kategori</th>
                                        <th class="py-2.5 px-3">Warna Kain</th>
                                        <th class="py-2.5 px-3">Ukuran</th>
                                        <th class="py-2.5 px-3 text-center">Jumlah</th>
                                        <th class="py-2.5 px-3 text-right">Harga Satuan</th>
                                        <th class="py-2.5 px-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-800">
                                    @php
                                        $totalSubtotal = 0;
                                    @endphp
                                    @foreach($selectedPesanan->variasi as $idx => $v)
                                        @php
                                            $hargaSatuanItem = $v->harga_satuan ?? ($selectedPesanan->harga_satuan ?? 60000);
                                            $subtotalItem = $v->subtotal ?? ($hargaSatuanItem * $v->jumlah);
                                            $totalSubtotal += $subtotalItem;
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="py-2 px-3 text-gray-400 font-mono text-[11px]">{{ $idx + 1 }}</td>
                                            <td class="py-2 px-3 font-mono font-bold text-gray-700">
                                                <span class="inline-flex rounded-lg bg-gray-100 px-2 py-0.5 text-[10px] font-bold text-gray-700">
                                                    {{ $v->kode_sku_ukuran }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 font-bold text-gray-900">{{ $v->nama_produk ?? $selectedPesanan->jenis_kain }}</td>
                                            <td class="py-2 px-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $v->kategori_ukuran === 'Anak-anak' ? 'bg-amber-50 text-amber-800 border border-amber-200/60' : 'bg-blue-50 text-blue-800 border border-blue-200/60' }}">
                                                    {{ $v->kategori_ukuran }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 font-semibold text-gray-900">{{ $v->warna }}</td>
                                            <td class="py-2 px-3 font-bold text-[#0D7A68]">{{ $v->ukuran }}</td>
                                            <td class="py-2 px-3 text-center font-black text-gray-900">{{ $v->jumlah }} pcs</td>
                                            <td class="py-2 px-3 text-right font-mono font-medium text-gray-700">Rp {{ number_format($hargaSatuanItem, 0, ',', '.') }}</td>
                                            <td class="py-2 px-3 text-right font-black text-gray-900">Rp {{ number_format($subtotalItem, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50/80 font-bold border-t border-gray-200 text-gray-900">
                                    <tr>
                                        <td colspan="5" class="py-2.5 px-3 text-right uppercase text-[10px] tracking-wider text-gray-500">Total Akumulasi:</td>
                                        <td class="py-2.5 px-3 text-center font-black text-[#0D7A68]">{{ $selectedPesanan->jumlah }} pcs</td>
                                        <td class="py-2.5 px-3 text-right uppercase text-[10px] tracking-wider text-gray-500">Total Harga:</td>
                                        <td class="py-2.5 px-3 text-right font-black text-[#0D7A68]">Rp {{ number_format($selectedPesanan->total_harga ?? $totalSubtotal, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif

                @if($selectedPesanan->detail_desain)
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 text-xs text-gray-700">
                        <strong class="text-gray-900 block mb-1">Catatan Pengerjaan:</strong>
                        {{ $selectedPesanan->detail_desain }}
                    </div>
                @endif
                @if($selectedPesanan->file_desain)
                    <button type="button" wire:click="unduhDesain({{ $selectedPesanan->id }})" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-3 py-2 text-xs font-bold text-gray-700 transition hover:bg-gray-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/>
                        </svg>
                        Unduh File Desain
                    </button>
                @endif

                <div class="flex items-center justify-between pt-3 border-t border-gray-100 flex-wrap gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <button
                            type="button"
                            wire:click="bukaModalEdit({{ $selectedPesanan->id }})"
                            class="px-4 py-2 rounded-xl bg-black hover:bg-gray-800 text-xs font-bold text-white transition cursor-pointer"
                        >
                            Edit Pesanan & Variasi
                        </button>
                        <button
                            type="button"
                            wire:click="bukaModalUpdate({{ $selectedPesanan->id }})"
                            class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-xs font-bold text-white transition cursor-pointer"
                        >
                            Ubah Status & Pengerjaan
                        </button>
                    </div>
                    <button
                        type="button"
                        wire:click="tutupModalDetail"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100 cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL UBAH STATUS CEPAT --}}
    @if($showModalUpdate)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 space-y-4 relative my-8 animate-in fade-in duration-200">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-black text-gray-900">Update Status Pesanan</h3>
                    <button type="button" wire:click="tutupModalUpdate" class="text-gray-400 hover:text-gray-600 text-sm font-bold">✕</button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Status Pemesanan (SPK)</label>
                        <select wire:model="newStatusPemesanan" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs">
                            <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                            <option value="antrian_produksi">Antrian Produksi</option>
                            <option value="dalam_produksi">Dalam Produksi</option>
                            <option value="siap_diambil">Siap Diambil</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Status Pembayaran</label>
                        <select wire:model="newStatusPembayaran" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs">
                            <option value="dp">✓ DP 50% Diterima</option>
                            <option value="lunas">✓ Sudah Lunas</option>
                            <option value="belum_bayar">Belum Bayar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahap di Workshop</label>
                        <select wire:model="newTahapProduksi" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs">
                            <option value="pemotongan_kain">Siap Dipotong</option>
                            <option value="sablon">Sedang Sablon</option>
                            <option value="penjahitan">Sedang Dijahit</option>
                            <option value="steam">Steam / QC</option>
                            <option value="selesai">Siap Diambil</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan Lokasi Lini / Mesin</label>
                        <input
                            type="text"
                            wire:model="newCatatanWorkshop"
                            placeholder="Contoh: Meja Sablon B, Lini Mesin 04"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" wire:click="tutupModalUpdate" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="button" wire:click="simpanUpdateStatus" class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-xs font-bold text-white shadow-sm">Simpan</button>
                </div>
            </div>
        </div>
    @endif

</div>
