<div class="space-y-6">
    @if($pesanSukses)
        <div class="flex items-center justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <div>
                <h2 class="text-xs font-bold text-emerald-900">Pembayaran berhasil diproses</h2>
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
            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-200 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-gray-700">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>
                Arus kas pesanan konveksi
            </span>
            <h1 class="mt-2 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Catatan Pembayaran Pelanggan</h1>
            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-gray-500">Pantau uang muka (DP), sisa tagihan, dan bukti transfer pembayaran pesanan.</p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 whitespace-nowrap">
            <button type="button" wire:click="eksporRekap" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 active:scale-95 whitespace-nowrap cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg>
                <span>Ekspor Rekap Pembayaran</span>
            </button>
            <button type="button" wire:click="bukaModalCreate" class="inline-flex items-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800 active:scale-95 whitespace-nowrap cursor-pointer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
                <span>Catat Pembayaran Masuk</span>
            </button>
        </div>
    </div>

    <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Total penerimaan bulan ini</p>
                    <p class="mt-4 text-2xl font-black tracking-tight text-gray-900">Rp {{ number_format((float) $totalDiterimaBulanIni, 0, ',', '.') }}</p>
                    <p class="mt-1 text-[11px] text-gray-500">Pembayaran yang sudah diterima</p>
                </div>
                <span class="rounded-xl bg-teal-100 p-2 text-teal-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h3"/></svg>
                </span>
            </div>
        </article>

        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Uang muka (DP) menunggu cek</p>
                    <p class="mt-4 text-2xl font-black tracking-tight text-gray-900">Rp {{ number_format((float) $nominalPerluDicek, 0, ',', '.') }}</p>
                    <p class="mt-1 text-[11px] text-gray-500">{{ $jumlahPerluDicek }} transaksi perlu diverifikasi</p>
                </div>
                <span class="rounded-xl bg-amber-100 p-2 text-amber-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l2.5 2.5"/><circle cx="12" cy="12" r="9"/></svg>
                </span>
            </div>
        </article>

        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Tagihan belum lunas</p>
                    <p class="mt-4 text-2xl font-black tracking-tight text-gray-900">Rp {{ number_format((float) $totalTagihanTersisa, 0, ',', '.') }}</p>
                    <p class="mt-1 text-[11px] text-gray-500">Sisa tagihan seluruh pesanan aktif</p>
                </div>
                <span class="rounded-xl bg-gray-100 p-2 text-gray-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M8 9h8M8 13h8M8 17h4"/></svg>
                </span>
            </div>
        </article>

        <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div class="w-full">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500">Progres pembayaran</p>
                    <p class="mt-4 text-2xl font-black tracking-tight text-gray-900">{{ number_format((float) $persentasePembayaran, 1, ',', '.') }}%</p>
                    <p class="mt-1 text-[11px] text-gray-500">Proporsi nilai pesanan yang telah dibayar</p>
                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-teal-600 transition-all" style="width: {{ $persentasePembayaran }}%"></div>
                    </div>
                </div>
                <span class="rounded-xl bg-teal-100 p-2 text-teal-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/></svg>
                </span>
            </div>
        </article>
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 p-4 space-y-3.5">
            {{-- 1. Pencarian Nomor Pesanan / Pemesan / Bukti (Berada di Atas) --}}
            <div class="relative w-full">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari no. pesanan, nama pemesan, atau bukti pembayaran..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50/70 py-2.5 pl-10 pr-4 text-xs text-gray-800 placeholder:text-gray-400 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black transition"
                >
            </div>

            {{-- 2. Action Button Tabs: Semua Transaksi, Perlu Dicek, DP Diterima, Sudah Lunas (Sejajar di Bawah Pencarian) --}}
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar p-1 rounded-xl bg-gray-100/90 w-fit max-w-full">
                @foreach([
                    'semua' => 'Semua Transaksi',
                    'perlu_dicek' => 'Perlu Dicek',
                    'dp_diterima' => 'DP Diterima',
                    'sudah_lunas' => 'Sudah Lunas',
                ] as $tab => $label)
                    <button
                        type="button"
                        wire:key="payment-tab-{{ $tab }}"
                        wire:click="setTab('{{ $tab }}')"
                        @class([
                            'rounded-lg px-3.5 py-2 text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer shrink-0',
                            'bg-black text-white shadow-sm' => $tabStatus === $tab,
                            'text-gray-600 hover:text-gray-900 hover:bg-white/70' => $tabStatus !== $tab,
                        ])
                    >
                        <span>{{ $label }}</span>
                        @if($tab === 'perlu_dicek' && $jumlahPerluDicek > 0)
                            <span class="rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-extrabold text-amber-800">{{ $jumlahPerluDicek }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-xs">
                <thead class="border-b border-gray-100 bg-gray-50 text-[9px] font-bold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">No. Bukti Kas</th>
                        <th class="px-4 py-3">Pesanan & Pelanggan</th>
                        <th class="px-4 py-3">Waktu Transfer</th>
                        <th class="px-4 py-3">Jenis Pembayaran</th>
                        <th class="px-4 py-3 text-right">Nominal Masuk</th>
                        <th class="px-4 py-3">Cara Bayar</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Bukti & Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pembayaran as $item)
                        <tr wire:key="payment-{{ $item->id }}" class="transition hover:bg-gray-50/80">
                            <td class="px-4 py-4">
                                <span class="font-bold text-gray-900">{{ $item->kode_pembayaran }}</span>
                                <span class="mt-1 block text-[10px] text-gray-400">{{ $item->admin?->name ?? 'Admin' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.pesanan.index') }}" class="font-bold text-gray-900 hover:underline">{{ $item->pemesanan?->nomor_pemesanan ?? '-' }}</a>
                                <span class="mt-1 block text-[10px] text-gray-500">{{ $item->pemesanan?->pelanggan?->nama ?? 'Pelanggan tidak tersedia' }}</span>
                            </td>
                            <td class="px-4 py-4 text-gray-700">
                                {{ $item->tanggal_bayar?->format('d M Y') ?? '-' }}
                                <span class="mt-1 block text-[10px] text-gray-400">{{ $item->tanggal_bayar?->format('H:i') ?? '' }} WIB</span>
                            </td>
                            <td class="px-4 py-4">
                                <span @class([
                                    'inline-flex rounded-lg px-2.5 py-1 text-[10px] font-bold',
                                    'bg-teal-50 text-teal-800' => $item->jenis_pembayaran === 'DP',
                                    'bg-indigo-50 text-indigo-700' => $item->jenis_pembayaran === 'Pelunasan',
                                ])>{{ $item->jenis_pembayaran }}</span>
                            </td>
                            <td class="px-4 py-4 text-right font-bold text-gray-900">Rp {{ number_format((float) $item->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-gray-700">{{ $item->metode_pembayaran }}</td>
                            <td class="px-4 py-4">
                                @if($item->status_konfirmasi === 'Diterima')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg>
                                        Berhasil Diterima
                                    </span>
                                @elseif($item->status_konfirmasi === 'Ditolak')
                                    <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-700">Ditolak</span>
                                @else
                                    <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-800">Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="flex flex-wrap justify-end items-center gap-1.5">
                                    <button type="button" wire:click="lihatBukti({{ $item->id }})" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-[10px] font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-2xs cursor-pointer whitespace-nowrap">
                                        <svg class="h-3 w-3 text-gray-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                        </svg>
                                        <span class="whitespace-nowrap">Lihat Struk</span>
                                    </button>
                                    @if($item->status_konfirmasi === 'Menunggu Verifikasi')
                                        <button type="button" wire:click="konfirmasiPembayaran({{ $item->id }}, 'Diterima')" class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-[10px] font-bold text-white hover:bg-emerald-700 transition shadow-2xs cursor-pointer">Terima</button>
                                        <button type="button" wire:click="konfirmasiPembayaran({{ $item->id }}, 'Ditolak')" class="rounded-lg bg-rose-50 px-2.5 py-1.5 text-[10px] font-bold text-rose-700 hover:bg-rose-100 transition shadow-2xs cursor-pointer">Tolak</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <p class="text-sm font-bold text-gray-700">Belum ada transaksi pembayaran</p>
                                <p class="mt-1 text-xs text-gray-500">Transaksi baru akan muncul di daftar ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pembayaran->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $pembayaran->links() }}</div>
        @endif
    </section>

    @if($showModalCreate)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4" wire:click.self="tutupModalCreate">
            <section class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">Catat Pembayaran Masuk</h2>
                        <p class="mt-1 text-xs text-gray-500">Isi transaksi yang diterima dan lampirkan bukti jika tersedia.</p>
                    </div>
                    <button type="button" wire:click="tutupModalCreate" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100" aria-label="Tutup">✕</button>
                </div>

                <form wire:submit="simpanPembayaran" class="space-y-4 p-5">
                    <div>
                        <label for="pemesananId" class="mb-1.5 block text-xs font-bold text-gray-700">Pesanan <span class="text-rose-500">*</span></label>
                        <select id="pemesananId" wire:model.live="pemesananId" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs text-gray-800 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            <option value="">Pilih pesanan</option>
                            @foreach($pesananTersedia as $pesanan)
                                <option wire:key="order-option-{{ $pesanan->id }}" value="{{ $pesanan->id }}">
                                    {{ $pesanan->nomor_pemesanan }} · {{ $pesanan->pelanggan?->nama ?? 'Pelanggan' }} · Sisa Rp {{ number_format(max(0, (float) $pesanan->total_harga - (float) $pesanan->total_dicadangkan), 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @if($pesananTersedia->isEmpty())
                            <p class="mt-1 text-[10px] text-gray-500">Tidak ada pesanan aktif dengan tagihan tersisa.</p>
                        @endif
                        @error('pemesananId') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="jenisPembayaran" class="mb-1.5 block text-xs font-bold text-gray-700">Jenis pembayaran <span class="text-rose-500">*</span></label>
                            <select id="jenisPembayaran" wire:model="jenisPembayaran" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs text-gray-800 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                                <option value="DP">Uang muka (DP)</option>
                                <option value="Pelunasan">Pelunasan</option>
                            </select>
                            @error('jenisPembayaran') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="jumlahBayar" class="mb-1.5 block text-xs font-bold text-gray-700">Nominal masuk (Rp) <span class="text-rose-500">*</span></label>
                            <input id="jumlahBayar" type="text" inputmode="numeric" wire:model.live="jumlahBayar" placeholder="Contoh: 390.000 atau 390000" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @if(filled($jumlahBayar) && (int) preg_replace('/[^0-9]/', '', (string) $jumlahBayar) > 0)
                                <p class="mt-1 text-[10px] text-teal-700 font-semibold">Format terbaca: Rp {{ number_format((float) preg_replace('/[^0-9]/', '', (string) $jumlahBayar), 0, ',', '.') }}</p>
                            @endif
                            @error('jumlahBayar') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="tanggalBayar" class="mb-1.5 block text-xs font-bold text-gray-700">Waktu transfer <span class="text-rose-500">*</span></label>
                            <input id="tanggalBayar" type="datetime-local" wire:model="tanggalBayar" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs text-gray-800 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                            @error('tanggalBayar') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="metodePembayaran" class="mb-1.5 block text-xs font-bold text-gray-700">Cara bayar <span class="text-rose-500">*</span></label>
                            <select id="metodePembayaran" wire:model="metodePembayaran" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs text-gray-800 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Tunai">Tunai</option>
                            </select>
                            @error('metodePembayaran') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="statusKonfirmasi" class="mb-1.5 block text-xs font-bold text-gray-700">Status transaksi <span class="text-rose-500">*</span></label>
                            <select id="statusKonfirmasi" wire:model="statusKonfirmasi" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs text-gray-800 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600">
                                <option value="Diterima">Diterima</option>
                                <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                            </select>
                            @error('statusKonfirmasi') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="buktiPembayaran" class="mb-1.5 block text-xs font-bold text-gray-700">Bukti transfer <span class="font-normal text-gray-400">(opsional, maks. 5 MB)</span></label>
                            <input id="buktiPembayaran" type="file" wire:model="buktiPembayaran" accept=".jpg,.jpeg,.png,.webp,.pdf" class="block w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-[10px] text-gray-700 file:mr-2 file:rounded-md file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:font-bold">
                            @if($buktiPembayaran)
                                <p class="mt-1 text-[10px] text-teal-700">{{ $buktiPembayaran->getClientOriginalName() }}</p>
                            @endif
                            <div wire:loading wire:target="buktiPembayaran" class="mt-1 text-[10px] text-gray-500">Mengunggah bukti...</div>
                            @error('buktiPembayaran') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="catatanPembayaran" class="mb-1.5 block text-xs font-bold text-gray-700">Catatan <span class="font-normal text-gray-400">(opsional)</span></label>
                            <textarea id="catatanPembayaran" rows="2" wire:model="catatan" placeholder="Contoh: pembayaran diterima melalui rekening operasional" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs text-gray-800 placeholder:text-gray-400 focus:border-teal-600 focus:outline-none focus:ring-1 focus:ring-teal-600"></textarea>
                            @error('catatan') <p class="mt-1 text-[10px] font-semibold text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                        <button type="button" wire:click="tutupModalCreate" class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="simpanPembayaran,buktiPembayaran" class="rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50">
                            <span wire:loading.remove wire:target="simpanPembayaran">Simpan Pembayaran</span>
                            <span wire:loading wire:target="simpanPembayaran">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </section>
        </div>
    @endif

    {{-- MODAL STRUK PEMBAYARAN RESMI COTTON.id --}}
    @if($showModalBukti && $selectedPayment)
        @php
            $pesananStruk = $selectedPayment->pemesanan;
            $pelangganStruk = $pesananStruk?->pelanggan;
            $totalHargaStruk = (float) ($pesananStruk?->total_harga ?? 0);
            $totalDiterimaStruk = (float) ($pesananStruk?->pembayaran()->where('status_konfirmasi', 'Diterima')->sum('jumlah_bayar') ?? 0);
            $sisaTagihanStruk = max(0, $totalHargaStruk - $totalDiterimaStruk);
            $terbilangNominal = \App\Livewire\Admin\ManajemenPembayaran::terbilang((int) $selectedPayment->jumlah_bayar);
            $adaFileFisik = $selectedPayment->bukti_pembayaran && \Illuminate\Support\Facades\Storage::disk('local')->exists($selectedPayment->bukti_pembayaran);
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/70 backdrop-blur-xs p-3 sm:p-5 overflow-y-auto" wire:click.self="tutupModalBukti">
            <section class="flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl border border-gray-100 my-auto">
                {{-- Header Modal --}}
                <div class="flex items-center justify-between gap-4 border-b border-gray-100 bg-gray-50/80 px-5 py-3.5 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-gray-900 leading-tight">Struk Kuitansi Pembayaran</h2>
                            <p class="text-[11px] text-gray-500 font-medium">Bukti transaksi sah kas masuk sistem COTTON.id</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Tombol Cetak Struk --}}
                        <button type="button" onclick="cetakStrukKuitansi()" class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white px-3.5 py-2 text-xs font-bold transition shadow-xs cursor-pointer">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Cetak Struk</span>
                        </button>

                        {{-- Tombol Buka di Tab Baru --}}
                        <a href="{{ route('admin.pembayaran.bukti', $selectedPayment) }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 text-xs font-bold transition shadow-2xs">
                            <svg class="h-3.5 w-3.5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            <span>Buka Tab Baru</span>
                        </a>

                        {{-- Tombol Tutup --}}
                        <button type="button" wire:click="tutupModalBukti" class="rounded-xl p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition cursor-pointer" aria-label="Tutup">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Konten Struk Kuitansi yang Dapat Dicetak --}}
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50/50">
                    <div id="area-struk-pembayaran" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 relative overflow-hidden max-w-2xl mx-auto">
                        
                        {{-- Watermark Logo COTTON.id --}}
                        <div class="pointer-events-none absolute -right-6 -bottom-6 select-none opacity-[0.03]">
                            <img src="{{ asset('images/logo-cotton.jpg') }}" alt="Watermark" class="w-64 h-64 object-cover rounded-full">
                        </div>

                        {{-- 1. Header Kuitansi & Identitas Konveksi --}}
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-5 border-b-2 border-dashed border-gray-200">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200 p-0.5 bg-black shrink-0 shadow-2xs">
                                    <img src="{{ asset('images/logo-cotton.jpg') }}" alt="COTTON.id" class="w-full h-full object-cover rounded-full">
                                </div>
                                <div>
                                    <h1 class="text-lg font-black tracking-tight text-gray-900 leading-tight">COTTON.id Konveksi</h1>
                                    <p class="text-xs font-bold text-teal-700">Workshop & Apparel Custom</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Jl. Pemuda No. 45, Semarang · Telp: (024) 876-5432 · WA: 0812-9988-1234</p>
                                </div>
                            </div>

                            <div class="text-left sm:text-right shrink-0">
                                <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200">
                                    Kuitansi Kas Masuk
                                </span>
                                <p class="font-mono text-sm font-black text-gray-900 mt-1.5">{{ $selectedPayment->kode_pembayaran }}</p>
                                <p class="text-[11px] text-gray-500 font-medium">{{ $selectedPayment->tanggal_bayar?->translatedFormat('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        {{-- 2. Data Pelanggan & Pesanan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-5 text-xs border-b border-gray-100">
                            <div class="space-y-2">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Telah Diterima Dari:</span>
                                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $pelangganStruk?->nama ?? 'Pelanggan Umum' }}</p>
                                    @if($pelangganStruk?->telepon)
                                        <p class="text-gray-500 font-medium">{{ $pelangganStruk->telepon }}</p>
                                    @endif
                                    @if($pelangganStruk?->alamat)
                                        <p class="text-gray-400 text-[11px] mt-0.5 leading-snug">{{ $pelangganStruk->alamat }}</p>
                                    @endif
                                </div>

                                <div class="pt-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Untuk Pembayaran:</span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span @class([
                                            'inline-flex px-2 py-0.5 rounded text-[10px] font-bold',
                                            'bg-teal-50 text-teal-800 border border-teal-200' => $selectedPayment->jenis_pembayaran === 'DP',
                                            'bg-indigo-50 text-indigo-700 border border-indigo-200' => $selectedPayment->jenis_pembayaran === 'Pelunasan',
                                        ])>
                                            {{ $selectedPayment->jenis_pembayaran === 'DP' ? 'Uang Muka (DP)' : 'Pelunasan Tagihan' }}
                                        </span>
                                        <span class="font-semibold text-gray-700">Pesanan {{ $pesananStruk?->nomor_pemesanan ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 sm:border-l sm:border-gray-100 sm:pl-4">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Rincian SPK / Pesanan:</span>
                                    <p class="font-bold text-gray-800 mt-0.5">{{ $pesananStruk?->jenis_kain ?? 'Apparel Konveksi' }}</p>
                                    <p class="text-gray-500">Jumlah: <strong class="text-gray-900">{{ $pesananStruk?->jumlah ?? 0 }} pcs</strong></p>
                                </div>

                                <div class="pt-1 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Metode Pembayaran:</span>
                                        <p class="font-bold text-gray-800 mt-0.5">{{ $selectedPayment->metode_pembayaran }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Status Transaksi:</span>
                                        @if($selectedPayment->status_konfirmasi === 'Diterima')
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 mt-0.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                                Diterima Sah
                                            </span>
                                        @elseif($selectedPayment->status_konfirmasi === 'Ditolak')
                                            <span class="text-[11px] font-bold text-rose-600 mt-0.5">Ditolak</span>
                                        @else
                                            <span class="text-[11px] font-bold text-amber-600 mt-0.5">Menunggu Verifikasi</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Box Nominal Pembayaran Diterima & Terbilang --}}
                        <div class="my-5 p-4 sm:p-5 rounded-2xl bg-teal-50/70 border border-teal-200/80">
                            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2">
                                <span class="text-[11px] font-bold text-teal-800 uppercase tracking-wider">Jumlah Uang Diterima:</span>
                                <span class="text-2xl sm:text-3xl font-black text-teal-900">
                                    Rp {{ number_format((float) $selectedPayment->jumlah_bayar, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="mt-2.5 pt-2 border-t border-teal-200/50">
                                <p class="text-xs text-teal-800 italic leading-relaxed font-medium">
                                    <span class="font-bold not-italic text-[10px] text-teal-600 uppercase">Terbilang:</span>
                                    "{{ $terbilangNominal }} Rupiah"
                                </p>
                            </div>
                        </div>

                        {{-- 4. Ringkasan Status Tagihan Pesanan --}}
                        <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 text-xs space-y-2">
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Total Nilai Pesanan (SPK):</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($totalHargaStruk, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Total Diterima (Kumulatif):</span>
                                <span class="font-bold text-teal-700">Rp {{ number_format($totalDiterimaStruk, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-gray-200/60 font-bold">
                                <span class="text-gray-700">Sisa Tagihan Pesanan:</span>
                                @if($sisaTagihanStruk <= 0)
                                    <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full font-black text-xs border border-emerald-200">
                                        ✓ LUNAS
                                    </span>
                                @else
                                    <span class="text-rose-600 font-black">Rp {{ number_format($sisaTagihanStruk, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Catatan (jika ada) --}}
                        @if($selectedPayment->catatan)
                            <div class="mt-4 p-3 rounded-xl bg-amber-50/60 border border-amber-200/60 text-xs text-amber-900">
                                <span class="font-bold text-[10px] text-amber-700 uppercase tracking-wider block">Catatan Transaksi:</span>
                                <p class="mt-0.5 leading-relaxed">{{ $selectedPayment->catatan }}</p>
                            </div>
                        @endif

                        {{-- 5. Footer & Pengesahan Kuitansi --}}
                        <div class="mt-6 pt-5 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-gray-500">
                            <div class="max-w-xs text-center sm:text-left">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 mb-2">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>Terverifikasi Sistem COTTON.id</span>
                                </div>
                                <p class="text-[10px] text-gray-400 leading-tight">
                                    Struk kuitansi ini sah dan diterbitkan otomatis oleh sistem konveksi COTTON.id sebagai bukti transaksi kas yang valid.
                                </p>
                            </div>

                            <div class="text-center sm:text-right shrink-0">
                                <p class="text-[11px] text-gray-500">Semarang, {{ $selectedPayment->tanggal_bayar?->translatedFormat('d F Y') }}</p>
                                <p class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-wider">Kasir / Penerima,</p>
                                <div class="h-12 flex items-center justify-center sm:justify-end">
                                    <span class="text-xs font-serif italic text-teal-800 font-bold border-b border-gray-300 pb-0.5">
                                        {{ $selectedPayment->admin?->name ?? 'Admin Workshop' }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-400 font-mono">ID Admin: #{{ $selectedPayment->user_id ?? 1 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Lampiran Bukti Fisik / Slip Transfer (Jika Ada File Terunggah) --}}
                    @if($adaFileFisik)
                        <div class="mt-5 max-w-2xl mx-auto rounded-2xl bg-white border border-gray-200 p-4 shadow-xs">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 mb-3">
                                <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                    <span>Lampiran Slip / Bukti Transfer Fisik:</span>
                                </span>
                                <a href="{{ route('admin.pembayaran.bukti', $selectedPayment) }}" target="_blank" class="text-[11px] font-bold text-teal-600 hover:text-teal-800">
                                    Unduh File Asli ↗
                                </a>
                            </div>
                            <div class="rounded-xl overflow-hidden bg-gray-100 border border-gray-200 flex justify-center p-2">
                                <img src="{{ route('admin.pembayaran.bukti', $selectedPayment) }}" alt="Bukti Transfer Asli" class="max-h-80 w-auto object-contain rounded-lg">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer Modal --}}
                <div class="flex items-center justify-between gap-3 border-t border-gray-100 bg-gray-50/80 px-5 py-3 shrink-0">
                    <span class="text-[11px] text-gray-500 font-medium">
                        Kode Pembayaran: <strong class="text-gray-900">{{ $selectedPayment->kode_pembayaran }}</strong>
                    </span>
                    <button type="button" wire:click="tutupModalBukti" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-2xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </section>
        </div>

        {{-- Script Print Struk Khusus --}}
        <script>
            function cetakStrukKuitansi() {
                const strukEl = document.getElementById('area-struk-pembayaran');
                if (!strukEl) return;
                
                const printWindow = window.open('', '_blank', 'width=800,height=900');
                if (!printWindow) {
                    window.print();
                    return;
                }
                
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Cetak Struk - {{ $selectedPayment->kode_pembayaran }}</title>
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <style>
                            * { font-family: 'Plus Jakarta Sans', sans-serif !important; }
                            @page { size: auto; margin: 10mm; }
                            body { background: white; margin: 0; padding: 15px; }
                        </style>
                    </head>
                    <body>
                        <div class="max-w-2xl mx-auto">
                            ${strukEl.innerHTML}
                        </div>
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(function() { window.close(); }, 800);
                            };
                        <\/script>
                    </body>
                    </html>
                `);
                printWindow.document.close();
            }
        </script>
    @endif
</div>
