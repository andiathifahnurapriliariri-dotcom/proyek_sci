<div class="space-y-6">
    {{-- ALERT NOTIFIKASI SUKSES --}}
    @if($pesanSukses)
        <div class="flex items-center justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-xs">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div>
                    <h2 class="text-xs font-bold text-emerald-900">Aksi Berhasil</h2>
                    <p class="text-xs text-emerald-700 mt-0.5">{{ $pesanSukses }}</p>
                </div>
            </div>
            <button type="button" wire:click="$set('pesanSukses', '')" class="rounded-lg p-1 text-emerald-700 hover:bg-emerald-100" aria-label="Tutup notifikasi">✕</button>
        </div>
    @endif

    {{-- HEADER HALAMAN & AKSI CEPAT --}}
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold text-gray-500">
                <span>Manajemen Inventori</span>
                <span>›</span>
                <span class="text-gray-800">Persediaan & Mutasi Stok</span>
                <span class="text-teal-700">• Pembaruan {{ now()->translatedFormat('d M Y') }}</span>
            </div>

            @if($activeTab === 'persediaan')
                <h1 class="mt-2 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Persediaan Produk Baju Jadi (Stok Pakaian)</h1>
                <p class="mt-1 max-w-3xl text-sm leading-relaxed text-gray-500">Pantau stok baju siap jual/kirim, stok penyangga (safety stock), serta ambang batas Reorder Point (ROP) untuk setiap ukuran.</p>
            @else
                <h1 class="mt-2 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Riwayat Keluar Masuk Baju Jadi (Mutasi Stok Pakaian)</h1>
                <p class="mt-1 max-w-3xl text-sm leading-relaxed text-gray-500">Catatan pergerakan produk pakaian yang selesai diproduksi masuk ke rak siap kirim atau dikeluarkan untuk pengiriman pesanan dan serah terima pelanggan.</p>
            @endif
        </div>

        {{-- TOMBOL AKSI SESUAI TAB YANG DIBUKA --}}
        <div class="flex flex-wrap items-center gap-2">
            @if($activeTab === 'persediaan')
                <button type="button" wire:click="bukaModalCreate" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 cursor-pointer active:scale-95">
                    <svg class="h-4 w-4 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/><rect x="3" y="3" width="18" height="18" rx="3"/></svg>
                    Tambah SKU Produk
                </button>
                <button type="button" wire:click="bukaModalMasuk" class="inline-flex items-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800 cursor-pointer active:scale-95">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/><rect x="3" y="3" width="18" height="18" rx="3"/></svg>
                    Catat Masuk Produk Jadi
                </button>
                <button type="button" wire:click="bukaModalKeluar" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 cursor-pointer active:scale-95">
                    <svg class="h-4 w-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h12v11H3zM15 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="18.5" cy="19" r="1.5"/></svg>
                    Catat Baju Keluar
                </button>
            @else
                <button type="button" wire:click="bukaModalMasuk" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 cursor-pointer active:scale-95">
                    <svg class="h-4 w-4 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/><path d="M4 20h16"/></svg>
                    Catat Baju Masuk (Dari Produksi)
                </button>
                <button type="button" wire:click="bukaModalKeluar" class="inline-flex items-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800 cursor-pointer active:scale-95">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h12v11H3zM15 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="18.5" cy="19" r="1.5"/></svg>
                    Catat Baju Keluar (Kirim/Ambil)
                </button>
            @endif
        </div>
    </div>

    {{-- NAVIGASI TAB UTAMA: PERSEDIAAN PRODUK VS RIWAYAT MUTASI --}}
    <div class="flex items-center gap-2 border-b border-gray-200 pb-3">
        <button
            type="button"
            wire:click="setMainTab('persediaan')"
            @class([
                'inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-xs cursor-pointer',
                'bg-black text-white shadow-sm' => $activeTab === 'persediaan',
                'bg-white text-gray-600 hover:text-gray-900 hover:bg-gray-50 border border-gray-200' => $activeTab !== 'persediaan',
            ])
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                <path d="m3.3 7 8.7 5 8.7-5"/>
                <path d="M12 22V12"/>
            </svg>
            <span>Persediaan Produk</span>
            <span @class([
                'ml-1 px-2 py-0.5 rounded-full text-[10px] font-black',
                'bg-teal-500 text-white' => $activeTab === 'persediaan',
                'bg-gray-100 text-gray-700' => $activeTab !== 'persediaan',
            ])>{{ $jumlahSku }} SKU</span>
        </button>

        <button
            type="button"
            wire:click="setMainTab('mutasi')"
            @class([
                'inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-xs cursor-pointer',
                'bg-black text-white shadow-sm' => $activeTab === 'mutasi',
                'bg-white text-gray-600 hover:text-gray-900 hover:bg-gray-50 border border-gray-200' => $activeTab !== 'mutasi',
            ])
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m17 2 4 4-4 4"/>
                <path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                <path d="m7 22-4-4 4-4"/>
                <path d="M21 13v1a4 4 0 0 1-4 4H3"/>
            </svg>
            <span>Riwayat Mutasi</span>
            <span @class([
                'ml-1 px-2 py-0.5 rounded-full text-[10px] font-black',
                'bg-teal-500 text-white' => $activeTab === 'mutasi',
                'bg-gray-100 text-gray-700' => $activeTab !== 'mutasi',
            ])>{{ $jumlahMutasi }} Catatan</span>
        </button>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 1: PERSEDIAAN PRODUK BAJU JADI --}}
    {{-- ======================================================== --}}
    @if($activeTab === 'persediaan')
        {{-- 4 KARTU STATISTIK PERSEDIAAN --}}
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Total stok baju ready</p>
                        <p class="mt-4 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($totalStok, 0, ',', '.') }}</span><span class="text-sm text-gray-500">Pcs</span></p>
                        <p class="mt-1 text-[11px] text-gray-500">Tersedia siap ambil/kirim di rak baju jadi</p>
                    </div>
                    <span class="rounded-xl bg-gray-100 p-2 text-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 4h12l2 5H4z"/><path d="M5 9v11h14V9M9 13h6"/></svg>
                    </span>
                </div>
                <p class="mt-4 flex items-center gap-1.5 text-[10px] font-semibold text-teal-700"><span class="h-2 w-2 rounded-full bg-teal-600"></span>Siap kirim ke pemesan / display</p>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-rose-600">Perlu diproduksi ulang</p>
                        <p class="mt-4 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ $jumlahKritis }}</span><span class="text-sm text-rose-600">Ukuran</span></p>
                        <p class="mt-1 text-[11px] text-gray-500">Varian ukuran di bawah batas ROP</p>
                    </div>
                    <span class="rounded-xl bg-rose-100 p-2 text-rose-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m10.3 3.9-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3l-8-14a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                    </span>
                </div>
                <p class="mt-4 flex items-center gap-1.5 text-[10px] font-semibold text-rose-700"><span class="h-2 w-2 rounded-full bg-rose-600"></span>Stok per ukuran menyentuh batas minimum</p>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Baju siap diambil pelanggan</p>
                        <p class="mt-4 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($jumlahSiapDiambil, 0, ',', '.') }}</span><span class="text-sm text-gray-500">Pcs</span></p>
                        <p class="mt-1 text-[11px] text-gray-500">Pesanan selesai produksi siap diserahkan</p>
                    </div>
                    <span class="rounded-xl bg-gray-100 p-2 text-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 4V2h6v2M9 12h6M9 16h6"/></svg>
                    </span>
                </div>
                <p class="mt-4 flex items-center gap-1.5 text-[10px] font-semibold text-teal-700"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="14" height="10" rx="2"/><path d="M16 10h3l3 3v4h-6z"/><circle cx="7" cy="19" r="2"/><circle cx="18" cy="19" r="2"/></svg>Menunggu kurir & penyerahan pelanggan</p>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Keamanan varian ukuran</p>
                        <p class="mt-4 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ $persentaseAman }}%</span><span class="text-sm text-teal-700">Aman</span></p>
                        <p class="mt-1 text-[11px] text-gray-500">Ketersediaan varian ukuran per produk terjaga</p>
                    </div>
                    <span class="rounded-xl bg-gray-100 p-2 text-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3 19 6v5c0 4.5-3 7.5-7 10-4-2.5-7-5.5-7-10V6z"/><path d="m9 12 2 2 4-4"/></svg>
                    </span>
                </div>
                <p class="mt-4 flex items-center gap-1.5 text-[10px] font-semibold text-gray-500"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>{{ $jumlahSku - $jumlahKritis }} dari {{ $jumlahSku }} SKU di atas safety stock</p>
            </article>
        </section>

        {{-- BANNER PERINGATAN ROP --}}
        <section class="flex flex-col gap-4 rounded-2xl border-l-4 border-rose-600 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <div class="flex items-start gap-3">
                <span class="rounded-xl bg-rose-100 p-2 text-rose-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>
                </span>
                <div>
                    <h2 class="text-xs font-bold text-gray-900">Peringatan batas minimum (stok per ukuran menipis)</h2>
                    @if($stokKritis->isNotEmpty())
                        <p class="mt-1 text-xs leading-relaxed text-gray-600">
                            {{ $jumlahKritis }} varian ukuran perlu diproduksi:
                            @foreach($stokKritis->take(3) as $stok)
                                <span wire:key="warning-{{ $stok->id }}" class="font-semibold text-rose-700">{{ $stok->nama_produk }} {{ $stok->warna }} Ukuran {{ $stok->ukuran }} (sisa {{ $stok->sisa_stok }} pcs, ROP: {{ $stok->batas_rop }} pcs)@if(! $loop->last),@endif</span>
                            @endforeach
                            @if($jumlahKritis > 3) dan {{ $jumlahKritis - 3 }} varian lainnya.@endif
                        </p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Semua varian ukuran masih berada di atas batas ROP.</p>
                    @endif
                </div>
            </div>
            @if($stokKritis->isNotEmpty())
                <button type="button" wire:click="jadwalkanProduksi({{ $stokKritis->first()->id }})" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white transition hover:bg-gray-800 cursor-pointer active:scale-95">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v6M5 7l4 4M19 7l-4 4M4 13h16M6 13l1 8h10l1-8"/></svg>
                    Jadwalkan Produksi Baju Baru
                </button>
            @endif
        </section>

        {{-- TABEL DAFTAR STOK PER UKURAN PRODUK --}}
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Daftar Stok Per Ukuran Produk</h2>
                    <p class="mt-1 text-[11px] text-gray-500">Menampilkan varian ukuran secara detail tanpa penggabungan kategori</p>
                </div>
                <div class="flex flex-col gap-2 sm:w-80">
                    <label class="relative block">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari SKU ukuran (mis: K30S-BLK-S)..." class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-gray-400 focus:bg-white focus:outline-none focus:ring-1 focus:ring-gray-300">
                    </label>
                    <select wire:model.live="filterStatus" class="w-fit rounded-xl border border-gray-200 bg-white px-3 py-2 text-[11px] font-semibold text-gray-700 focus:border-gray-400 focus:outline-none">
                        <option value="semua">Semua status ROP</option>
                        <option value="aman">Stok aman</option>
                        <option value="menipis">Menipis / di bawah ROP</option>
                        <option value="habis">Stok habis</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-xs">
                    <thead class="border-y border-gray-100 bg-gray-50 text-[9px] font-bold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Kode SKU Ukuran</th>
                            <th class="px-4 py-3">Nama Produk & Warna</th>
                            <th class="px-4 py-3">Ukuran Spesifik</th>
                            <th class="px-4 py-3">Sisa Stok</th>
                            <th class="px-4 py-3">Batas Aman ROP</th>
                            <th class="px-4 py-3">Status ROP</th>
                            <th class="px-4 py-3 text-center">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarStok as $stok)
                            @php
                                $statusRop = $stok->hitungStatusRop();
                            @endphp
                            <tr wire:key="stock-row-{{ $stok->id }}" class="transition hover:bg-gray-50/80">
                                <td class="px-4 py-4 font-mono font-bold text-gray-900">{{ $stok->produk?->kode_produk ?? 'SKU-'.$stok->id }}</td>
                                <td class="px-4 py-4">
                                    <span class="block font-semibold text-gray-900">{{ $stok->nama_produk }}</span>
                                    <span class="mt-1 block text-[10px] text-gray-500">{{ $stok->warna }} · {{ $stok->produk?->jenis_kain ?? 'Katun' }}</span>
                                </td>
                                <td class="px-4 py-4"><span class="inline-flex rounded-lg bg-gray-100 px-2 py-1 font-bold text-gray-800">{{ $stok->ukuran }}</span></td>
                                <td class="px-4 py-4 font-black {{ $stok->sisa_stok <= 0 ? 'text-rose-600' : ($stok->sisa_stok <= $stok->batas_rop ? 'text-amber-600' : 'text-gray-900') }}">
                                    {{ number_format($stok->sisa_stok) }} pcs
                                </td>
                                <td class="px-4 py-4 text-gray-600">{{ number_format($stok->batas_rop) }} pcs</td>
                                <td class="px-4 py-4">
                                    @if($statusRop === 'Aman')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold text-emerald-800"><span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>Stok Aman</span>
                                    @elseif($statusRop === 'Habis')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-2.5 py-1 text-[10px] font-bold text-rose-800"><span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>Stok Habis</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-bold text-amber-800"><span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span>Menipis (Di Bawah ROP)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" wire:click="bukaModalRop({{ $stok->id }})" class="rounded-lg border border-gray-200 px-2.5 py-1 text-[11px] font-semibold text-gray-700 hover:bg-gray-100 cursor-pointer">Atur ROP</button>
                                        <button type="button" wire:click="lihatDetail({{ $stok->id }})" class="rounded-lg border border-gray-200 px-2.5 py-1 text-[11px] font-semibold text-gray-700 hover:bg-gray-100 cursor-pointer">Detail</button>
                                        @if($stok->sisa_stok <= $stok->batas_rop)
                                            <button type="button" wire:click="jadwalkanProduksi({{ $stok->id }})" class="rounded-lg bg-black px-2.5 py-1 text-[11px] font-bold text-white hover:bg-gray-800 cursor-pointer">Produksi</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500">Tidak ada varian stok yang sesuai filter pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-4 py-3">
                {{ $daftarStok->links() }}
            </div>
        </section>
    @endif

    {{-- ======================================================== --}}
    {{-- TAB 2: RIWAYAT MUTASI STOK --}}
    {{-- ======================================================== --}}
    @if($activeTab === 'mutasi')
        {{-- 3 KARTU STATISTIK MUTASI --}}
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Produk masuk minggu ini</p>
                        <p class="mt-3 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($jumlahMasukMingguIni) }}</span><span class="text-sm text-gray-500">Pcs</span></p>
                        <span class="mt-3 inline-flex rounded-full bg-teal-100 px-2.5 py-1 text-[10px] font-bold text-teal-800">Masuk dari produksi</span>
                    </div>
                    <span class="rounded-xl bg-teal-100 p-2 text-teal-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V4M5 11l7-7 7 7"/><path d="M4 20h16"/></svg>
                    </span>
                </div>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Produk keluar minggu ini</p>
                        <p class="mt-3 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($jumlahKeluarMingguIni) }}</span><span class="text-sm text-gray-500">Pcs</span></p>
                        <span class="mt-3 inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-semibold text-gray-700">Pengiriman / serah terima</span>
                    </div>
                    <span class="rounded-xl bg-gray-100 p-2 text-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h12v11H3zM15 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="18.5" cy="19" r="1.5"/></svg>
                    </span>
                </div>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Sisa stok siap ambil</p>
                        <p class="mt-3 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($totalStokSiapAmbil) }}</span><span class="text-sm text-gray-500">Pcs</span></p>
                        <span class="mt-3 inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-semibold text-gray-700">Tersedia di rak penyimpanan display</span>
                    </div>
                    <span class="rounded-xl bg-black p-2 text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 9h16M9 13h6"/></svg>
                    </span>
                </div>
            </article>
        </section>

        {{-- TABEL RIWAYAT PERGERAKAN STOK (MUTASI) --}}
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-3 p-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-1 rounded-xl bg-gray-100 p-1">
                    @foreach(['semua' => 'Semua Catatan', 'masuk' => 'Baju Masuk (Selesai Produksi)', 'keluar' => 'Baju Keluar (Kirim/Diambil)'] as $tab => $label)
                        <button
                            type="button"
                            wire:key="mutation-tab-{{ $tab }}"
                            wire:click="setTab('{{ $tab }}')"
                            @class([
                                'rounded-lg px-3 py-2 text-[10px] font-bold transition sm:text-[11px] cursor-pointer',
                                'bg-white text-gray-900 shadow-sm' => $filterTab === $tab,
                                'text-gray-600 hover:bg-white/70' => $filterTab !== $tab,
                            ])
                        >{{ $label }}</button>
                    @endforeach
                </div>
                <label class="relative block w-full lg:max-w-sm">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input type="search" wire:model.live.debounce.300ms="searchMutasi" placeholder="Cari nomor pesanan, nama produk pakaian..." class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-gray-400 focus:bg-white focus:outline-none focus:ring-1 focus:ring-gray-300">
                </label>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-xs">
                    <thead class="border-y border-gray-100 bg-gray-50 text-[9px] font-bold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Waktu & Tanggal</th>
                            <th class="px-4 py-3">Jenis Pergerakan</th>
                            <th class="px-4 py-3">Nama Produk & Ukuran</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Untuk Pesanan / Keterangan</th>
                            <th class="px-4 py-3">Petugas Gudang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarMutasi as $mutasi)
                            @php
                                $detail = $mutasi->keterangan;
                                $label = $mutasi->jenis_mutasi === 'masuk' ? 'Masuk (Produksi)' : 'Keluar';
                                if ($mutasi->jenis_mutasi === 'keluar' && str_contains($detail, 'Keluar (Diambil)')) {
                                    $label = 'Keluar (Diambil)';
                                } elseif ($mutasi->jenis_mutasi === 'keluar' && str_contains($detail, 'Keluar (Kirim)')) {
                                    $label = 'Keluar (Kirim)';
                                }
                                $referensiTampilan = preg_replace('/^(Masuk \(Produksi\)|Keluar \((Kirim|Diambil)\))\s*·?\s*/', '', $detail) ?: $detail;
                            @endphp
                            <tr wire:key="mutation-row-{{ $mutasi->id }}" class="transition hover:bg-gray-50/80">
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span class="block font-semibold text-gray-800">{{ $mutasi->tanggal_mutasi?->translatedFormat('d M Y') }}</span>
                                    <span class="mt-1 block text-[10px] text-gray-500">{{ $mutasi->tanggal_mutasi?->format('H:i') }} WIB</span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($mutasi->jenis_mutasi === 'masuk')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-100 px-2.5 py-1 text-[10px] font-bold text-teal-800"><span class="h-1.5 w-1.5 rounded-full bg-teal-700"></span>{{ $label }}</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-2.5 py-1 text-[10px] font-bold text-rose-700"><span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>{{ $label }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="block font-semibold text-gray-900">{{ $mutasi->stokProduk?->nama_produk ?? 'Produk dihapus' }} {{ $mutasi->stokProduk?->warna }}</span>
                                    <span class="mt-1 block text-[10px] text-gray-500">Ukuran {{ $mutasi->stokProduk?->ukuran ?? '-' }} · SKU {{ $mutasi->stokProduk?->produk?->kode_produk ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span @class(['block font-bold', 'text-teal-700' => $mutasi->jenis_mutasi === 'masuk', 'text-gray-900' => $mutasi->jenis_mutasi === 'keluar'])>
                                        {{ $mutasi->jenis_mutasi === 'masuk' ? '+' : '−' }}{{ number_format($mutasi->jenis_mutasi === 'masuk' ? $mutasi->jumlah_stok_masuk : $mutasi->jumlah_stok_keluar) }} Pcs
                                    </span>
                                    <span class="mt-1 block text-[10px] text-gray-500">Saldo: {{ number_format($mutasi->saldo_sebelum) }} → {{ number_format($mutasi->saldo_sesudah) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($mutasi->jenis_mutasi === 'keluar' && preg_match('/ORD-[A-Z0-9-]+/', $detail, $nomorPesanan))
                                        <span class="block font-semibold text-gray-800">{{ $nomorPesanan[0] }}</span>
                                    @elseif(filled($referensiTampilan))
                                        <span class="block font-semibold text-gray-800">{{ \Illuminate\Support\Str::limit($referensiTampilan, 48) }}</span>
                                    @else
                                        <span class="block text-gray-400">-</span>
                                    @endif
                                    <span class="mt-1 block text-[10px] text-gray-500">{{ \Illuminate\Support\Str::limit($referensiTampilan, 58) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[9px] font-bold text-gray-600">{{ $mutasi->user?->initials() ?? 'PG' }}</span>
                                        <span class="font-semibold text-gray-700">{{ $mutasi->user?->name ?? 'Petugas' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <p class="text-sm font-bold text-gray-700">Belum ada catatan mutasi stok</p>
                                    <p class="mt-1 text-xs text-gray-500">Catat produk selesai produksi atau stok yang keluar untuk pesanan pelanggan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-100 bg-gray-50/70 px-4 py-3 text-[10px] text-gray-500 sm:flex-row sm:items-center sm:justify-between">
                <span>Menampilkan {{ $daftarMutasi->count() }} dari {{ $jumlahMutasi }} mutasi produk pakaian</span>
                @if($daftarMutasi->count() < $jumlahMutasi)
                    <button type="button" wire:click="muatLebihBanyak" wire:loading.attr="disabled" class="rounded-lg border border-gray-200 bg-white px-3 py-2 font-bold text-gray-700 hover:bg-gray-50 cursor-pointer">Muat Lebih Banyak</button>
                @endif
            </div>
        </section>

        {{-- SOP PENGELOLAAN STOK --}}
        <section class="flex items-start gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
            <span class="rounded-xl bg-black p-2 text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 3v3h8V3M8 13l2.5 2.5L16 10"/></svg>
            </span>
            <div>
                <h2 class="text-[11px] font-black uppercase tracking-wide text-gray-900">Pengingat standar pengelolaan stok baju jadi (SOP)</h2>
                <p class="mt-1 text-xs leading-relaxed text-gray-600">Setiap pengeluaran baju jadi wajib dicocokkan dengan nomor pesanan (ORD). Penambahan stok baju ready otomatis tercatat saat operator QC memvalidasi batch pakaian selesai.</p>
            </div>
        </section>
    @endif

    {{-- ======================================================== --}}
    {{-- MODAL 1: TAMBAH SKU PRODUK BARU --}}
    {{-- ======================================================== --}}
    @if($showModalCreate)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="tutupModalCreate">
            <section class="max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">Tambah SKU Produk Baju</h2>
                        <p class="mt-1 text-xs text-gray-500">Tambahkan varian produk baru beserta ukuran spesifiknya.</p>
                    </div>
                    <button type="button" wire:click="tutupModalCreate" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 cursor-pointer" aria-label="Tutup">✕</button>
                </div>

                <form wire:submit="simpanProduk" class="space-y-4 p-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <label for="kodeProduk" class="block text-xs font-bold text-gray-700">Kode SKU <span class="text-rose-500">*</span></label>
                                <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    Otomatis
                                </span>
                            </div>
                            <div class="relative">
                                <input id="kodeProduk" wire:model="kodeProduk" placeholder="Contoh: PRD-007" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 pr-20 px-3 py-2.5 font-mono text-xs font-semibold text-gray-800 focus:border-teal-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-600">
                                <button type="button" wire:click="buatUlangKodeSku" title="Generate ulang kode SKU" class="absolute inset-y-1 right-1 flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-2 text-[10px] font-bold text-gray-600 shadow-sm hover:bg-gray-50 hover:text-teal-700 cursor-pointer">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    Refresh
                                </button>
                            </div>
                            <p class="mt-1 text-[10px] text-gray-500">Dibuat otomatis oleh sistem (tetap dapat disesuaikan jika perlu).</p>
                            @error('kodeProduk') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="namaProduk" class="mb-1.5 block text-xs font-bold text-gray-700">Nama produk <span class="text-rose-500">*</span></label>
                            <input id="namaProduk" wire:model="namaProduk" placeholder="Contoh: Kaos Polos Combed 30s" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('namaProduk') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="jenisKain" class="mb-1.5 block text-xs font-bold text-gray-700">Jenis kain <span class="text-rose-500">*</span></label>
                            <input id="jenisKain" wire:model="jenisKain" placeholder="Contoh: Katun Combed 30s" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('jenisKain') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="warna" class="mb-1.5 block text-xs font-bold text-gray-700">Warna <span class="text-rose-500">*</span></label>
                            <input id="warna" wire:model="warna" placeholder="Contoh: Hitam Pekat" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('warna') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="ukuran" class="mb-1.5 block text-xs font-bold text-gray-700">Ukuran <span class="text-rose-500">*</span></label>
                            <select id="ukuran" wire:model="ukuran" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                                @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                    <option wire:key="new-size-{{ $size }}" value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                            @error('ukuran') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="hargaSatuan" class="mb-1.5 block text-xs font-bold text-gray-700">Harga satuan (Rp)</label>
                            <input id="hargaSatuan" type="number" min="0" step="1" wire:model="hargaSatuan" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('hargaSatuan') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="stokAwal" class="mb-1.5 block text-xs font-bold text-gray-700">Stok awal (pcs)</label>
                            <input id="stokAwal" type="number" min="0" step="1" wire:model="stokAwal" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('stokAwal') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="batasRopAwal" class="mb-1.5 block text-xs font-bold text-gray-700">Batas minimum ROP</label>
                            <input id="batasRopAwal" type="number" min="0" step="1" wire:model="batasRop" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('batasRop') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                        <button type="button" wire:click="tutupModalCreate" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white hover:bg-gray-800 disabled:opacity-50 cursor-pointer">Simpan SKU</button>
                    </div>
                </form>
            </section>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- MODAL 2: CATAT MASUK PRODUK JADI --}}
    {{-- ======================================================== --}}
    @if($showModalMasuk)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="tutupModalMasuk">
            <section class="w-full max-w-xl rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">Catat Masuk Produk Jadi</h2>
                        <p class="mt-1 text-xs text-gray-500">Penerimaan barang menambah saldo stok dan mencatat riwayat mutasi gudang.</p>
                    </div>
                    <button type="button" wire:click="tutupModalMasuk" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 cursor-pointer" aria-label="Tutup">✕</button>
                </div>
                <form wire:submit="simpanStokMasuk" class="space-y-4 p-5">
                    <div>
                        <label for="selectedStokId" class="mb-1.5 block text-xs font-bold text-gray-700">Varian produk <span class="text-rose-500">*</span></label>
                        <select id="selectedStokId" wire:model="selectedStokId" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            <option value="">Pilih SKU dan ukuran</option>
                            @foreach($stokPilihan as $item)
                                <option wire:key="stock-option-{{ $item->id }}" value="{{ $item->id }}">{{ $item->produk?->kode_produk }}-{{ $item->ukuran }} · {{ $item->nama_produk }} (stok {{ $item->sisa_stok }})</option>
                            @endforeach
                        </select>
                        @error('selectedStokId') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="jumlahMasuk" class="mb-1.5 block text-xs font-bold text-gray-700">Jumlah masuk (pcs) <span class="text-rose-500">*</span></label>
                            <input id="jumlahMasuk" type="number" min="1" step="1" wire:model="jumlahMasuk" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('jumlahMasuk') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="tanggalMutasi" class="mb-1.5 block text-xs font-bold text-gray-700">Tanggal penerimaan <span class="text-rose-500">*</span></label>
                            <input id="tanggalMutasi" type="datetime-local" wire:model="tanggalMutasi" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('tanggalMutasi') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="keterangan" class="mb-1.5 block text-xs font-bold text-gray-700">Keterangan <span class="text-rose-500">*</span></label>
                        <textarea id="keterangan" rows="2" wire:model="keterangan" placeholder="Contoh: Hasil produksi batch November" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600"></textarea>
                        @error('keterangan') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                        <button type="button" wire:click="tutupModalMasuk" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" class="rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white hover:bg-gray-800 disabled:opacity-50 cursor-pointer">Simpan Stok Masuk</button>
                    </div>
                </form>
            </section>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- MODAL 3: ATUR BATAS ROP --}}
    {{-- ======================================================== --}}
    @if($showModalRop)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="$set('showModalRop', false)">
            <section class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-lg font-black text-gray-900">Atur Batas ROP</h2>
                    <p class="mt-1 text-xs text-gray-500">Batas minimum stok untuk varian yang dipilih.</p>
                </div>
                <form wire:submit="simpanBatasRop" class="space-y-4 p-5">
                    <div>
                        <label for="batasRop" class="mb-1.5 block text-xs font-bold text-gray-700">Batas aman minimum (pcs)</label>
                        <input id="batasRop" type="number" min="0" step="1" wire:model="batasRop" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                        @error('batasRop') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showModalRop', false)" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">Batal</button>
                        <button type="submit" class="rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white hover:bg-gray-800 cursor-pointer">Simpan Batas ROP</button>
                    </div>
                </form>
            </section>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- MODAL 4: DETAIL STOK & RIWAYAT MUTASI SKU --}}
    {{-- ======================================================== --}}
    @if($showModalDetail && $detailStok)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="tutupModalDetail">
            <section class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">Detail Stok {{ $detailStok->produk?->kode_produk }}-{{ $detailStok->ukuran }}</h2>
                        <p class="mt-1 text-xs text-gray-500">{{ $detailStok->nama_produk }} · {{ $detailStok->warna }} · Ukuran {{ $detailStok->ukuran }}</p>
                    </div>
                    <button type="button" wire:click="tutupModalDetail" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 cursor-pointer" aria-label="Tutup">✕</button>
                </div>
                <div class="grid gap-3 border-b border-gray-100 p-5 sm:grid-cols-3">
                    <div class="rounded-xl bg-gray-50 p-3"><p class="text-[10px] font-semibold text-gray-500">Sisa stok</p><p class="mt-1 text-lg font-black text-gray-900">{{ number_format($detailStok->sisa_stok) }} pcs</p></div>
                    <div class="rounded-xl bg-gray-50 p-3"><p class="text-[10px] font-semibold text-gray-500">Batas ROP</p><p class="mt-1 text-lg font-black text-gray-900">{{ number_format($detailStok->batas_rop) }} pcs</p></div>
                    <div class="rounded-xl bg-gray-50 p-3"><p class="text-[10px] font-semibold text-gray-500">Status</p><p class="mt-1 text-lg font-black {{ $detailStok->hitungStatusRop() === 'Aman' ? 'text-emerald-700' : 'text-rose-700' }}">{{ $detailStok->hitungStatusRop() }}</p></div>
                </div>
                <div class="p-5">
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Mutasi Stok</h3>
                    <div class="mt-3 overflow-x-auto">
                        <table class="w-full min-w-[600px] text-left text-xs">
                            <thead class="bg-gray-50 text-[9px] font-bold uppercase tracking-wide text-gray-500">
                                <tr><th class="px-3 py-2.5">Tanggal</th><th class="px-3 py-2.5">Jenis</th><th class="px-3 py-2.5">Jumlah</th><th class="px-3 py-2.5">Saldo</th><th class="px-3 py-2.5">Keterangan / Petugas</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($detailStok->mutasiStok as $mutasi)
                                    <tr wire:key="mutation-{{ $mutasi->id }}">
                                        <td class="px-3 py-3 text-gray-600">{{ $mutasi->tanggal_mutasi?->format('d M Y H:i') }}</td>
                                        <td class="px-3 py-3"><span class="rounded-full px-2 py-1 text-[10px] font-bold {{ $mutasi->jenis_mutasi === 'masuk' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">{{ ucfirst($mutasi->jenis_mutasi) }}</span></td>
                                        <td class="px-3 py-3 font-bold {{ $mutasi->jenis_mutasi === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">{{ $mutasi->jenis_mutasi === 'masuk' ? '+' : '-' }}{{ number_format($mutasi->jenis_mutasi === 'masuk' ? $mutasi->jumlah_stok_masuk : $mutasi->jumlah_stok_keluar) }} pcs</td>
                                        <td class="px-3 py-3 text-gray-700">{{ number_format($mutasi->saldo_sebelum) }} → {{ number_format($mutasi->saldo_sesudah) }}</td>
                                        <td class="px-3 py-3"><span class="block text-gray-700">{{ $mutasi->keterangan }}</span><span class="mt-1 block text-[10px] text-gray-400">{{ $mutasi->user?->name ?? 'Petugas' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-3 py-8 text-center text-xs text-gray-500">Belum ada riwayat mutasi stok.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- MODAL 5: CATAT MUTASI STOK (MASUK / KELUAR) --}}
    {{-- ======================================================== --}}
    @if($showModalMutasi)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="tutupModalMutasi">
            <section class="max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">{{ $jenisPergerakan === 'masuk' ? 'Catat Baju Masuk dari Produksi' : 'Catat Baju Keluar' }}</h2>
                        <p class="mt-1 text-xs text-gray-500">{{ $jenisPergerakan === 'masuk' ? 'Barang selesai QC menambah saldo stok siap kirim.' : 'Pilih pesanan dan tujuan pengeluaran agar saldo stok tercatat akurat.' }}</p>
                    </div>
                    <button type="button" wire:click="tutupModalMutasi" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 cursor-pointer" aria-label="Tutup">✕</button>
                </div>

                <form wire:submit="simpanMutasi" class="space-y-4 p-5">
                    @if($jenisPergerakan === 'keluar')
                        <div>
                            <label for="tujuanKeluar" class="mb-1.5 block text-xs font-bold text-gray-700">Jenis pengeluaran <span class="text-rose-500">*</span></label>
                            <select id="tujuanKeluar" wire:model="tujuanKeluar" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs focus:border-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="Kirim">Keluar untuk dikirim</option>
                                <option value="Diambil">Keluar diambil pelanggan</option>
                            </select>
                            @error('tujuanKeluar') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div>
                        <label for="stokProdukId" class="mb-1.5 block text-xs font-bold text-gray-700">Produk & ukuran <span class="text-rose-500">*</span></label>
                        <select id="stokProdukId" wire:model="stokProdukId" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            <option value="">Pilih SKU produk</option>
                            @foreach($stokPilihan as $stok)
                                <option wire:key="mutation-sku-{{ $stok->id }}" value="{{ $stok->id }}">{{ $stok->produk?->kode_produk }}-{{ $stok->ukuran }} · {{ $stok->nama_produk }} {{ $stok->warna }} · Stok {{ $stok->sisa_stok }} pcs</option>
                            @endforeach
                        </select>
                        @error('stokProdukId') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    @if($jenisPergerakan === 'keluar')
                        <div>
                            <label for="referensi" class="mb-1.5 block text-xs font-bold text-gray-700">Nomor pesanan (ORD) <span class="text-rose-500">*</span></label>
                            <select id="referensi" wire:model="referensi" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                                <option value="">Pilih pesanan pelanggan</option>
                                @foreach($pesananPilihan as $pesanan)
                                    <option wire:key="mutation-order-{{ $pesanan->id }}" value="{{ $pesanan->nomor_pemesanan }}">{{ $pesanan->nomor_pemesanan }} · {{ $pesanan->pelanggan?->nama ?? 'Pelanggan' }}</option>
                                @endforeach
                            </select>
                            @error('referensi') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <div>
                            <label for="referensi" class="mb-1.5 block text-xs font-bold text-gray-700">Batch / asal produksi <span class="font-normal text-gray-400">(opsional)</span></label>
                            <input id="referensi" wire:model="referensi" placeholder="Contoh: Batch Produksi #42" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('referensi') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="jumlah" class="mb-1.5 block text-xs font-bold text-gray-700">Jumlah {{ $jenisPergerakan === 'masuk' ? 'masuk' : 'keluar' }} (pcs) <span class="text-rose-500">*</span></label>
                            <input id="jumlah" type="number" min="1" step="1" wire:model="jumlah" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('jumlah') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="tanggalMutasi" class="mb-1.5 block text-xs font-bold text-gray-700">Waktu pergerakan <span class="text-rose-500">*</span></label>
                            <input id="tanggalMutasi" type="datetime-local" wire:model="tanggalMutasi" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('tanggalMutasi') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="mb-1.5 block text-xs font-bold text-gray-700">Keterangan <span class="font-normal text-gray-400">(opsional)</span></label>
                        <textarea id="keterangan" rows="2" wire:model="keterangan" placeholder="{{ $jenisPergerakan === 'masuk' ? 'Contoh: QC selesai, siap masuk rak display' : 'Contoh: Kurir reguler / diambil oleh pemesan' }}" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600"></textarea>
                        @error('keterangan') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                        <button type="button" wire:click="tutupModalMutasi" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="simpanMutasi" class="rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer">
                            <span wire:loading.remove wire:target="simpanMutasi">Simpan Mutasi</span>
                            <span wire:loading wire:target="simpanMutasi">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </section>
        </div>
    @endif
</div>
