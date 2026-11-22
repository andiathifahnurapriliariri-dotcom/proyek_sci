<div class="hidden md:flex items-center flex-1 max-w-lg mx-6 relative"
     x-data="{
         flatUrls: {{ json_encode(array_column($flatItems, 'url')) }},
         openSelected() {
             let idx = @js($selectedIndex);
             if (idx >= 0 && this.flatUrls[idx]) {
                 window.location.href = this.flatUrls[idx];
             }
         }
     }"
     x-on:click.outside="$wire.closeDropdown()"
     x-on:keydown.escape.window="$wire.closeDropdown()"
     x-on:keydown.arrow-down.prevent="$wire.selectNext({{ count($flatItems) }})"
     x-on:keydown.arrow-up.prevent="$wire.selectPrev({{ count($flatItems) }})"
     x-on:keydown.enter.prevent="openSelected()"
>
    {{-- Input Search Bar Header --}}
    <div class="relative w-full">
        {{-- Ikon Kaca Pembesar di Kiri --}}
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" x2="16.65" y1="21" y2="16.65"/>
            </svg>
        </span>

        {{-- Input Field Utama --}}
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            wire:focus="openDropdown"
            placeholder="Cari nomor pesanan, nama pemesan, atau baju..."
            class="w-full rounded-full border border-gray-200 bg-gray-50 py-2 pl-9 pr-10 text-xs text-gray-800 placeholder-gray-400 focus:border-teal-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-600 transition shadow-sm"
            autocomplete="off"
        />

        {{-- Indikator Kanan: Loading Spinner & Tombol Clear --}}
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 gap-1.5">
            {{-- Loading Spinner saat Query Diproses --}}
            <div wire:loading wire:target="query" class="text-teal-600">
                <svg class="animate-spin w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </div>

            {{-- Tombol Hapus (Clear Query) --}}
            @if(filled($query))
                <button
                    type="button"
                    wire:click="clearQuery"
                    class="text-gray-400 hover:text-gray-700 transition p-0.5 rounded-full hover:bg-gray-200"
                    title="Hapus pencarian (Esc)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- Dropdown Popover Hasil Pencarian --}}
    @if($isOpen && filled(trim($query)))
        <div class="absolute top-full left-0 right-0 mt-2 z-50 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden divide-y divide-gray-100 max-h-[75vh] flex flex-col transition-all duration-200">

            {{-- Header Ringkasan Pencarian --}}
            <div class="px-4 py-2.5 bg-gray-50/90 flex items-center justify-between border-b border-gray-100 text-[11px]">
                <div class="flex items-center gap-1.5 text-gray-600 font-medium">
                    <span>Hasil untuk</span>
                    <span class="font-bold text-gray-900 bg-white px-2 py-0.5 rounded border border-gray-200 truncate max-w-[180px]">"{{ $query }}"</span>
                </div>
                <div class="flex items-center gap-1 text-gray-500 font-semibold">
                    <span class="px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 text-[10px] font-bold">{{ $totalHasil }} ditemukan</span>
                </div>
            </div>

            {{-- Body Hasil Pencarian (Scrollable) --}}
            <div class="overflow-y-auto divide-y divide-gray-100 flex-1">
                @php $globalIdx = 0; @endphp

                {{-- KATEGORI 1: MANAJEMEN PESANAN --}}
                @if($pesanan->isNotEmpty())
                    <div class="p-2 bg-white">
                        <div class="px-2.5 py-1.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-900">
                                <span class="w-5 h-5 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                    📋
                                </span>
                                <span>Manajemen Pesanan</span>
                                <span class="text-[10px] text-indigo-500 font-semibold bg-indigo-50 px-1.5 py-0.2 rounded-full">({{ $pesananTotal }})</span>
                            </div>
                            @if($pesananTotal > count($pesanan))
                                <a href="{{ route('admin.pesanan.index', ['search' => $query]) }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 transition">
                                    Lihat Semua &rarr;
                                </a>
                            @endif
                        </div>

                        <div class="mt-1 space-y-1">
                            @foreach($pesanan as $item)
                                @php
                                    $isSel = ($selectedIndex === $globalIdx);
                                    $itemUrl = route('admin.pesanan.index', ['search' => $item->nomor_pemesanan, 'detail' => $item->id]);
                                    $globalIdx++;
                                @endphp
                                <a
                                    href="{{ $itemUrl }}"
                                    class="group flex items-center justify-between p-2.5 rounded-xl transition text-left {{ $isSel ? 'bg-indigo-50/80 border border-indigo-200' : 'hover:bg-gray-50' }}"
                                >
                                    <div class="min-w-0 flex-1 pr-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-extrabold text-gray-900 group-hover:text-indigo-600 transition">{{ $item->nomor_pemesanan }}</span>
                                            <span class="text-xs text-gray-400">•</span>
                                            <span class="text-xs font-bold text-gray-700 truncate">{{ $item->pelanggan?->nama ?? 'Pelanggan Umum' }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 font-medium truncate mt-0.5">
                                            {{ $item->jenis_kain ?? 'Pakaian Konveksi' }}
                                            @if($item->warna) <span class="text-gray-400">|</span> Warna: {{ $item->warna }} @endif
                                            <span class="text-gray-400">|</span> {{ $item->jumlah }} Pcs
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        @php
                                            $badgeColor = match($item->status_pemesanan) {
                                                'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'diproses', 'proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'menunggu_pembayaran', 'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $badgeColor }}">
                                            {{ str_replace('_', ' ', $item->status_pemesanan) }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- KATEGORI 2: DATA PELANGGAN --}}
                @if($pelanggan->isNotEmpty())
                    <div class="p-2 bg-white">
                        <div class="px-2.5 py-1.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-900">
                                <span class="w-5 h-5 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                                    👤
                                </span>
                                <span>Data Pelanggan</span>
                                <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-1.5 py-0.2 rounded-full">({{ $pelangganTotal }})</span>
                            </div>
                            @if($pelangganTotal > count($pelanggan))
                                <a href="{{ route('admin.pelanggan.index', ['search' => $query]) }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-800 transition">
                                    Lihat Semua &rarr;
                                </a>
                            @endif
                        </div>

                        <div class="mt-1 space-y-1">
                            @foreach($pelanggan as $item)
                                @php
                                    $isSel = ($selectedIndex === $globalIdx);
                                    $itemUrl = route('admin.pelanggan.index', ['search' => $item->nama]);
                                    $globalIdx++;
                                @endphp
                                <a
                                    href="{{ $itemUrl }}"
                                    class="group flex items-center justify-between p-2.5 rounded-xl transition text-left {{ $isSel ? 'bg-emerald-50/80 border border-emerald-200' : 'hover:bg-gray-50' }}"
                                >
                                    <div class="min-w-0 flex-1 pr-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-extrabold text-gray-900 group-hover:text-emerald-700 transition">{{ $item->nama }}</span>
                                            @if($item->kategori)
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-gray-100 text-gray-600 uppercase">{{ $item->kategori }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-500 font-medium truncate mt-0.5">
                                            <span>WhatsApp: {{ $item->nomor_telepon ?: '-' }}</span>
                                            @if($item->kota) <span class="text-gray-400">•</span> {{ $item->kota }} @endif
                                            @if($item->email) <span class="text-gray-400">•</span> {{ $item->email }} @endif
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                            Pelanggan
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- KATEGORI 3: PERSEDIAAN & STOK PRODUK --}}
                @if($stokProduk->isNotEmpty())
                    <div class="p-2 bg-white">
                        <div class="px-2.5 py-1.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-teal-900">
                                <span class="w-5 h-5 rounded-md bg-teal-50 text-teal-600 flex items-center justify-center text-xs">
                                    👕
                                </span>
                                <span>Persediaan Produk & Stok</span>
                                <span class="text-[10px] text-teal-600 font-semibold bg-teal-50 px-1.5 py-0.2 rounded-full">({{ $stokTotal }})</span>
                            </div>
                            @if($stokTotal > count($stokProduk))
                                <a href="{{ route('admin.persediaan.index', ['tab' => 'persediaan', 'search' => $query]) }}" class="text-[11px] font-bold text-teal-600 hover:text-teal-800 transition">
                                    Lihat Semua &rarr;
                                </a>
                            @endif
                        </div>

                        <div class="mt-1 space-y-1">
                            @foreach($stokProduk as $item)
                                @php
                                    $isSel = ($selectedIndex === $globalIdx);
                                    $searchVal = $item->produk?->kode_produk ?? $item->nama_produk;
                                    $itemUrl = route('admin.persediaan.index', ['tab' => 'persediaan', 'search' => $searchVal]);
                                    $globalIdx++;
                                @endphp
                                <a
                                    href="{{ $itemUrl }}"
                                    class="group flex items-center justify-between p-2.5 rounded-xl transition text-left {{ $isSel ? 'bg-teal-50/80 border border-teal-200' : 'hover:bg-gray-50' }}"
                                >
                                    <div class="min-w-0 flex-1 pr-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-extrabold text-gray-900 group-hover:text-teal-700 transition">{{ $item->nama_produk }}</span>
                                            @if($item->produk?->kode_produk)
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-teal-50 text-teal-700 border border-teal-200">SKU: {{ $item->produk->kode_produk }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-500 font-medium truncate mt-0.5">
                                            Warna: <strong class="text-gray-700">{{ $item->warna }}</strong> <span class="text-gray-400">•</span>
                                            Ukuran: <strong class="text-gray-700">{{ $item->ukuran }}</strong> <span class="text-gray-400">•</span>
                                            Sisa Stok: <strong class="{{ $item->sisa_stok <= $item->batas_rop ? 'text-rose-600' : 'text-emerald-700' }}">{{ $item->sisa_stok }} Pcs</strong>
                                            <span class="text-gray-400">(Batas ROP: {{ $item->batas_rop }})</span>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        @if($item->sisa_stok <= 0)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">HABIS</span>
                                        @elseif($item->sisa_stok <= $item->batas_rop)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">MENIPIS</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">AMAN</span>
                                        @endif
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 group-hover:text-teal-600 group-hover:translate-x-0.5 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- KATEGORI 4: MONITORING PRODUKSI --}}
                @if($produksi->isNotEmpty())
                    <div class="p-2 bg-white">
                        <div class="px-2.5 py-1.5 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-amber-900">
                                <span class="w-5 h-5 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                                    ⚙️
                                </span>
                                <span>Monitoring Produksi</span>
                                <span class="text-[10px] text-amber-600 font-semibold bg-amber-50 px-1.5 py-0.2 rounded-full">({{ $produksiTotal }})</span>
                            </div>
                            @if($produksiTotal > count($produksi))
                                <a href="{{ route('admin.produksi.index', ['search' => $query]) }}" class="text-[11px] font-bold text-amber-600 hover:text-amber-800 transition">
                                    Lihat Semua &rarr;
                                </a>
                            @endif
                        </div>

                        <div class="mt-1 space-y-1">
                            @foreach($produksi as $item)
                                @php
                                    $isSel = ($selectedIndex === $globalIdx);
                                    $searchVal = $item->pemesanan?->nomor_pemesanan ?? $item->kode_produksi;
                                    $itemUrl = route('admin.produksi.index', ['search' => $searchVal]);
                                    $globalIdx++;
                                @endphp
                                <a
                                    href="{{ $itemUrl }}"
                                    class="group flex items-center justify-between p-2.5 rounded-xl transition text-left {{ $isSel ? 'bg-amber-50/80 border border-amber-200' : 'hover:bg-gray-50' }}"
                                >
                                    <div class="min-w-0 flex-1 pr-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-extrabold text-gray-900 group-hover:text-amber-700 transition">{{ $item->kode_produksi }}</span>
                                            @if($item->pemesanan?->nomor_pemesanan)
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-xs font-semibold text-gray-600">SPK: {{ $item->pemesanan->nomor_pemesanan }}</span>
                                            @endif
                                            @if($item->pemesanan?->pelanggan?->nama)
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-xs font-bold text-gray-700 truncate">{{ $item->pemesanan->pelanggan->nama }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-500 font-medium truncate mt-0.5">
                                            Tahap Saat Ini: <strong class="text-amber-700 capitalize">{{ $item->tahap_saat_ini ?: 'Antrean' }}</strong>
                                            <span class="text-gray-400">•</span> Progress: <strong class="text-gray-700">{{ $item->progress_persen }}%</strong>
                                            @if($item->pemesanan?->jenis_kain)
                                                <span class="text-gray-400">•</span> {{ $item->pemesanan->jenis_kain }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $item->status_produksi ?: 'PROSES' }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 group-hover:text-amber-600 group-hover:translate-x-0.5 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- EMPTY STATE: KETIKA TIDAK ADA DATA DITEMUKAN --}}
                @if($totalHasil === 0)
                    <div class="p-8 text-center bg-white">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                <line x1="8" y1="11" x2="14" y2="11"/>
                            </svg>
                        </div>
                        <h4 class="text-xs font-bold text-gray-900 mb-1">Data tidak ditemukan</h4>
                        <p class="text-[11px] text-gray-500 max-w-xs mx-auto leading-relaxed">
                            Tidak ada pesanan, pelanggan, produk/stok, atau proses produksi yang cocok dengan kata kunci <strong class="text-gray-800">"{{ $query }}"</strong>.
                        </p>
                        <div class="mt-3 flex items-center justify-center gap-1.5 text-[10px] text-gray-400 font-medium">
                            <span>Saran: Coba gunakan nomor SPK, nama pelanggan, warna kain, atau SKU.</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer Bantuan Navigasi Keyboard --}}
            <div class="px-4 py-2 bg-gray-50 flex items-center justify-between text-[10px] text-gray-400 font-medium border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 rounded bg-white border border-gray-200 text-gray-600 font-bold shadow-xs">↑</kbd>
                        <kbd class="px-1.5 py-0.5 rounded bg-white border border-gray-200 text-gray-600 font-bold shadow-xs">↓</kbd>
                        <span>navigasi</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 rounded bg-white border border-gray-200 text-gray-600 font-bold shadow-xs">↵</kbd>
                        <span>buka</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 rounded bg-white border border-gray-200 text-gray-600 font-bold shadow-xs">Esc</kbd>
                        <span>tutup</span>
                    </span>
                </div>
                <div>
                    <span class="text-teal-700 font-semibold">COTTON.id Live Search</span>
                </div>
            </div>

        </div>
    @endif
</div>
