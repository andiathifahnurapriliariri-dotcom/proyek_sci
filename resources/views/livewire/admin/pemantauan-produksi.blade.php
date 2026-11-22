<div class="space-y-5" wire:poll.3m="segarkanData">
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold text-gray-500">
                <span class="rounded-md bg-teal-100 px-2 py-1 font-bold uppercase tracking-wide text-teal-800">Lini Operasional Kantor</span>
                <span>›</span>
                <span class="text-gray-800">Monitoring Produksi Baju</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-1 text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Sinkronisasi Data
                </span>
            </div>
            <h1 class="mt-2 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Pemantauan Produksi</h1>
            <p class="mt-1 max-w-3xl text-sm leading-relaxed text-gray-500">Pantau posisi pengerjaan pesanan pakaian di workshop secara langsung, mulai dari pemotongan kain hingga finishing dan pengemasan.</p>
        </div>
        <div class="flex shrink-0 flex-col gap-2 sm:flex-row">
            <button type="button" wire:click="segarkanData" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 disabled:opacity-60">
                <svg wire:loading.remove wire:target="segarkanData" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7v5h-5"/><path d="M4 17v-5h5"/><path d="M5.6 9a7 7 0 0 1 11.6-2L20 12M4 12l2.8 5a7 7 0 0 0 11.6-2"/></svg>
                <svg wire:loading wire:target="segarkanData" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48 2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83"/></svg>
                Segarkan Data Produksi
            </button>
            <button type="button" wire:click="unduhLembarPantauan" wire:loading.attr="disabled" wire:target="unduhLembarPantauan" class="inline-flex items-center justify-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800 disabled:opacity-60">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg>
                <span wire:loading.remove wire:target="unduhLembarPantauan">Unduh Lembar Pantauan</span>
                <span wire:loading wire:target="unduhLembarPantauan">Menyiapkan...</span>
            </button>
        </div>
    </div>

    @error('filterTahap')
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700">{{ $message }}</div>
    @enderror

    <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Total baju diproses</p>
                    <p class="mt-3 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($jumlahPcsAktif) }}</span><span class="text-sm text-gray-500">Pcs</span></p>
                    <span class="mt-3 inline-flex rounded-full bg-teal-100 px-2.5 py-1 text-[10px] font-bold text-teal-800">{{ number_format($jumlahPesananAktif) }} pesanan aktif</span>
                </div>
                <span class="rounded-xl bg-gray-100 p-2 text-gray-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/><path d="M2 4h.01M2 12h.01M2 20h.01"/></svg></span>
            </div>
        </article>
        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Rata-rata progres lini</p>
                    <p class="mt-3 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ $rataRataProgres }}%</span></p>
                    <span class="mt-3 inline-flex rounded-full bg-cyan-50 px-2.5 py-1 text-[10px] font-bold text-cyan-800">Dari pesanan yang sedang berjalan</span>
                </div>
                <span class="rounded-xl bg-cyan-50 p-2 text-cyan-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-4 4"/></svg></span>
            </div>
        </article>
        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Produksi selesai</p>
                    <p class="mt-3 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($persentaseSelesai, 1) }}%</span></p>
                    <span class="mt-3 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-800">Porsi dari seluruh SPK aktif/selesai</span>
                </div>
                <span class="rounded-xl bg-emerald-50 p-2 text-emerald-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg></span>
            </div>
        </article>
        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Siap packing & kirim</p>
                    <p class="mt-3 flex items-baseline gap-2 text-gray-900"><span class="text-3xl font-black">{{ number_format($jumlahPcsSiapKirim) }}</span><span class="text-sm text-gray-500">Pcs</span></p>
                    <span class="mt-3 inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-800">Menunggu serah terima</span>
                </div>
                <span class="rounded-xl bg-amber-50 p-2 text-amber-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h12v11H3zM15 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="18.5" cy="19" r="1.5"/></svg></span>
            </div>
        </article>
    </section>

    <section class="space-y-3">
        <div class="flex flex-wrap items-end justify-between gap-2">
            <div>
                <h2 class="text-sm font-black uppercase tracking-wide text-gray-900">Alur stasiun kerja aktif (pipeline pabrik)</h2>
                <p class="mt-1 text-xs text-gray-500">Beban dan tenggat di setiap tahap diperbarui dari data SPK produksi.</p>
            </div>
            <p class="text-[10px] font-medium text-gray-400">Diperbarui hari ini pukul {{ $diperbaruiPada }}</p>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($tahapan as $tahap)
                <article wire:key="production-stage-{{ $tahap['kode'] }}" class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-[9px] font-bold text-gray-600">Pos {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        @if($tahap['jumlah_terlambat'] > 0)
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold text-amber-700"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>{{ $tahap['jumlah_terlambat'] }} terlambat</span>
                        @elseif($tahap['jumlah_pesanan'] > 0)
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold text-teal-700"><span class="h-1.5 w-1.5 rounded-full bg-teal-600"></span>Normal</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[9px] font-semibold text-gray-400"><span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>Belum ada antrean</span>
                        @endif
                    </div>
                    <h3 class="mt-3 text-sm font-black text-gray-900">{{ $tahap['nama'] }}</h3>
                    <p class="mt-1 min-h-8 text-[10px] leading-relaxed text-gray-500">
                        @switch($tahap['kode'])
                            @case('pemotongan_kain') Pemotongan pola kain sesuai spesifikasi pesanan.@break
                            @case('sablon') Proses sablon atau bordir pada bahan pakaian.@break
                            @case('penjahitan') Penyatuan potongan kain menjadi pakaian jadi.@break
                            @case('steam') Pemeriksaan akhir, steam, dan pengemasan.@break
                        @endswitch
                    </p>
                    <div class="mt-3 flex items-center justify-between text-[10px]">
                        <span class="font-semibold text-gray-500">Pesanan di tahap</span>
                        <span class="font-black text-gray-900">{{ number_format($tahap['jumlah_pesanan']) }}</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-teal-600 transition-all" style="width: {{ $tahap['porsi_beban'] }}%"></div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-[10px] text-gray-500">
                        <span>Beban berjalan</span>
                        <span class="font-bold text-gray-700">{{ number_format($tahap['jumlah_pcs']) }} pcs</span>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="flex flex-col gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">
        <div class="flex items-start gap-3">
            <span class="rounded-xl bg-teal-50 p-2 text-teal-700"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/></svg></span>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-[11px] font-black uppercase tracking-wide text-gray-900">Panduan alur pemantauan produksi</h2>
                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[9px] font-bold text-gray-600">SOP WORKSHOP</span>
                </div>
                <p class="mt-1 max-w-4xl text-xs leading-relaxed text-gray-600">Perbarui tahap dan progres pada SPK setelah pekerjaan selesai di setiap stasiun. Pesanan yang melewati tanggal estimasi akan ditandai untuk ditindaklanjuti; pesanan siap kirim tercatat setelah status produksi diperbarui.</p>
            </div>
        </div>
        <button type="button" wire:click="lihatSop" class="inline-flex shrink-0 items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-[10px] font-bold text-gray-700 transition hover:bg-gray-50">Lihat SOP Sablon & Packing</button>
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="flex flex-col gap-3 p-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="flex items-center gap-2 text-sm font-black text-gray-900"><span class="h-2 w-2 rounded-full bg-teal-600"></span>Tabel pantauan pesanan berjalan</h2>
                <p class="mt-1 text-[10px] text-gray-500">Daftar batch pengerjaan pakaian aktif di dalam workshop.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <label class="sr-only" for="filterTahapProduksi">Filter tahap produksi</label>
                <select id="filterTahapProduksi" wire:model.live="filterTahap" class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-[10px] font-semibold text-gray-700 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                    <option value="semua">Semua Tahap Produksi</option>
                    @foreach($opsiTahap as $kode => $nama)
                        <option value="{{ $kode }}">{{ $nama }}</option>
                    @endforeach
                </select>
                <label class="relative block sm:w-64">
                    <span class="sr-only">Cari pesanan produksi</span>
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari no. pesanan atau pelanggan..." class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-[10px] text-gray-800 placeholder:text-gray-400 focus:border-gray-400 focus:bg-white focus:outline-none focus:ring-1 focus:ring-gray-300">
                </label>
                <span class="text-[10px] font-semibold text-gray-500">{{ number_format($jumlahHasil) }} pesanan aktif</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-xs">
                <thead class="border-y border-gray-100 bg-gray-50 text-[9px] font-bold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">No. Produksi / Pesanan</th>
                        <th class="px-4 py-3">Nama Pemesan</th>
                        <th class="px-4 py-3">Jenis Baju & Jumlah</th>
                        <th class="px-4 py-3">Tahap Saat Ini</th>
                        <th class="px-4 py-3">Penanggung Jawab</th>
                        <th class="px-4 py-3">Progress</th>
                        <th class="px-4 py-3">Estimasi Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($daftarProduksi as $produksi)
                        @php
                            $pesanan = $produksi->pemesanan;
                            $tanggalEstimasi = $pesanan?->tanggal_selesai_estimasi;
                            $terlambat = $tanggalEstimasi && $tanggalEstimasi->isBefore(today());
                        @endphp
                        <tr wire:key="production-row-{{ $produksi->id }}" class="transition hover:bg-gray-50/80">
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="block font-mono text-[10px] font-bold text-gray-800">{{ $produksi->kode_produksi }}</span>
                                <span class="mt-1 block text-[9px] text-gray-500">{{ $pesanan?->nomor_pemesanan ?? 'Pesanan dihapus' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="block max-w-48 truncate font-semibold text-gray-800">{{ $pesanan?->pelanggan?->nama ?? 'Pelanggan' }}</span>
                                <span class="mt-1 block text-[9px] text-gray-500">{{ $pesanan?->warna }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="block font-semibold text-gray-800">{{ $pesanan?->jenis_kain }}</span>
                                <span class="mt-1 inline-flex rounded bg-gray-100 px-1.5 py-0.5 text-[9px] font-bold text-gray-600">{{ number_format($pesanan?->jumlah ?? 0) }} pcs</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap font-semibold text-gray-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-teal-600"></span>
                                    {{ $labelTahap[$produksi->tahap_saat_ini] ?? 'Tahap tidak diketahui' }}
                                </span>
                                <span class="mt-1 block text-[9px] text-gray-500">{{ $labelStatus[$produksi->status_produksi] ?? $produksi->status_produksi }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="flex items-center gap-1.5 text-[10px] font-semibold text-gray-700">
                                    <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    {{ $produksi->operator?->name ?? 'Belum ditentukan' }}
                                </span>
                            </td>
                            <td class="min-w-36 px-4 py-3">
                                <div class="flex items-center justify-between gap-2 text-[9px]">
                                    <span class="font-bold text-gray-800">{{ $produksi->progress_persen }}%</span>
                                    <span class="text-gray-500">{{ $terlambat ? 'Perlu perhatian' : 'Berjalan' }}</span>
                                </div>
                                <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full {{ $terlambat ? 'bg-amber-500' : 'bg-teal-600' }}" style="width: {{ min(max((int) $produksi->progress_persen, 0), 100) }}%"></div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @if($tanggalEstimasi)
                                    <span class="text-[10px] font-semibold {{ $terlambat ? 'text-rose-700' : 'text-gray-700' }}">{{ $tanggalEstimasi->format('d M Y') }}</span>
                                    @if($terlambat)
                                        <span class="mt-1 block text-[9px] font-bold text-rose-600">Terlambat</span>
                                    @endif
                                @else
                                    <span class="text-[10px] text-gray-400">Belum ditentukan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <p class="text-sm font-bold text-gray-700">Belum ada pesanan produksi aktif</p>
                                <p class="mt-1 text-xs text-gray-500">SPK aktif akan muncul di sini saat produksi dimulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-gray-100 bg-gray-50/70 px-4 py-3 text-[10px] text-gray-500 sm:flex-row sm:items-center sm:justify-between">
            <span>Menampilkan {{ $daftarProduksi->count() }} dari {{ number_format($jumlahHasil) }} batch produksi aktif</span>
            {{ $daftarProduksi->links() }}
        </div>
    </section>

    @if($showModalSop)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="tutupSop">
            <section role="dialog" aria-modal="true" aria-labelledby="judul-sop-produksi" class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 id="judul-sop-produksi" class="text-lg font-black text-gray-900">SOP Sablon & Packing</h2>
                        <p class="mt-1 text-xs text-gray-500">Langkah pemeriksaan sebelum batch diteruskan ke tahap berikutnya.</p>
                    </div>
                    <button type="button" wire:click="tutupSop" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100" aria-label="Tutup SOP">✕</button>
                </div>
                <ol class="space-y-3 p-5 text-xs leading-relaxed text-gray-700">
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-[10px] font-black text-teal-800">1</span><span>Cocokkan desain, warna, ukuran, dan jumlah dengan SPK sebelum mulai sablon atau bordir.</span></li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-[10px] font-black text-teal-800">2</span><span>Periksa hasil cetak pada sampel terlebih dahulu; hentikan proses jika posisi atau warna tidak sesuai persetujuan.</span></li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-[10px] font-black text-teal-800">3</span><span>Hitung dan periksa hasil jahitan serta finishing. Pisahkan produk cacat untuk diperbaiki sebelum dikemas.</span></li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-[10px] font-black text-teal-800">4</span><span>Lakukan steam, lipat dan kemas pakaian; beri label nomor pesanan dan jumlah agar siap diserahterimakan.</span></li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-[10px] font-black text-teal-800">5</span><span>Perbarui tahap dan progres pada SPK setelah pekerjaan selesai agar pantauan produksi tetap akurat.</span></li>
                </ol>
                <div class="flex justify-end border-t border-gray-100 px-5 py-4">
                    <button type="button" wire:click="tutupSop" class="rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white hover:bg-gray-800">Mengerti</button>
                </div>
            </section>
        </div>
    @endif
</div>
