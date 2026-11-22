<div class="space-y-6">
    {{-- HEADER HALAMAN & AKSI CEPAT --}}
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <span>Tata Kelola</span>
                <span class="text-slate-300">›</span>
                <span class="text-teal-600 font-extrabold">Audit Log & Rekam Aktivitas</span>
            </div>
            <h1 class="mt-1.5 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Audit Log & Jejak Aktivitas</h1>
            <p class="mt-1 max-w-3xl text-xs sm:text-sm leading-relaxed text-gray-500">
                Pantau seluruh rekam jejak aktivitas pengguna, pembaruan status pesanan SPK, verifikasi pembayaran, dan pergerakan produksi workshop secara kronologis dan transparan.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            @if($search !== '' || $filterTipe !== 'semua' || $filterRentang !== 'semua' || $filterUser !== 'semua')
                <button
                    type="button"
                    wire:click="resetFilter"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-gray-900"
                >
                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset Filter</span>
                </button>
            @endif

            <button
                type="button"
                wire:click="eksporCsv"
                wire:loading.attr="disabled"
                wire:target="eksporCsv"
                class="inline-flex items-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800 disabled:cursor-wait disabled:opacity-60"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span wire:loading.remove wire:target="eksporCsv">Ekspor Log (.CSV)</span>
                <span wire:loading wire:target="eksporCsv">Menyiapkan CSV...</span>
            </button>
        </div>
    </div>

    {{-- KARTU RINGKASAN METRIK AUDIT --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Log --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Rekam Jejak</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900">{{ number_format($totalSemua, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">aktivitas tercatat</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Sejak awal sistem konveksi berjalan</p>
        </div>

        {{-- Card 2: Hari Ini --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Aktivitas Hari Ini</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600">{{ number_format($totalHariIni, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">kejadian baru</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>

        {{-- Card 3: Keuangan & Pembayaran --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Transaksi & Keuangan</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-blue-600">{{ number_format($totalKeuangan, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">transaksi kas/DP</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Verifikasi transfer & pelunasan</p>
        </div>

        {{-- Card 4: Operasional Workshop --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Operasional Workshop</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-amber-600">{{ number_format($totalOperasional, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">aktivitas pabrik</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Pemotongan, sablon, jahit & packing</p>
        </div>
    </div>

    {{-- FILTER & PENCARIAN --}}
    <div class="rounded-2xl border border-gray-200/80 bg-white p-4 sm:p-5 shadow-sm space-y-4">
        {{-- Baris 1: Search + Dropdowns --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            {{-- Input Pencarian --}}
            <div class="md:col-span-5 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.350ms="search"
                    placeholder="Cari aksi, no. referensi (ORD/PAY), PIC, atau catatan..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-9 text-xs font-medium text-gray-800 placeholder-gray-400 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                @if($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Dropdown Pengguna / PIC --}}
            <div class="md:col-span-3">
                <select
                    wire:model.live="filterUser"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="semua">👤 Semua Pengguna / PIC</option>
                    @foreach($daftarUser as $user)
                        <option value="{{ $user }}">{{ $user }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Rentang Waktu --}}
            <div class="md:col-span-2">
                <select
                    wire:model.live="filterRentang"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="semua">📅 Semua Waktu</option>
                    <option value="hari_ini">Hari Ini</option>
                    <option value="7_hari">7 Hari Terakhir</option>
                    <option value="30_hari">30 Hari Terakhir</option>
                    <option value="bulan_ini">Bulan Ini</option>
                </select>
            </div>

            {{-- Dropdown Jumlah Per Halaman --}}
            <div class="md:col-span-2">
                <select
                    wire:model.live="perPage"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="15">Tampilkan 15 Baris</option>
                    <option value="25">Tampilkan 25 Baris</option>
                    <option value="50">Tampilkan 50 Baris</option>
                    <option value="100">Tampilkan 100 Baris</option>
                </select>
            </div>
        </div>

        {{-- Baris 2: Filter Tabs Kategori Cepat --}}
        <div class="flex flex-wrap items-center gap-1.5 pt-1 border-t border-gray-100">
            <span class="text-[11px] font-bold text-gray-400 mr-1.5 uppercase tracking-wide">Kategori:</span>

            @php
                $tipeTabs = [
                    'semua'      => ['label' => 'Semua Kategori', 'icon' => 'grid'],
                    'pesanan'    => ['label' => 'Pesanan (SPK)', 'icon' => 'order'],
                    'pembayaran' => ['label' => 'Pembayaran & DP', 'icon' => 'pay'],
                    'produksi'   => ['label' => 'Workshop / Produksi', 'icon' => 'prod'],
                    'stok'       => ['label' => 'Persediaan & Stok', 'icon' => 'stock'],
                ];
            @endphp

            @foreach($tipeTabs as $kunci => $tab)
                <button
                    type="button"
                    wire:click="$set('filterTipe', '{{ $kunci }}')"
                    class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterTipe === $kunci ? 'bg-black text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                >
                    @if($kunci === 'pesanan')
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    @elseif($kunci === 'pembayaran')
                        <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                    @elseif($kunci === 'produksi')
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                    @elseif($kunci === 'stok')
                        <span class="h-2 w-2 rounded-full bg-purple-400"></span>
                    @endif
                    <span>{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- TABEL AUDIT LOG --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/75 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 pl-5 pr-3">Waktu & Tanggal</th>
                        <th class="py-3.5 px-3">Pengguna / PIC</th>
                        <th class="py-3.5 px-3">Kategori</th>
                        <th class="py-3.5 px-3">Aktivitas & Keterangan</th>
                        <th class="py-3.5 px-3">Referensi</th>
                        <th class="py-3.5 pl-3 pr-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($logs as $log)
                        @php
                            $tipeLower = strtolower($log->tipe ?? 'sistem');
                            $badgeClass = match($tipeLower) {
                                'pesanan'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'pembayaran' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'produksi'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                'stok'       => 'bg-purple-50 text-purple-700 border-purple-200',
                                'auth'       => 'bg-rose-50 text-rose-700 border-rose-200',
                                default      => 'bg-slate-50 text-slate-700 border-slate-200',
                            };

                            $dotColor = match($tipeLower) {
                                'pesanan'    => 'bg-emerald-500',
                                'pembayaran' => 'bg-blue-500',
                                'produksi'   => 'bg-amber-500',
                                'stok'       => 'bg-purple-500',
                                'auth'       => 'bg-rose-500',
                                default      => 'bg-slate-500',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition group">
                            {{-- Kolom 1: Waktu & Tanggal --}}
                            <td class="py-3.5 pl-5 pr-3 whitespace-nowrap">
                                <div class="font-bold text-gray-900">
                                    {{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }} WIB
                                </div>
                                <div class="text-[11px] font-medium text-gray-500 mt-0.5">
                                    {{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-' }}
                                    <span class="text-gray-400">({{ $log->created_at ? $log->created_at->diffForHumans() : '' }})</span>
                                </div>
                            </td>

                            {{-- Kolom 2: Pengguna / PIC --}}
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-[11px] font-black text-white">
                                        {{ strtoupper(substr($log->nama_user ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 truncate max-w-[150px]">{{ $log->nama_user ?? 'Sistem Otomatis' }}</p>
                                        <p class="text-[10px] text-gray-400">ID User: {{ $log->user_id ?? 'System' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom 3: Kategori --}}
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider {{ $badgeClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }}"></span>
                                    <span>{{ $log->tipe ?? 'Umum' }}</span>
                                </span>
                            </td>

                            {{-- Kolom 4: Aktivitas & Keterangan --}}
                            <td class="py-3.5 px-3">
                                <div class="font-bold text-gray-900 text-xs leading-snug">
                                    {{ $log->aksi }}
                                </div>
                                @if($log->keterangan)
                                    <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-1 max-w-md">
                                        {{ $log->keterangan }}
                                    </p>
                                @endif
                            </td>

                            {{-- Kolom 5: Referensi --}}
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                @if($log->referensi)
                                    <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 font-mono text-[11px] font-bold text-gray-700 border border-gray-200">
                                        {{ $log->referensi }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            {{-- Kolom 6: Aksi --}}
                            <td class="py-3.5 pl-3 pr-5 text-right whitespace-nowrap">
                                <button
                                    type="button"
                                    wire:click="lihatDetail({{ $log->id }})"
                                    class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-2.5 py-1 text-[11px] font-bold text-gray-700 shadow-2xs transition hover:bg-gray-100 hover:text-black group-hover:border-gray-300"
                                >
                                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-50 text-gray-400 border border-gray-100">
                                    <svg class="h-7 w-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="mt-3 text-sm font-bold text-gray-900">Tidak Ada Log Aktivitas Ditemukan</h3>
                                <p class="mt-1 text-xs text-gray-500 max-w-sm mx-auto">
                                    Tidak ada data riwayat aktivitas yang sesuai dengan kata kunci atau filter yang Anda pilih saat ini.
                                </p>
                                @if($search !== '' || $filterTipe !== 'semua' || $filterRentang !== 'semua' || $filterUser !== 'semua')
                                    <button
                                        type="button"
                                        wire:click="resetFilter"
                                        class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-black px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-gray-800 transition"
                                    >
                                        <span>Reset Semua Filter</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINASI & INFO RECORD --}}
        @if($logs->hasPages() || $logs->total() > 0)
            <div class="border-t border-gray-200 bg-gray-50/50 px-5 py-3.5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
                <div>
                    Menampilkan <span class="font-bold text-gray-900">{{ $logs->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-900">{{ $logs->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $logs->total() }}</span> log aktivitas
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL DETAIL LOG --}}
    @if($showModalDetail && $selectedLog)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity"
            x-data
            @keydown.escape.window="$wire.tutupModalDetail()"
        >
            <div
                class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-gray-200 overflow-hidden transform transition-all"
                @click.outside="$wire.tutupModalDetail()"
            >
                {{-- Header Modal --}}
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-6 py-4">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-700 font-bold border border-teal-200">
                            #{{ $selectedLog->id }}
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">Rincian Log Aktivitas</h3>
                            <p class="text-[11px] text-gray-500">ID Aktivitas: {{ $selectedLog->id }}</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="tutupModalDetail"
                        class="rounded-xl p-1.5 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Isi Modal --}}
                <div class="p-6 space-y-4">
                    {{-- Aksi Utama --}}
                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Aksi / Tindakan</span>
                        <p class="mt-1 text-sm font-black text-slate-900">{{ $selectedLog->aksi }}</p>
                    </div>

                    {{-- Grid Metadata --}}
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Pengguna / Pelaksana</span>
                            <p class="mt-1 font-bold text-gray-900">{{ $selectedLog->nama_user ?? 'Sistem' }}</p>
                            <p class="text-[10px] text-gray-500">ID User: {{ $selectedLog->user_id ?? 'System' }}</p>
                        </div>

                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Kategori Modul</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider {{ match(strtolower($selectedLog->tipe ?? '')) {
                                    'pesanan'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'pembayaran' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'produksi'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'stok'       => 'bg-purple-50 text-purple-700 border-purple-200',
                                    default      => 'bg-slate-50 text-slate-700 border-slate-200',
                                } }}">
                                    <span>{{ ucfirst($selectedLog->tipe ?? 'Umum') }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Nomor Referensi</span>
                            <p class="mt-1 font-mono font-bold text-gray-900">
                                {{ $selectedLog->referensi ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Waktu Transaksi</span>
                            <p class="mt-1 font-bold text-gray-900">
                                {{ $selectedLog->created_at ? $selectedLog->created_at->format('H:i:s') : '-' }} WIB
                            </p>
                            <p class="text-[10px] text-gray-500">
                                {{ $selectedLog->created_at ? $selectedLog->created_at->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Keterangan Lengkap --}}
                    @if($selectedLog->keterangan)
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Keterangan Tambahan</span>
                            <div class="mt-1.5 rounded-xl border border-gray-200 bg-gray-50/70 p-3.5 text-xs text-gray-700 leading-relaxed font-mono whitespace-pre-wrap">
                                {{ $selectedLog->keterangan }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer Modal --}}
                <div class="border-t border-gray-100 bg-gray-50/80 px-6 py-3.5 flex justify-end">
                    <button
                        type="button"
                        wire:click="tutupModalDetail"
                        class="rounded-xl bg-black px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-gray-800 transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

