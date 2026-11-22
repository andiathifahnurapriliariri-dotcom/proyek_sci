<div class="space-y-6">

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
                    <h4 class="text-xs font-bold text-emerald-900">Berhasil!</h4>
                    <p class="text-xs text-emerald-700 font-medium mt-0.5">{{ $pesanSukses }}</p>
                </div>
            </div>
            <button type="button" wire:click="$set('pesanSukses', '')" class="text-emerald-500 hover:text-emerald-800 text-sm font-bold p-1">
                ✕
            </button>
        </div>
    @endif

    {{-- 1. HEADER HALAMAN NOTIFIKASI --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Notifikasi</h1>
                @if($totalBelumDibaca > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-500 text-white shadow-sm">
                        {{ $totalBelumDibaca }} Baru
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 font-normal mt-1">
                Pantau seluruh pembaruan pesanan SPK, konfirmasi pembayaran, progres pengerjaan workshop, dan peringatan batas ROP stok.
            </p>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            @if($totalBelumDibaca > 0)
                <button
                    type="button"
                    wire:click="tandaiSemuaDibaca"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-gray-900 shadow-sm transition active:scale-95"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 7 17l-5-5"/>
                        <path d="m22 10-7.5 7.5L13 16"/>
                    </svg>
                    <span>Tandai Semua Telah Dibaca</span>
                </button>
            @endif
        </div>
    </div>

    {{-- 2. ENAM KARTU STATISTIK KATEGORI NOTIFIKASI --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        {{-- Card 1: Belum Dibaca --}}
        <div
            wire:click="$set('filterStatus', 'belum_dibaca')"
            class="cursor-pointer bg-white rounded-2xl border p-4 shadow-sm hover:shadow transition {{ $filterStatus === 'belum_dibaca' ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-gray-200/70' }}"
        >
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-rose-600">Belum Dibaca</span>
                <div class="w-6 h-6 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600 tracking-tight">{{ number_format($totalBelumDibaca) }}</div>
            <p class="text-[11px] text-gray-400 font-medium mt-1">Perlu ditinjau</p>
        </div>

        {{-- Card 2: Pesanan --}}
        <div
            wire:click="$set('filterKategori', 'pesanan')"
            class="cursor-pointer bg-white rounded-2xl border p-4 shadow-sm hover:shadow transition {{ $filterKategori === 'pesanan' ? 'border-indigo-400 ring-2 ring-indigo-100 bg-indigo-50/20' : 'border-gray-200/70' }}"
        >
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Pesanan Masuk</span>
                <div class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ number_format($countKategori['pesanan']) }}</div>
            <p class="text-[11px] text-gray-400 font-medium mt-1">SPK & antrean</p>
        </div>

        {{-- Card 3: Pembayaran --}}
        <div
            wire:click="$set('filterKategori', 'pembayaran')"
            class="cursor-pointer bg-white rounded-2xl border p-4 shadow-sm hover:shadow transition {{ $filterKategori === 'pembayaran' ? 'border-emerald-400 ring-2 ring-emerald-100 bg-emerald-50/20' : 'border-gray-200/70' }}"
        >
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Pembayaran</span>
                <div class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ number_format($countKategori['pembayaran']) }}</div>
            <p class="text-[11px] text-gray-400 font-medium mt-1">Mutasi transfer</p>
        </div>

        {{-- Card 4: Produksi Workshop --}}
        <div
            wire:click="$set('filterKategori', 'produksi')"
            class="cursor-pointer bg-white rounded-2xl border p-4 shadow-sm hover:shadow transition {{ $filterKategori === 'produksi' ? 'border-amber-400 ring-2 ring-amber-100 bg-amber-50/20' : 'border-gray-200/70' }}"
        >
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Workshop</span>
                <div class="w-6 h-6 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ number_format($countKategori['produksi']) }}</div>
            <p class="text-[11px] text-gray-400 font-medium mt-1">Tahap pengerjaan</p>
        </div>

        {{-- Card 5: Peringatan ROP --}}
        <div
            wire:click="$set('filterKategori', 'stok_rop')"
            class="cursor-pointer bg-white rounded-2xl border p-4 shadow-sm hover:shadow transition {{ $filterKategori === 'stok_rop' ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-gray-200/70' }}"
        >
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-rose-600">Peringatan ROP</span>
                <div class="w-6 h-6 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600 tracking-tight">{{ number_format($countKategori['stok_rop']) }}</div>
            <p class="text-[11px] text-rose-500 font-semibold mt-1">Stok kritis</p>
        </div>

        {{-- Card 6: Siap Ambil / Selesai --}}
        <div
            wire:click="$set('filterKategori', 'selesai')"
            class="cursor-pointer bg-white rounded-2xl border p-4 shadow-sm hover:shadow transition {{ $filterKategori === 'selesai' ? 'border-teal-400 ring-2 ring-teal-100 bg-teal-50/20' : 'border-gray-200/70' }}"
        >
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-teal-700">Siap Ambil</span>
                <div class="w-6 h-6 rounded-lg bg-teal-50 flex items-center justify-center text-teal-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-teal-700 tracking-tight">{{ number_format($countKategori['selesai']) }}</div>
            <p class="text-[11px] text-teal-600 font-semibold mt-1">Selesai produksi</p>
        </div>
    </div>

    {{-- 3. TOOLBAR FILTER & SEARCH --}}
    <div class="bg-white rounded-2xl border border-gray-200/70 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Tab Status Dibaca --}}
        <div class="flex flex-wrap items-center gap-1.5">
            <button
                type="button"
                wire:click="$set('filterStatus', 'semua')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $filterStatus === 'semua' ? 'bg-gray-900 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
            >
                Semua ({{ number_format($totalSemua) }})
            </button>
            <button
                type="button"
                wire:click="$set('filterStatus', 'belum_dibaca')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $filterStatus === 'belum_dibaca' ? 'bg-rose-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
            >
                <span>Belum Dibaca</span>
                @if($totalBelumDibaca > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ $filterStatus === 'belum_dibaca' ? 'bg-white text-rose-600' : 'bg-rose-500 text-white' }}">
                        {{ $totalBelumDibaca }}
                    </span>
                @endif
            </button>
            <button
                type="button"
                wire:click="$set('filterStatus', 'sudah_dibaca')"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $filterStatus === 'sudah_dibaca' ? 'bg-gray-900 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
            >
                Sudah Dibaca ({{ number_format($totalSudahDibaca) }})
            </button>
        </div>

        {{-- Dropdown Kategori & Search Field --}}
        <div class="flex items-center gap-3 w-full md:w-auto">
            {{-- Dropdown Kategori --}}
            <select
                wire:model.live="filterKategori"
                class="rounded-xl border border-gray-200 bg-gray-50 py-2 px-3 text-xs font-semibold text-gray-700 focus:border-teal-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-600"
            >
                <option value="semua">Semua Kategori</option>
                <option value="pesanan">📋 Pesanan Baru / SPK</option>
                <option value="pembayaran">💳 Pembayaran Masuk</option>
                <option value="produksi">⚙️ Progres Produksi Workshop</option>
                <option value="stok_rop">⚠️ Peringatan Batas ROP</option>
                <option value="selesai">✅ Pesanan Selesai / Siap Ambil</option>
            </select>

            {{-- Kolom Pencarian --}}
            <div class="relative flex-1 md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" x2="16.65" y1="21" y2="16.65"/>
                    </svg>
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari notifikasi..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2 pl-8 pr-8 text-xs text-gray-800 placeholder-gray-400 focus:border-teal-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-600 transition"
                />
                @if(filled($search))
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600"
                    >
                        ✕
                    </button>
                @endif
            </div>

            {{-- Tombol Reset Filter --}}
            @if($filterKategori !== 'semua' || $filterStatus !== 'semua' || filled($search))
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition shrink-0"
                    title="Reset Filter"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                </button>
            @endif
        </div>
    </div>

    {{-- 4. DAFTAR NOTIFIKASI --}}
    <div class="space-y-3">
        @forelse($daftarNotifikasi as $notif)
            @php
                $isUnread = !$notif->dibaca;

                $config = match($notif->kategori) {
                    'pesanan' => [
                        'label' => 'SPK & Pesanan',
                        'icon' => '📋',
                        'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'border' => 'border-l-indigo-500',
                    ],
                    'pembayaran' => [
                        'label' => 'Pembayaran',
                        'icon' => '💳',
                        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'border' => 'border-l-emerald-500',
                    ],
                    'produksi' => [
                        'label' => 'Workshop / Produksi',
                        'icon' => '⚙️',
                        'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'border' => 'border-l-amber-500',
                    ],
                    'stok_rop' => [
                        'label' => 'Peringatan ROP',
                        'icon' => '⚠️',
                        'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'border' => 'border-l-rose-500',
                    ],
                    'selesai' => [
                        'label' => 'Siap Ambil / Selesai',
                        'icon' => '✅',
                        'badge' => 'bg-teal-50 text-teal-700 border-teal-200',
                        'border' => 'border-l-teal-500',
                    ],
                    default => [
                        'label' => 'Sistem',
                        'icon' => '🔔',
                        'badge' => 'bg-gray-50 text-gray-700 border-gray-200',
                        'border' => 'border-l-gray-400',
                    ],
                };
            @endphp

            <div class="group relative rounded-2xl bg-white border border-gray-200/80 p-4 shadow-sm transition hover:shadow-md {{ $isUnread ? 'border-l-4 ' . $config['border'] . ' bg-white' : 'opacity-90 bg-white/70' }}">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    {{-- Sisi Kiri: Ikon, Judul, Pesan & Detail --}}
                    <div class="flex items-start gap-3.5 flex-1 min-w-0">
                        {{-- Ikon Avatar Kategori --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-base shadow-xs {{ $isUnread ? 'bg-gray-100 font-bold' : 'bg-gray-50' }}">
                            {{ $config['icon'] }}
                        </div>

                        {{-- Konten Notifikasi --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border uppercase tracking-wider {{ $config['badge'] }}">
                                    {{ $config['label'] }}
                                </span>

                                @if($isUnread)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        Belum Dibaca
                                    </span>
                                @else
                                    <span class="text-[10px] font-semibold text-gray-400">
                                        Sudah dibaca {{ $notif->dibaca_pada ? $notif->dibaca_pada->diffForHumans() : '' }}
                                    </span>
                                @endif

                                <span class="text-[11px] text-gray-400">•</span>
                                <span class="text-[11px] text-gray-400" title="{{ $notif->created_at->format('d M Y, H:i:s') }}">
                                    {{ $notif->created_at->diffForHumans() }} ({{ $notif->created_at->format('d M Y, H:i') }} WIB)
                                </span>
                            </div>

                            <h3 class="text-sm font-extrabold text-gray-900 group-hover:text-teal-700 transition leading-snug">
                                {{ $notif->judul }}
                            </h3>

                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                {{ $notif->pesan }}
                            </p>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Aksi --}}
                    <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                        {{-- Tombol Buka Halaman / Detail Terkait --}}
                        @if($notif->link)
                            <button
                                type="button"
                                wire:click="bukaNotifikasi({{ $notif->id }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 text-teal-800 hover:bg-teal-600 hover:text-white border border-teal-200 text-xs font-bold transition shadow-xs active:scale-95"
                            >
                                <span>Buka Data</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>
                        @endif

                        <div class="flex items-center gap-1.5">
                            {{-- Tombol Tandai Sudah Dibaca --}}
                            @if($isUnread)
                                <button
                                    type="button"
                                    wire:click="tandaiDibaca({{ $notif->id }})"
                                    class="p-1.5 text-gray-400 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition"
                                    title="Tandai telah dibaca"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                </button>
                            @endif

                            {{-- Tombol Hapus Notifikasi --}}
                            <button
                                type="button"
                                wire:click="hapusNotifikasi({{ $notif->id }})"
                                wire:confirm="Hapus notifikasi ini?"
                                class="p-1.5 text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                title="Hapus notifikasi"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl border border-gray-200/70 p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-4 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>
                <h3 class="text-base font-black text-gray-900 mb-1">Tidak Ada Notifikasi</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto leading-relaxed">
                    @if($filterStatus === 'belum_dibaca')
                        Luar biasa! Seluruh notifikasi pemberitahuan telah Anda baca dan tidak ada tugas tertunda saat ini.
                    @elseif($filterKategori !== 'semua' || filled($search))
                        Tidak ditemukan pemberitahuan yang cocok dengan kriteria filter saat ini.
                    @else
                        Belum ada riwayat pemberitahuan sistem yang tercatat.
                    @endif
                </p>
                @if($filterKategori !== 'semua' || $filterStatus !== 'semua' || filled($search))
                    <div class="mt-4">
                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white text-xs font-bold hover:bg-black transition shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            <span>Reset Filter & Tampilkan Semua</span>
                        </button>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    {{-- 5. PAGINASI --}}
    @if($daftarNotifikasi->hasPages())
        <div class="pt-2">
            {{ $daftarNotifikasi->links() }}
        </div>
    @endif

</div>
