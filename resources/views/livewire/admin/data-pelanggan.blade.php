<div class="space-y-5">
    @if($pesanSukses)
        <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
            <span>{{ $pesanSukses }}</span>
            <button type="button" wire:click="$set('pesanSukses', '')" class="font-bold" aria-label="Tutup notifikasi">×</button>
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="mb-2 flex items-center gap-2 text-[10px] font-bold uppercase tracking-wide">
                <span class="rounded-full bg-gray-200 px-2.5 py-1 text-gray-600">Database Klien</span>
                <span class="text-teal-700">• Data Terverifikasi</span>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Daftar Pelanggan</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Kelola data kontak klien konveksi, alamat pengiriman, dan riwayat pesanan. Data pelanggan baru tercatat dari pesanan.</p>
        </div>

        <div class="flex shrink-0 flex-wrap gap-2">
            <button type="button" wire:click="exportData" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/>
                </svg>
                Unduh Data
            </button>
        </div>
    </div>

    <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan pelanggan">
        <article class="flex min-h-32 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Total Terdaftar</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2"><strong class="text-3xl font-extrabold text-gray-900">{{ number_format($totalPelanggan, 0, ',', '.') }}</strong><span class="text-xs text-gray-500">Klien</span></div>
            <p class="text-[11px] text-gray-500">Seluruh data pelanggan tersimpan</p>
        </article>

        <article class="flex min-h-32 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Pelanggan Aktif</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-100 text-teal-700">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 7v5h-5"/><path d="M4 17v-5h5"/><path d="M5.6 9A7 7 0 0 1 18 6l2 2M4 16l2 2a7 7 0 0 0 12.4-3"/></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2"><strong class="text-3xl font-extrabold text-gray-900">{{ number_format($pelangganAktif, 0, ',', '.') }}</strong><span class="text-xs text-gray-500">Pelanggan</span></div>
            <p class="text-[11px] text-gray-500">Memiliki pesanan dalam 3 bulan terakhir</p>
        </article>

        <article class="flex min-h-32 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Pelanggan Baru</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2"><strong class="text-3xl font-extrabold text-gray-900">{{ number_format($pelangganBaru, 0, ',', '.') }}</strong><span class="text-xs text-teal-700">bulan ini</span></div>
            <p class="text-[11px] text-gray-500">Pendaftaran sejak awal bulan ini</p>
        </article>

        <article class="flex min-h-32 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Rata-rata Nilai Pesanan</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h3"/></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1"><span class="text-sm font-bold text-gray-700">Rp</span><strong class="text-3xl font-extrabold text-gray-900">{{ number_format($rataRataPesanan, 0, ',', '.') }}</strong></div>
            <p class="text-[11px] text-gray-500">Rata-rata nilai seluruh pesanan</p>
        </article>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm" aria-label="Filter pelanggan">
        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 xl:grid-cols-[minmax(240px,1fr)_200px_180px_40px]">
            <label class="relative block">
                <span class="sr-only">Cari pelanggan</span>
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari nama pemesan, perusahaan, no. HP, atau kota..." class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </label>
            <label>
                <span class="sr-only">Filter kategori</span>
                <select wire:model.live="filterKategori" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-xs text-gray-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    <option value="semua">Semua Kategori</option>
                    @foreach($kategoriOptions as $kategoriOption)
                        <option value="{{ $kategoriOption }}">{{ $kategoriOption }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span class="sr-only">Filter kota</span>
                <select wire:model.live="filterKota" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-xs text-gray-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    <option value="semua">Semua Kota</option>
                    @foreach($daftarKota as $kotaOption)
                        <option value="{{ $kotaOption }}">{{ $kotaOption }}</option>
                    @endforeach
                </select>
            </label>
            <button type="button" wire:click="resetFilters" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-600 transition hover:bg-gray-100" aria-label="Atur ulang filter" title="Atur ulang filter">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 11a8.1 8.1 0 0 0-14.9-3M4 4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 14.9 3M20 20v-4h-4"/></svg>
            </button>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-2">
            <span class="text-[10px] font-medium text-gray-500">Filter Cepat:</span>
            @foreach($kategoriOptions as $kategoriOption)
                <button type="button" wire:click="setFilterKategori('{{ $kategoriOption }}')" class="rounded-full px-2.5 py-1 text-[10px] font-semibold transition {{ $filterKategori === $kategoriOption ? 'bg-teal-100 text-teal-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $kategoriOption }}
                </button>
            @endforeach
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm" aria-label="Daftar pelanggan">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left">
                <thead class="bg-gray-50 text-[9px] font-bold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">ID Klien</th>
                        <th class="px-4 py-3">Nama Klien / Perusahaan</th>
                        <th class="px-4 py-3">Kontak WhatsApp &amp; Email</th>
                        <th class="px-4 py-3">Alamat &amp; Kota</th>
                        <th class="px-4 py-3 text-center">Total PO</th>
                        <th class="px-4 py-3 text-right">Nilai Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-700">
                    @forelse($pelanggan as $item)
                        <tr wire:key="pelanggan-{{ $item->id }}" class="transition hover:bg-gray-50/80">
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-md bg-gray-100 px-2 py-1 font-bold text-gray-700">{{ $item->kode_pelanggan }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-[10px] font-bold text-gray-600">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($item->nama, 0, 2)) }}</span>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-gray-900">{{ $item->nama }}</p>
                                        <p class="mt-0.5 text-[10px] text-gray-500">{{ $item->kategori ?: 'Kategori belum diatur' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-700">{{ $item->nomor_telepon }}</p>
                                <p class="mt-0.5 text-[10px] text-gray-500">{{ $item->email ?: 'Email belum tersedia' }}</p>
                            </td>
                            <td class="max-w-56 px-4 py-3">
                                <p class="font-medium text-gray-700">{{ $item->kota ?: 'Kota belum diatur' }}</p>
                                <p class="mt-0.5 truncate text-[10px] text-gray-500" title="{{ $item->alamat }}">{{ $item->alamat }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex min-w-9 flex-col items-center rounded-full bg-gray-100 px-2 py-1 text-[10px] font-bold text-gray-700">
                                    {{ $item->pemesanan_count }}
                                    <span class="font-medium">PO</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-700">Rp {{ number_format((float) ($item->pemesanan_sum_total_harga ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="font-semibold text-gray-700">Pelanggan tidak ditemukan</p>
                                <p class="mt-1 text-xs text-gray-500">Coba ubah kata kunci atau filter yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-gray-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-[10px] text-gray-500">
                Menampilkan {{ $pelanggan->firstItem() ?? 0 }}–{{ $pelanggan->lastItem() ?? 0 }} dari {{ number_format($pelanggan->total(), 0, ',', '.') }} total pelanggan
            </p>
            <div>{{ $pelanggan->links() }}</div>
        </div>
    </section>

</div>
