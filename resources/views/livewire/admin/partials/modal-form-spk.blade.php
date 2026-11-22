{{-- MODAL FORM INPUT PESANAN (SPK) REUSABLE & KONSISTEN --}}
@if($showModalCreate || $showModalPesanan)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 sm:p-8 space-y-5 relative my-8 animate-in fade-in duration-200">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ $editPesananId ? 'Edit Pesanan (SPK)' : 'Buat Pesanan Baru (SPK)' }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $editPesananId ? 'Perbarui rincian pesanan dan variasi yang tersimpan.' : 'Input spesifikasi pakaian dan target deadline konveksi.' }}</p>
                </div>
                <button
                    type="button"
                    wire:click="tutupModalCreate"
                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-sm transition cursor-pointer"
                >
                    ✕
                </button>
            </div>

            <form wire:submit="simpanPesanan" class="max-h-[70vh] space-y-4 overflow-y-auto pr-1">
                <section class="space-y-3">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Nama / Instansi <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model.live.debounce.300ms="nama_pelanggan" placeholder="Contoh: PT Sinergi Kreatif" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            @error('nama_pelanggan') <span class="text-[10px] font-semibold text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-gray-700">Kontak WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" wire:model="nomor_telepon" placeholder="Contoh: 0812-3456-7890" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            @error('nomor_telepon') <span class="text-[10px] font-semibold text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold text-gray-700">Alamat <span class="text-rose-500">*</span></label>
                            <textarea wire:model="alamat" rows="2" placeholder="Contoh: Jl. Riau No. 128, Bandung" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black"></textarea>
                            @error('alamat') <span class="text-[10px] font-semibold text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </section>

                <section class="space-y-4 border-t border-gray-100 pt-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-wide text-gray-500">Spesifikasi Produk & Pilihan Variasi</h4>

                    {{-- TABEL DINAMIS: RINCIAN VARIASI PESANAN --}}
                    <div class="space-y-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-2xs">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-gray-100 pb-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-md bg-[#0D7A68] text-[10px] font-black text-white">✓</span>
                                    <label class="text-xs font-bold text-gray-900">Rincian Variasi Pesanan <span class="text-rose-500">*</span></label>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5">Tabel dinamis variasi produk, kategori ukuran, warna, ukuran, dan jumlah (pcs) dengan harga satuan otomatis.</p>
                            </div>

                            <button type="button" wire:click="tambahVariasi" class="inline-flex items-center gap-1.5 self-start sm:self-auto rounded-xl bg-black px-3.5 py-2 text-xs font-bold text-white hover:bg-gray-800 transition active:scale-95 cursor-pointer shadow-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Tambah Variasi</span>
                            </button>
                        </div>

                        {{-- Alert Duplikasi Variasi dengan Tombol Gabungkan Jumlah --}}
                        @if($pesanErrorVariasi)
                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <span class="font-semibold">{{ $pesanErrorVariasi }}</span>
                                </div>
                                @if($infoDuplikat)
                                    <button type="button" wire:click="gabungkanVariasiDuplikat" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition cursor-pointer shadow-xs">
                                        <span>Gabungkan Jumlah</span>
                                    </button>
                                @endif
                            </div>
                        @endif
                        @error('variasi')
                            <div class="rounded-xl border border-rose-200 bg-rose-50 p-2.5 text-xs font-semibold text-rose-600">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- Tabel Rincian Variasi Pesanan --}}
                        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-gray-50 border-b border-gray-200 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                    <tr>
                                        <th class="py-2.5 px-3">Produk</th>
                                        <th class="py-2.5 px-3">Kategori</th>
                                        <th class="py-2.5 px-3">Warna</th>
                                        <th class="py-2.5 px-3">Ukuran</th>
                                        <th class="py-2.5 px-3 text-center">Jumlah (pcs)</th>
                                        <th class="py-2.5 px-3 text-right">Harga Satuan</th>
                                        <th class="py-2.5 px-3 text-right">Subtotal</th>
                                        <th class="py-2.5 px-3 text-center min-w-[70px]">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-800">
                                    @foreach($variasi as $index => $item)
                                        @php
                                            $kategoriDipilih = $item['kategori'] ?? 'Dewasa';
                                            $opsiUkuran = $this->getUkuranOptionsByKategori($kategoriDipilih);
                                        @endphp
                                        <tr class="hover:bg-gray-50/60 transition">
                                            {{-- 1. Produk --}}
                                            <td class="py-2 px-3">
                                                <select wire:model.live="variasi.{{ $index }}.produk" class="w-full min-w-[110px] rounded-lg border border-gray-200 bg-gray-50/70 px-2 py-1.5 text-xs font-semibold text-gray-900 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                                                    @foreach(self::DAFTAR_PRODUK as $prod)
                                                        <option value="{{ $prod }}">{{ $prod }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            {{-- 2. Kategori Ukuran --}}
                                            <td class="py-2 px-3">
                                                <select wire:model.live="variasi.{{ $index }}.kategori" class="w-full min-w-[95px] rounded-lg border border-gray-200 bg-gray-50/70 px-2 py-1.5 text-xs font-semibold text-gray-900 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                                                    @foreach(self::KATEGORI_UKURAN as $kat)
                                                        <option value="{{ $kat }}">{{ $kat }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            {{-- 3. Warna Kain --}}
                                            <td class="py-2 px-3">
                                                <select wire:model.live="variasi.{{ $index }}.warna" class="w-full min-w-[110px] rounded-lg border border-gray-200 bg-gray-50/70 px-2 py-1.5 text-xs font-medium text-gray-800 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                                                    @foreach(self::WARNA_MASTER as $w)
                                                        <option value="{{ $w }}">{{ $w }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            {{-- 4. Ukuran Baju Menyesuaikan Kategori --}}
                                            <td class="py-2 px-3">
                                                <select wire:model.live="variasi.{{ $index }}.ukuran" class="w-full min-w-[80px] rounded-lg border border-gray-200 bg-gray-50/70 px-2 py-1.5 text-xs font-bold text-gray-900 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                                                    @foreach($opsiUkuran as $uk)
                                                        <option value="{{ $uk }}">{{ $uk }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="mt-1 block font-mono text-[9px] font-bold text-teal-700">
                                                    SKU: {{ $this->getPreviewSkuUkuran($item['produk'] ?? '', $item['warna'] ?? '', $item['ukuran'] ?? '') }}
                                                </span>
                                            </td>

                                            {{-- 5. Jumlah Pcs --}}
                                            <td class="py-2 px-3 text-center">
                                                <input type="number" wire:model.live="variasi.{{ $index }}.jumlah" min="1" class="w-20 text-center rounded-lg border border-gray-200 bg-gray-50/70 px-2 py-1.5 text-xs font-black text-gray-900 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black" />
                                                @error("variasi.{$index}.jumlah") <span class="block text-[9px] text-rose-500 font-bold mt-0.5">{{ $message }}</span> @enderror
                                            </td>

                                            {{-- 6. Harga Satuan (Otomatis dari Master Harga) --}}
                                            <td class="py-2 px-3 text-right whitespace-nowrap">
                                                <span class="inline-block px-2 py-1 rounded bg-gray-100 text-gray-700 font-mono text-xs font-bold">
                                                    Rp {{ number_format($item['harga_satuan'] ?? 0, 0, ',', '.') }}
                                                </span>
                                            </td>

                                            {{-- 7. Subtotal (Otomatis: Jumlah x Harga Satuan) --}}
                                            <td class="py-2 px-3 text-right whitespace-nowrap font-black text-gray-900">
                                                Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}
                                            </td>

                                            {{-- 8. Aksi Hapus --}}
                                            <td class="py-2 px-3 text-center whitespace-nowrap min-w-[70px]">
                                                @if(count($variasi) > 1)
                                                    <button
                                                        type="button"
                                                        wire:click="hapusVariasi({{ $index }})"
                                                        class="inline-flex items-center justify-center p-2 rounded-xl text-rose-600 hover:text-white hover:bg-rose-600 bg-rose-50 border border-rose-200 transition cursor-pointer shadow-2xs group"
                                                        title="Hapus variasi baris ini"
                                                        aria-label="Hapus variasi"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 6h18"/>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                            <line x1="10" x2="10" y1="11" y2="17"/>
                                                            <line x1="14" x2="14" y1="11" y2="17"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        disabled
                                                        class="inline-flex items-center justify-center p-2 rounded-xl text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed opacity-60"
                                                        title="Minimal harus ada 1 variasi pesanan"
                                                        aria-label="Hapus variasi (dinonaktifkan)"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 6h18"/>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                            <line x1="10" x2="10" y1="11" y2="17"/>
                                                            <line x1="14" x2="14" y1="11" y2="17"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- 6. RINGKASAN PESANAN DI BAWAH TABEL (4 KARTU OTOMATIS) --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-gray-100">
                            {{-- Total Jenis Produk --}}
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-200/70">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Jenis Produk</span>
                                <div class="mt-1 flex items-baseline gap-1">
                                    <span class="text-base font-black text-gray-900">{{ $totalJenisProduk }}</span>
                                    <span class="text-xs text-gray-500 font-semibold">Jenis</span>
                                </div>
                            </div>

                            {{-- Total Variasi --}}
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-200/70">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Variasi</span>
                                <div class="mt-1 flex items-baseline gap-1">
                                    <span class="text-base font-black text-gray-900">{{ $totalVariasi }}</span>
                                    <span class="text-xs text-gray-500 font-semibold">Variasi</span>
                                </div>
                            </div>

                            {{-- Total Seluruh Pakaian (pcs) --}}
                            <div class="p-3 rounded-xl bg-teal-50/70 border border-teal-200/60">
                                <span class="text-[10px] font-bold text-teal-700 uppercase tracking-wider block">Total Seluruh Pakaian</span>
                                <div class="mt-1 flex items-baseline gap-1">
                                    <span class="text-base font-black text-teal-900">{{ $totalPakaianPcs }}</span>
                                    <span class="text-xs text-teal-700 font-semibold">Pcs</span>
                                </div>
                            </div>

                            {{-- Total Harga Pesanan --}}
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200/70">
                                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Total Harga Pesanan</span>
                                <div class="mt-1">
                                    <span class="text-base font-black text-emerald-900">Rp {{ number_format($totalHargaPesanan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-3 border-t border-gray-100 pt-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-700">Target Batas Waktu <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="tanggal_deadline" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                        @error('tanggal_deadline') <span class="text-[10px] font-semibold text-rose-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-700">Pembayaran Awal</label>
                        <select wire:model="status_pembayaran_awal" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            <option value="dp">✓ DP 50% Diterima (Mulai Produksi)</option>
                            <option value="lunas">✓ Sudah Lunas 100%</option>
                            <option value="belum_bayar">Belum Bayar (Menunggu Konfirmasi)</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-xs font-bold text-gray-700">Catatan Khusus Sablon</label>
                        <textarea wire:model="catatan_tambahan" rows="2" placeholder="Catatan posisi sablon, desain, teknik afdruk..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-800 focus:border-black focus:bg-white focus:outline-none focus:ring-1 focus:ring-black"></textarea>
                        @error('catatan_tambahan') <span class="text-[10px] font-semibold text-rose-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-xs font-bold text-gray-700">File Desain <span class="font-normal text-gray-400">(opsional: JPG, PNG, WEBP, PDF; maks. 10 MB)</span></label>
                        <input type="file" wire:model="file_desain" accept=".jpg,.jpeg,.png,.webp,.pdf" class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-700 file:mr-3 file:rounded-md file:border-0 file:bg-gray-200 file:px-3 file:py-1.5 file:text-xs file:font-bold">
                        @if($file_desain)
                            <p class="mt-1 text-[10px] text-teal-700">{{ $file_desain->getClientOriginalName() }}</p>
                        @endif
                        <div wire:loading wire:target="file_desain" class="mt-1 text-[10px] text-gray-500">Mengunggah file...</div>
                        @error('file_desain') <span class="text-[10px] font-semibold text-rose-500">{{ $message }}</span> @enderror
                    </div>
                </section>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button
                        type="button"
                        wire:click="tutupModalCreate"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="simpanPesanan,file_desain"
                        class="px-5 py-2 rounded-xl bg-black hover:bg-gray-800 text-xs font-bold text-white shadow-md active:scale-95 transition disabled:cursor-wait disabled:opacity-60 cursor-pointer"
                    >
                        <span wire:loading.remove wire:target="simpanPesanan">{{ $editPesananId ? 'Simpan Perubahan SPK' : 'Simpan & Terbitkan SPK' }}</span>
                        <span wire:loading wire:target="simpanPesanan">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
