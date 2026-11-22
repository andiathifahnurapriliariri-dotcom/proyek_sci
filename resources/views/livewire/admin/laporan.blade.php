<div class="space-y-5">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-semibold uppercase tracking-wide text-gray-500">
                <span>Manajemen Konveksi</span><span>›</span><span class="text-gray-800">Laporan Kinerja Bulanan</span>
            </div>
            <h1 class="mt-2 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Laporan Kinerja &amp; Penjualan</h1>
            <p class="mt-1 max-w-3xl text-sm leading-relaxed text-gray-500">Ringkasan pendapatan, produktivitas penjahitan, persediaan, dan pemantauan stok untuk periode {{ $labelPeriode }}.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
            <label class="grid gap-1 text-[10px] font-bold uppercase tracking-wide text-gray-500">
                Pilih Periode
                <input type="month" wire:model.live="periode" class="rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-800 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100">
            </label>
            <button type="button" wire:click="unduhLaporan" wire:loading.attr="disabled" wire:target="unduhLaporan" class="inline-flex items-center justify-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800 disabled:cursor-wait disabled:opacity-60">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg>
                <span wire:loading.remove wire:target="unduhLaporan">Unduh Laporan Lengkap (PDF)</span>
                <span wire:loading wire:target="unduhLaporan">Menyiapkan PDF...</span>
            </button>
        </div>
    </div>

    @error('periode')
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700">{{ $message }}</div>
    @enderror

    <section class="grid grid-cols-1 gap-3 lg:grid-cols-3">
        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wide text-gray-500">Laporan keuangan</p>
                    <p class="mt-2 text-sm font-bold text-gray-900">Penjualan &amp; Pendapatan</p>
                    <p class="mt-2 flex items-baseline gap-1 text-gray-900"><span class="text-xl font-black">Rp</span><span class="text-3xl font-black">{{ number_format($nilaiPenjualan, 0, ',', '.') }}</span></p>
                    <p class="mt-1 text-[10px] text-gray-500">Dari {{ number_format($jumlahPesanan) }} pesanan selesai dan lunas</p>
                </div>
                <span class="rounded-full bg-teal-100 px-2.5 py-1 text-[9px] font-bold text-teal-800">Realisasi periode</span>
            </div>
            <div class="mt-4 flex items-center justify-between rounded-xl bg-gray-50 px-3 py-2.5 text-[10px]">
                <span class="text-gray-500">Rata-rata per pesanan</span>
                <span class="font-extrabold text-gray-900">Rp {{ number_format($rataRataPerPesanan, 0, ',', '.') }}</span>
            </div>
        </article>

        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wide text-gray-500">Produktivitas sewing</p>
                    <p class="mt-2 text-sm font-bold text-gray-900">Hasil Baju Jadi</p>
                    <p class="mt-2 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($jumlahPcs) }}</span><span class="text-sm font-semibold text-gray-500">Potong</span></p>
                    <p class="mt-1 text-[10px] text-gray-500">Produk dari pesanan yang lolos tahap akhir</p>
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[9px] font-bold text-gray-700">{{ number_format($jumlahPesanan) }} pesanan</span>
            </div>
            <div class="mt-4 flex items-center justify-between rounded-xl bg-gray-50 px-3 py-2.5 text-[10px]">
                <span class="text-gray-500">Rata-rata pengerjaan</span>
                <span class="font-extrabold text-gray-900">{{ number_format($rataRataHariKerja, 1, ',', '.') }} Hari Kerja</span>
            </div>
        </article>

        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wide text-gray-500">Manajemen persediaan</p>
                    <p class="mt-2 text-sm font-bold text-gray-900">Kepatuhan ROP Baju Jadi</p>
                    <p class="mt-2 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($persentaseRopAman, 1, ',', '.') }}%</span><span class="text-xs font-semibold text-gray-500">Stok Aman</span></p>
                    <p class="mt-1 text-[10px] text-gray-500">Snapshot stok saat ini, {{ number_format($totalSku) }} SKU dipantau</p>
                </div>
                <span class="rounded-full {{ $skuPerluPerhatian === 0 ? 'bg-teal-100 text-teal-800' : 'bg-amber-100 text-amber-800' }} px-2.5 py-1 text-[9px] font-bold">{{ $skuPerluPerhatian === 0 ? 'Stok Aman' : number_format($skuPerluPerhatian).' Perlu Perhatian' }}</span>
            </div>
            <div class="mt-4 flex items-center justify-between rounded-xl bg-gray-50 px-3 py-2.5 text-[10px]">
                <span class="text-gray-500">SKU di atas batas minimum</span>
                <span class="font-extrabold text-teal-700">{{ number_format($skuAman) }} dari {{ number_format($totalSku) }} SKU</span>
            </div>
        </article>
    </section>

    <section class="grid grid-cols-1 gap-3 xl:grid-cols-3">
        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm xl:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wide text-gray-500">Distribusi output produksi per kategori</p>
                    <h2 class="mt-1 text-sm font-bold text-gray-900">Volume potong baju dalam periode {{ $labelPeriode }}</h2>
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[9px] font-semibold text-gray-600">Unit: pcs pakaian</span>
            </div>

            <div class="mt-5 flex h-5 overflow-hidden rounded-full bg-gray-100" role="img" aria-label="Distribusi hasil produksi berdasarkan kategori">
                @forelse($distribusiOutput as $index => $kategori)
                    @php
                        $warnaBatang = ['bg-teal-700', 'bg-gray-700', 'bg-teal-400', 'bg-gray-400'][$index % 4];
                    @endphp
                    <div wire:key="report-bar-{{ $index }}" class="{{ $warnaBatang }} transition-all" style="width: {{ $kategori['porsi_output'] }}%" title="{{ $kategori['nama'] }}: {{ number_format($kategori['jumlah_pcs']) }} pcs"></div>
                @empty
                    <div class="h-full w-full bg-gray-200"></div>
                @endforelse
            </div>

            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                @forelse($distribusiOutput as $index => $kategori)
                    @php
                        $warnaTitik = ['bg-teal-700', 'bg-gray-700', 'bg-teal-400', 'bg-gray-400'][$index % 4];
                    @endphp
                    <div wire:key="report-category-{{ $index }}" class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 px-3 py-2.5">
                        <div class="min-w-0">
                            <p class="flex items-center gap-2 truncate text-[10px] font-bold text-gray-800"><span class="h-2 w-2 shrink-0 rounded-full {{ $warnaTitik }}"></span>{{ $kategori['nama'] }}</p>
                            <p class="mt-1 pl-4 text-[9px] text-gray-500">{{ number_format($kategori['porsi_output'], 1, ',', '.') }}% total output</p>
                        </div>
                        <span class="shrink-0 text-xs font-extrabold text-gray-900">{{ number_format($kategori['jumlah_pcs']) }} <span class="text-[9px] font-medium text-gray-500">pcs</span></span>
                    </div>
                @empty
                    <p class="col-span-full rounded-xl bg-gray-50 px-3 py-4 text-center text-xs text-gray-500">Belum ada hasil produksi selesai pada periode ini.</p>
                @endforelse
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-3 text-[9px] text-gray-500">
                <span>Total hasil: <strong class="text-gray-800">{{ number_format($jumlahPcs) }} pcs</strong></span>
                <span>{{ number_format($jumlahTepatWaktu) }} dari {{ number_format($jumlahDenganEstimasi) }} pesanan bertanggal selesai tepat waktu</span>
            </div>
        </article>

        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wide text-gray-500">Fasilitas konveksi</p>
                    <h2 class="mt-1 text-sm font-bold text-gray-900">Aktivitas Workshop</h2>
                    <p class="mt-1 text-[10px] leading-relaxed text-gray-500">Tahap produksi yang tercatat pada bulan {{ $labelPeriode }}.</p>
                </div>
                <span class="rounded-full bg-teal-100 px-2.5 py-1 text-[9px] font-bold text-teal-800">4 stasiun kerja</span>
            </div>
            <div class="mt-4 space-y-2">
                @foreach([
                    'Pemotongan kain' => $aktivitasWorkshop['pemotongan'],
                    'Sablon & bordir' => $aktivitasWorkshop['sablon'],
                    'Penjahitan' => $aktivitasWorkshop['penjahitan'],
                    'Steam & finishing' => $aktivitasWorkshop['steam'],
                ] as $namaStasiun => $jumlahAktivitas)
                    <div wire:key="report-station-{{ $loop->index }}" class="flex items-center justify-between rounded-xl border border-gray-100 px-3 py-2.5">
                        <span class="text-[10px] font-semibold text-gray-700">{{ $namaStasiun }}</span>
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-[9px] font-extrabold text-gray-800">{{ number_format($jumlahAktivitas) }} aktivitas</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 rounded-xl bg-teal-50 px-3 py-3">
                <p class="text-[9px] font-semibold text-teal-800">Ketepatan waktu penyelesaian</p>
                <p class="mt-1 text-xl font-black text-teal-900">{{ number_format($persentaseTepatWaktu, 1, ',', '.') }}%</p>
                <p class="text-[9px] text-teal-700">{{ number_format($jumlahTepatWaktu) }} dari {{ number_format($jumlahDenganEstimasi) }} pesanan memiliki estimasi</p>
            </div>
        </article>
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-2 border-b border-gray-100 px-4 py-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Rincian Hasil Pengerjaan Produk</h2>
                <p class="mt-1 text-[10px] text-gray-500">Rekap pesanan lunas yang telah selesai diproduksi berdasarkan kategori pakaian.</p>
            </div>
            <span class="w-fit rounded-full bg-gray-100 px-2.5 py-1 text-[9px] font-bold text-gray-600">{{ number_format($rincianKategori->count()) }} Kategori Aktif</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-gray-50 text-[9px] font-bold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Kategori Pakaian</th>
                        <th class="px-4 py-3 text-right">Jumlah Pesanan</th>
                        <th class="px-4 py-3 text-right">Baju Jadi (pcs)</th>
                        <th class="px-4 py-3 text-right">Nilai Penjualan</th>
                        <th class="px-4 py-3 text-right">Efisiensi Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rincianKategori as $index => $kategori)
                        <tr wire:key="report-row-{{ $index }}" class="text-[10px] text-gray-700">
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-gray-900">{{ $kategori['nama'] }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-right"><span class="rounded-full bg-gray-100 px-2.5 py-1 font-bold">{{ number_format($kategori['jumlah_pesanan']) }} Pesanan</span></td>
                            <td class="px-4 py-3.5 text-right font-semibold text-gray-900">{{ number_format($kategori['jumlah_pcs']) }} <span class="font-normal text-gray-500">Pcs</span></td>
                            <td class="px-4 py-3.5 text-right font-bold text-gray-900">Rp {{ number_format($kategori['nilai_penjualan'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-right">
                                <span class="inline-flex rounded-full {{ $kategori['efisiensi_waktu'] >= 90 ? 'bg-teal-100 text-teal-800' : 'bg-amber-100 text-amber-800' }} px-2.5 py-1 font-bold">
                                    {{ number_format($kategori['efisiensi_waktu']) }}% Tepat Waktu
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-xs text-gray-500">Tidak ada pesanan selesai dan lunas pada periode {{ $labelPeriode }}.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100 text-[10px] font-extrabold text-gray-900">
                    <tr>
                        <td class="px-4 py-3">Total Rekapitulasi</td>
                        <td class="px-4 py-3 text-right">{{ number_format($jumlahPesanan) }} Pesanan</td>
                        <td class="px-4 py-3 text-right">{{ number_format($jumlahPcs) }} Pcs</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($nilaiPenjualan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($persentaseTepatWaktu, 1, ',', '.') }}% Rata-rata</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex flex-col justify-between gap-2 bg-gray-50 px-4 py-3 text-[9px] text-gray-500 sm:flex-row">
            <span>Pesanan dihitung berdasarkan tanggal selesai produksi, status pesanan selesai, dan pembayaran lunas. Persediaan adalah snapshot saat ini.</span>
            <span>Diperbarui {{ $dibuatPada }}</span>
        </div>
    </section>
</div>
