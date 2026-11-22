<div class="space-y-6">
    {{-- HEADER HALAMAN & AKSI CEPAT --}}
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <span>Arsip Produksi</span>
                <span class="text-slate-300">›</span>
                <span class="text-teal-600 font-extrabold">Riwayat Pesanan Selesai</span>
            </div>
            <h1 class="mt-1.5 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Riwayat &amp; Arsip Pemesanan</h1>
            <p class="mt-1 max-w-3xl text-xs sm:text-sm leading-relaxed text-gray-500">
                Daftar arsip pesanan konveksi yang telah rampung diproduksi, diserahkan kepada pelanggan, serta riwayat catatan keuangan dan ketepatan waktu workshop.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            @if($search !== '' || $tahun !== 'semua' || $filterStatus !== 'semua_selesai' || $jenisPakaian !== 'semua')
                <button
                    type="button"
                    wire:click="resetFilters"
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
                wire:click="eksporArsip"
                wire:loading.attr="disabled"
                wire:target="eksporArsip"
                class="inline-flex items-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800 disabled:cursor-wait disabled:opacity-60"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span wire:loading.remove wire:target="eksporArsip">Ekspor Arsip (.CSV)</span>
                <span wire:loading wire:target="eksporArsip">Menyiapkan...</span>
            </button>
        </div>
    </div>

    {{-- KARTU RINGKASAN METRIK ARSIP PESANAN --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Pesanan --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Pesanan Arsip</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900">{{ number_format($totalPesanan, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">pesanan</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">
                {{ $tahun === 'semua' ? 'Semua periode tahun' : 'Periode tahun ' . $tahun }}
            </p>
        </div>

        {{-- Card 2: Total Pcs --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Baju Jadi Diproduksi</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-blue-600">{{ number_format($totalPcs, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">potong / pcs</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Akumulasi pakaian selesai diproduksi</p>
        </div>

        {{-- Card 3: Realisasi Nilai Omset --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Nilai Penjualan</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-xl sm:text-2xl font-black text-emerald-600">Rp {{ number_format($totalNilaiPesanan, 0, ',', '.') }}</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Omset dari pesanan arsip terpilih</p>
        </div>

        {{-- Card 4: Ketepatan Waktu --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Selesai Tepat Waktu</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-amber-600">{{ number_format($persentaseTepatWaktu, 1, ',', '.') }}%</span>
                <span class="text-xs font-semibold text-gray-400">skor SLA</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">{{ $pesananTepatWaktu }} pesanan selesai sesuai batas jadwal</p>
        </div>
    </div>

    {{-- FILTER, PENCARIAN & STATUS TABS --}}
    <div class="rounded-2xl border border-gray-200/80 bg-white p-4 sm:p-5 shadow-sm space-y-4">
        {{-- Baris 1: Pencarian & Dropdown --}}
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
                    placeholder="Cari nomor pesanan, nama pelanggan, kain, atau catatan..."
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

            {{-- Filter Tahun --}}
            <div class="md:col-span-2">
                <select
                    wire:model.live="tahun"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="semua">📅 Semua Tahun</option>
                    @foreach($tahunTersedia as $t)
                        <option value="{{ $t }}">Tahun {{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Jenis Kain --}}
            <div class="md:col-span-3">
                <select
                    wire:model.live="jenisPakaian"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="semua">👕 Semua Jenis Kain</option>
                    @foreach($jenisPakaianTersedia as $jk)
                        <option value="{{ $jk }}">{{ $jk }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Urutan & Baris --}}
            <div class="md:col-span-2 flex gap-2">
                <select
                    wire:model.live="urutan"
                    class="w-full rounded-xl border border-gray-200 bg-white px-2.5 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                </select>
                <select
                    wire:model.live="perPage"
                    class="w-20 shrink-0 rounded-xl border border-gray-200 bg-white px-2 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        {{-- Baris 2: Tabs Filter Status Riwayat --}}
        <div class="flex flex-wrap items-center gap-1.5 pt-1 border-t border-gray-100">
            <span class="text-[11px] font-bold text-gray-400 mr-1.5 uppercase tracking-wide">Status:</span>

            <button
                type="button"
                wire:click="$set('filterStatus', 'semua_selesai')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterStatus === 'semua_selesai' ? 'bg-black text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
            >
                <span class="h-2 w-2 rounded-full bg-teal-400"></span>
                <span>Semua Selesai &amp; Siap</span>
            </button>

            <button
                type="button"
                wire:click="$set('filterStatus', 'selesai')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterStatus === 'selesai' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
            >
                <span class="h-2 w-2 rounded-full bg-current"></span>
                <span>Selesai Diserahkan</span>
            </button>

            <button
                type="button"
                wire:click="$set('filterStatus', 'siap_diambil')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterStatus === 'siap_diambil' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}"
            >
                <span class="h-2 w-2 rounded-full bg-current"></span>
                <span>Siap Diambil</span>
            </button>

            <button
                type="button"
                wire:click="$set('filterStatus', 'semua')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ $filterStatus === 'semua' ? 'bg-slate-800 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
            >
                <span>Semua Status Arsip</span>
            </button>
        </div>
    </div>

    {{-- TABEL RIWAYAT PESANAN --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/75 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 pl-5 pr-3">No. Pesanan</th>
                        <th class="py-3.5 px-3">Pelanggan</th>
                        <th class="py-3.5 px-3">Spesifikasi Pakaian</th>
                        <th class="py-3.5 px-3 text-center">Jumlah</th>
                        <th class="py-3.5 px-3">Jadwal &amp; Selesai</th>
                        <th class="py-3.5 px-3 text-right">Total Nilai</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 pl-3 pr-5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($daftarPesanan as $pesanan)
                        @php
                            $statusBadge = match($pesanan->status_pemesanan) {
                                'selesai'             => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                'siap_diambil'        => 'bg-teal-50 text-teal-800 border-teal-200',
                                'dalam_produksi'      => 'bg-amber-50 text-amber-800 border-amber-200',
                                'antrian_produksi'    => 'bg-blue-50 text-blue-800 border-blue-200',
                                'menunggu_pembayaran' => 'bg-slate-50 text-slate-800 border-slate-200',
                                default               => 'bg-gray-50 text-gray-800 border-gray-200',
                            };

                            $labelStatus = match($pesanan->status_pemesanan) {
                                'selesai'             => 'Selesai & Diserahkan',
                                'siap_diambil'        => 'Siap Diambil',
                                'dalam_produksi'      => 'Dalam Pengerjaan',
                                'antrian_produksi'    => 'Masuk Antrean',
                                'menunggu_pembayaran' => 'Menunggu DP',
                                default               => ucfirst(str_replace('_', ' ', $pesanan->status_pemesanan)),
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition group">
                            {{-- No. Pesanan --}}
                            <td class="py-4 pl-5 pr-3 whitespace-nowrap">
                                <span class="rounded-lg bg-gray-100 px-2.5 py-1 font-mono text-[11px] font-bold text-gray-800 border border-gray-200">
                                    {{ $pesanan->nomor_pemesanan }}
                                </span>
                            </td>

                            {{-- Pelanggan --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-[10px] font-black text-white">
                                        {{ strtoupper(substr($pesanan->pelanggan?->nama ?? 'P', 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 truncate max-w-[170px]">
                                            {{ $pesanan->pelanggan?->nama ?? 'Pelanggan Umum' }}
                                        </p>
                                        <p class="text-[11px] text-gray-400">
                                            {{ $pesanan->pelanggan?->nomor_telepon ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Spesifikasi Pakaian --}}
                            <td class="py-4 px-3">
                                <div class="font-bold text-gray-900">
                                    {{ $pesanan->jenis_kain }}
                                </div>
                                <div class="text-[11px] text-gray-500 flex items-center gap-1.5 mt-0.5">
                                    <span>Warna: <strong class="text-gray-700 font-semibold">{{ $pesanan->warna }}</strong></span>
                                    <span>·</span>
                                    <span>Ukuran: <strong class="text-gray-700 font-semibold">{{ $pesanan->ukuran_baju }}</strong></span>
                                </div>
                            </td>

                            {{-- Jumlah --}}
                            <td class="py-4 px-3 text-center whitespace-nowrap">
                                <span class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-black text-gray-800">
                                    {{ number_format($pesanan->jumlah) }} pcs
                                </span>
                            </td>

                            {{-- Jadwal & Selesai --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                <div class="text-[11px] text-gray-500">
                                    Order: <strong class="text-gray-800 font-semibold">{{ $pesanan->tanggal_pemesanan ? $pesanan->tanggal_pemesanan->translatedFormat('d M Y') : '-' }}</strong>
                                </div>
                                <div class="text-[11px] text-teal-700 mt-0.5 font-medium">
                                    Selesai: <strong class="font-bold">{{ $pesanan->produksi?->tanggal_selesai ? $pesanan->produksi->tanggal_selesai->translatedFormat('d M Y') : ($pesanan->tanggal_selesai_estimasi ? $pesanan->tanggal_selesai_estimasi->translatedFormat('d M Y') : '-') }}</strong>
                                </div>
                            </td>

                            {{-- Total Nilai --}}
                            <td class="py-4 px-3 text-right whitespace-nowrap">
                                <div class="font-black text-gray-900">
                                    Rp {{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-0.5">
                                    @ Rp {{ number_format((float) $pesanan->harga_satuan, 0, ',', '.') }}
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider {{ $statusBadge }}">
                                        <span>{{ $labelStatus }}</span>
                                    </span>
                                    <div>
                                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-bold {{ $pesanan->status_pembayaran === 'lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ ucfirst($pesanan->status_pembayaran) }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Tindakan --}}
                            <td class="py-4 pl-3 pr-5 text-right whitespace-nowrap">
                                <button
                                    type="button"
                                    wire:click="lihatDetail({{ $pesanan->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 shadow-2xs transition hover:bg-gray-100 hover:text-black group-hover:border-gray-300"
                                >
                                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Detail SPK</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-50 text-gray-400 border border-gray-100">
                                    <svg class="h-7 w-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h3 class="mt-3 text-sm font-bold text-gray-900">Tidak Ada Riwayat Pesanan Ditemukan</h3>
                                <p class="mt-1 text-xs text-gray-500 max-w-sm mx-auto">
                                    Tidak ada data pesanan yang cocok dengan kata kunci pencarian, filter tahun, atau kategori status yang Anda pilih.
                                </p>
                                @if($search !== '' || $tahun !== 'semua' || $filterStatus !== 'semua_selesai' || $jenisPakaian !== 'semua')
                                    <button
                                        type="button"
                                        wire:click="resetFilters"
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

        {{-- PAGINASI --}}
        @if($daftarPesanan->hasPages() || $daftarPesanan->total() > 0)
            <div class="border-t border-gray-200 bg-gray-50/50 px-5 py-3.5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
                <div>
                    Menampilkan <span class="font-bold text-gray-900">{{ $daftarPesanan->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-900">{{ $daftarPesanan->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $daftarPesanan->total() }}</span> arsip pesanan
                </div>
                <div>
                    {{ $daftarPesanan->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL DETAIL SPK & RIWAYAT PESANAN --}}
    @if($showModalDetail && $selectedPesanan)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity"
            x-data
            @keydown.escape.window="$wire.tutupModalDetail()"
        >
            <div
                class="w-full max-w-2xl rounded-2xl bg-white shadow-2xl border border-gray-200 overflow-hidden transform transition-all"
                @click.outside="$wire.tutupModalDetail()"
            >
                {{-- Header Modal --}}
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-teal-700 font-mono font-bold text-xs border border-teal-200">
                            SPK
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold text-gray-900">Arsip SPK: {{ $selectedPesanan->nomor_pemesanan }}</h3>
                                <span class="rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-black px-2 py-0.5">
                                    {{ ucfirst(str_replace('_', ' ', $selectedPesanan->status_pemesanan)) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-500">ID Pesanan #{{ $selectedPesanan->id }} · Dikelola oleh {{ $selectedPesanan->admin?->name ?? 'Admin COTTON.id' }}</p>
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

                {{-- Body Modal --}}
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    {{-- 1. Kartu Informasi Pemesan --}}
                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Identitas Pemesan</span>
                            @if($selectedPesanan->pelanggan?->nomor_telepon)
                                <a
                                    href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $selectedPesanan->pelanggan->nomor_telepon)) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-2.5 py-1 text-[10px] font-bold text-white shadow-2xs hover:bg-emerald-700 transition"
                                >
                                    <span>Hubungi WhatsApp</span>
                                </a>
                            @endif
                        </div>
                        <p class="text-sm font-black text-slate-900">{{ $selectedPesanan->pelanggan?->nama ?? 'Pelanggan Umum' }}</p>
                        <p class="text-xs text-slate-600 mt-0.5">Telp: {{ $selectedPesanan->pelanggan?->nomor_telepon ?? '-' }} · Email: {{ $selectedPesanan->pelanggan?->email ?? '-' }}</p>
                        @if($selectedPesanan->pelanggan?->alamat)
                            <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">{{ $selectedPesanan->pelanggan->alamat }}</p>
                        @endif
                    </div>

                    {{-- 2. Spesifikasi Produk & Pakaian --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Bahan / Kain</span>
                            <p class="mt-1 font-bold text-gray-900">{{ $selectedPesanan->jenis_kain }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Warna Kain</span>
                            <p class="mt-1 font-bold text-gray-900">{{ $selectedPesanan->warna }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Ukuran</span>
                            <p class="mt-1 font-bold text-gray-900">{{ $selectedPesanan->ukuran_baju }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Jumlah Total</span>
                            <p class="mt-1 font-black text-teal-700 text-sm">{{ number_format($selectedPesanan->jumlah) }} pcs</p>
                        </div>
                    </div>

                    {{-- 3. Detail Desain & Perintah Kerja --}}
                    @if($selectedPesanan->detail_desain || $selectedPesanan->catatan)
                        <div class="rounded-xl border border-gray-100 p-4 bg-gray-50/70 space-y-2">
                            @if($selectedPesanan->detail_desain)
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Instruksi Desain &amp; Sablon / Bordir:</span>
                                    <p class="text-gray-800 leading-relaxed mt-0.5 font-medium">{{ $selectedPesanan->detail_desain }}</p>
                                </div>
                            @endif
                            @if($selectedPesanan->catatan)
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Catatan Khusus SPK:</span>
                                    <p class="text-gray-600 leading-relaxed mt-0.5">{{ $selectedPesanan->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- 4. Jadwal & Timeline Produksi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Tanggal Pesan</span>
                            <p class="mt-1 font-bold text-gray-900">{{ $selectedPesanan->tanggal_pemesanan ? $selectedPesanan->tanggal_pemesanan->translatedFormat('d F Y') : '-' }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-3 bg-white">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Target Estimasi</span>
                            <p class="mt-1 font-bold text-gray-900">{{ $selectedPesanan->tanggal_selesai_estimasi ? $selectedPesanan->tanggal_selesai_estimasi->translatedFormat('d F Y') : '-' }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-100 p-3 bg-teal-50/50">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-teal-800">Selesai Aktual Pabrik</span>
                            <p class="mt-1 font-black text-teal-900">{{ $selectedPesanan->produksi?->tanggal_selesai ? $selectedPesanan->produksi->tanggal_selesai->translatedFormat('d F Y') : '-' }}</p>
                        </div>
                    </div>

                    {{-- 5. Rincian Biaya & Pembayaran --}}
                    <div class="rounded-xl border border-gray-200 p-4 bg-white space-y-2.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Rincian Finansial Pesanan</span>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-600">Harga Satuan Pakaian</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format((float) $selectedPesanan->harga_satuan, 0, ',', '.') }} / pcs</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-600">Total Tagihan ({{ number_format($selectedPesanan->jumlah) }} pcs)</span>
                            <span class="font-black text-gray-900 text-sm">Rp {{ number_format((float) $selectedPesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs pt-2 border-t border-gray-100">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-black text-emerald-800">
                                {{ strtoupper($selectedPesanan->status_pembayaran) }}
                            </span>
                        </div>
                        @if($selectedPesanan->pembayaran->count() > 0)
                            <div class="mt-2 pt-2 border-t border-gray-100 space-y-1.5">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Riwayat Transaksi Masuk:</span>
                                @foreach($selectedPesanan->pembayaran as $byr)
                                    <div class="flex justify-between items-center text-[11px] bg-gray-50 px-2.5 py-1.5 rounded-lg">
                                        <div>
                                            <span class="font-mono font-bold text-gray-800">{{ $byr->kode_pembayaran }}</span>
                                            <span class="text-gray-400 ml-1">({{ $byr->jenis_pembayaran }} - {{ $byr->metode_pembayaran }})</span>
                                        </div>
                                        <span class="font-bold text-emerald-700">Rp {{ number_format((float) $byr->jumlah_bayar, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="border-t border-gray-100 bg-gray-50/80 px-6 py-3.5 flex justify-end">
                    <button
                        type="button"
                        wire:click="tutupModalDetail"
                        class="rounded-xl bg-black px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-gray-800 transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
