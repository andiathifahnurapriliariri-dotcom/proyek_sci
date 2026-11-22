<div class="space-y-5">
    @if($pesanSukses)
        <div class="flex items-center justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <div>
                <h2 class="text-xs font-bold text-emerald-900">Mutasi stok berhasil dicatat</h2>
                <p class="mt-1 text-xs text-emerald-700">{{ $pesanSukses }}</p>
            </div>
            <button type="button" wire:click="$set('pesanSukses', '')" class="rounded-lg p-1 text-emerald-700 hover:bg-emerald-100" aria-label="Tutup notifikasi">✕</button>
        </div>
    @endif

    @error('aksi')
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700">{{ $message }}</div>
    @enderror

    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="flex flex-wrap items-center gap-1.5 text-[10px] font-semibold text-gray-500">
                <span>Manajemen Inventori</span><span>›</span><span class="text-gray-800">Mutasi Stok Produk Jadi</span>
            </div>
            <h1 class="mt-2 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Riwayat Keluar Masuk Baju Jadi (Mutasi Stok Pakaian)</h1>
            <p class="mt-1 max-w-3xl text-sm leading-relaxed text-gray-500">Catatan pergerakan produk pakaian yang selesai diproduksi masuk ke rak siap kirim atau dikeluarkan untuk pengiriman pesanan dan serah terima pelanggan.</p>
        </div>
        <div class="flex shrink-0 flex-col gap-2 sm:flex-row lg:flex-col">
            <button type="button" wire:click="bukaModalMasuk" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                <svg class="h-4 w-4 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/><path d="M4 20h16"/></svg>
                Catat Baju Masuk (Dari Produksi)
            </button>
            <button type="button" wire:click="bukaModalKeluar" class="inline-flex items-center justify-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h12v11H3zM15 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="18.5" cy="19" r="1.5"/></svg>
                Catat Baju Keluar (Kirim/Ambil)
            </button>
        </div>
    </div>

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

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="flex flex-col gap-3 p-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-1 rounded-xl bg-gray-100 p-1">
                @foreach(['semua' => 'Semua Catatan', 'masuk' => 'Baju Masuk (Selesai Produksi)', 'keluar' => 'Baju Keluar (Kirim/Diambil)'] as $tab => $label)
                    <button
                        type="button"
                        wire:key="mutation-tab-{{ $tab }}"
                        wire:click="setTab('{{ $tab }}')"
                        @class([
                            'rounded-lg px-3 py-2 text-[10px] font-bold transition sm:text-[11px]',
                            'bg-white text-gray-900 shadow-sm' => $filterTab === $tab,
                            'text-gray-600 hover:bg-white/70' => $filterTab !== $tab,
                        ])
                    >{{ $label }}</button>
                @endforeach
            </div>
            <label class="relative block w-full lg:max-w-sm">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari nomor pesanan, nama produk pakaian..." class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-3 text-xs text-gray-800 placeholder:text-gray-400 focus:border-gray-400 focus:bg-white focus:outline-none focus:ring-1 focus:ring-gray-300">
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
                <button type="button" wire:click="muatLebihBanyak" wire:loading.attr="disabled" class="rounded-lg border border-gray-200 bg-white px-3 py-2 font-bold text-gray-700 hover:bg-gray-50">Muat Lebih Banyak</button>
            @endif
        </div>
    </section>

    <section class="flex items-start gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5">
        <span class="rounded-xl bg-black p-2 text-white">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 3v3h8V3M8 13l2.5 2.5L16 10"/></svg>
        </span>
        <div>
            <h2 class="text-[11px] font-black uppercase tracking-wide text-gray-900">Pengingat standar pengelolaan stok baju jadi (SOP)</h2>
            <p class="mt-1 text-xs leading-relaxed text-gray-600">Setiap pengeluaran baju jadi wajib dicocokkan dengan nomor pesanan (ORD). Penambahan stok baju ready otomatis tercatat saat operator QC memvalidasi batch pakaian selesai.</p>
        </div>
    </section>

    @if($showModalMutasi)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="tutupModalMutasi">
            <section class="max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">{{ $jenisPergerakan === 'masuk' ? 'Catat Baju Masuk dari Produksi' : 'Catat Baju Keluar' }}</h2>
                        <p class="mt-1 text-xs text-gray-500">{{ $jenisPergerakan === 'masuk' ? 'Barang selesai QC menambah saldo stok siap kirim.' : 'Pilih pesanan dan tujuan pengeluaran agar saldo stok tercatat akurat.' }}</p>
                    </div>
                    <button type="button" wire:click="tutupModalMutasi" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100" aria-label="Tutup">✕</button>
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
                        <textarea id="keterangan" rows="2" wire:model="keterangan" placeholder="{{ $jenisPergerakan === 'masuk' ? 'Contoh: QC selesai, siap masuk rak display' : 'Contoh: 4 ekspedisi dan pelanggan' }}" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-xs focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600"></textarea>
                        @error('keterangan') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                        <button type="button" wire:click="tutupModalMutasi" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="simpanMutasi" class="rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50">
                            <span wire:loading.remove wire:target="simpanMutasi">Simpan Mutasi</span>
                            <span wire:loading wire:target="simpanMutasi">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </section>
        </div>
    @endif
</div>
