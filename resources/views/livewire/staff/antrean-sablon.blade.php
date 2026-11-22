<div class="space-y-6">

    {{-- ALERT SUKSES --}}
    @if ($pesanSukses)
        <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200/80 flex items-center justify-between gap-3 text-teal-800 text-xs font-semibold shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="9 11 12 14 22 4"/>
                </svg>
                <span>{{ $pesanSukses }}</span>
            </div>
            <button wire:click="$set('pesanSukses', '')" class="text-teal-600 hover:text-teal-900 cursor-pointer p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    @endif

    {{-- 1. TOP BANNER SISTEM OPTIMASI & AMBANG BATAS (SESUAI GAMBAR 3) --}}
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-2 max-w-3xl">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                    SISTEM OPTIMASI MEJA CETAK
                </span>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-teal-700 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                    Kebijakan Zero-Waste Pasta Cat
                </span>
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug">
                Antrean Cetak Sablon & Kuota Desain
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium leading-normal">
                Untuk menghemat pasta cat dan tenaga cetak screen, proses sablon baru bisa dijalankan jika pesanan dengan gambar/desain yang sama sudah terkumpul <strong class="text-slate-900 font-bold">minimal 5 potong</strong>.
            </p>
        </div>

        {{-- Ambang Batas Produksi Card Kanan Sesuai Gambar 3 --}}
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 sm:p-5 flex items-center gap-4 shrink-0 shadow-2xs">
            <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center text-teal-700 shrink-0" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Ambang Batas Produksi</span>
                <div class="text-base font-black text-slate-900 leading-tight">5 Pcs / Screen Afdruk</div>
                <span class="text-[11px] text-slate-500 font-medium mt-0.5 block">Efisiensi operasional teruji</span>
            </div>
        </div>
    </div>

    {{-- 2. KLUSTER DESAIN AKTIF (2 KARTU BESAR SESUAI GAMBAR 3) --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                    <polyline points="2 17 12 22 22 17"/>
                    <polyline points="2 12 12 17 22 12"/>
                </svg>
                <h2 class="text-base font-black text-slate-900">Kluster Desain Aktif</h2>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                2 Kluster Berjalan
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- KLUSTER 1: DSN-YOUTH-26 (Belum Cukup Kuota) --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-xs space-y-4">
                {{-- Header Kluster --}}
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                        <span>KODE: <strong class="text-slate-900">DSN-YOUTH-26</strong></span>
                        <span>•</span>
                        <span>Plastisol High Density</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Belum Cukup Kuota (Kurang 2 Pcs)
                    </span>
                </div>

                <h3 class="text-base font-black text-slate-900">Desain: Logo Komunitas Youth 2026</h3>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                    {{-- Thumbnail Hoodie Graphic --}}
                    <div class="sm:col-span-5 bg-slate-900 rounded-xl p-3 text-center relative overflow-hidden flex flex-col items-center justify-center min-h-[140px]">
                        <div class="w-24 h-24 bg-indigo-950 rounded-xl border border-indigo-800/80 flex flex-col items-center justify-center p-2 relative shadow-md">
                            <span class="text-[9px] font-mono text-indigo-300">YOUTH 26</span>
                            <span class="text-[7px] text-zinc-400 font-bold tracking-widest mt-0.5">COMMUNITY</span>
                        </div>
                        <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-bold bg-slate-800 text-slate-300">
                            Hoodie Fleece
                        </span>
                    </div>

                    {{-- Detail Akumulasi & Progres --}}
                    <div class="sm:col-span-7 space-y-3">
                        <div class="flex items-baseline justify-between">
                            <span class="text-xs font-bold text-slate-500">Akumulasi Pesanan</span>
                            <div class="text-right">
                                <span class="text-2xl font-black text-slate-900">3</span>
                                <span class="text-xs font-bold text-slate-500">/ 5 Pcs Target</span>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1">
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: 60%;"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span class="text-slate-600">Progres: 60%</span>
                                <span class="text-amber-700">Kurang 2 potong lagi</span>
                            </div>
                        </div>

                        {{-- SPK Tergabung --}}
                        <div class="pt-2 border-t border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">SPK Tergabung Saat Ini:</span>
                            <div class="flex items-center gap-1.5 text-xs text-slate-700 font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                                <span>ORD-2026-004 (Hoodie Fleece 3 pcs)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Area --}}
                <div class="pt-3 border-t border-slate-100 space-y-2.5">
                    <button disabled
                            class="w-full py-2.5 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold cursor-not-allowed flex items-center justify-center gap-2">
                        <span>⊘</span>
                        <span>Mulai Proses Sablon</span>
                    </button>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px]">
                        <div class="flex items-center gap-1.5 text-slate-500 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            <span>Menunggu tambahan pesanan dengan motif serupa</span>
                        </div>
                        <button wire:click="tambahUjiCoba('DSN-YOUTH-26')"
                                class="text-slate-800 font-bold hover:text-teal-700 cursor-pointer">
                            + Tambah 2 Pcs untuk Uji Coba
                        </button>
                    </div>
                </div>
            </div>

            {{-- KLUSTER 2: DSN-TYPO-COTTON (Kuota Terpenuhi - Siap Sablon!) --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-xs space-y-4">
                {{-- Header Kluster --}}
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                        <span>KODE: <strong class="text-slate-900">DSN-TYPO-COTTON</strong></span>
                        <span>•</span>
                        <span>Rubber Soft Matte</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                        Kuota Terpenuhi - Siap Sablon!
                    </span>
                </div>

                <h3 class="text-base font-black text-slate-900">Desain: Tulisan Typography Minimalis COTTON</h3>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                    {{-- Thumbnail Combed Graphic --}}
                    <div class="sm:col-span-5 bg-slate-100 rounded-xl p-3 text-center relative overflow-hidden flex flex-col items-center justify-center min-h-[140px] border border-slate-200/60">
                        <div class="w-24 h-24 bg-white rounded-xl border border-slate-300 flex flex-col items-center justify-center p-2 relative shadow-xs">
                            <span class="text-[10px] font-black text-slate-900 tracking-wider">COTTON.id</span>
                            <span class="text-[7px] text-slate-400 font-bold tracking-widest mt-0.5">EST. 2024</span>
                        </div>
                        <span class="inline-block mt-2 px-2 py-0.5 rounded text-[9px] font-bold bg-slate-200 text-slate-700">
                            Combed 24s
                        </span>
                    </div>

                    {{-- Detail Akumulasi & Progres --}}
                    <div class="sm:col-span-7 space-y-3">
                        <div class="flex items-baseline justify-between">
                            <span class="text-xs font-bold text-slate-500">Akumulasi Pesanan</span>
                            <div class="text-right">
                                <span class="text-2xl font-black text-teal-700">8</span>
                                <span class="text-xs font-bold text-teal-600 bg-teal-50 px-1.5 py-0.5 rounded-md ml-1">+3 Surplus</span>
                            </div>
                        </div>

                        {{-- Progress Bar (160% Full) --}}
                        <div class="space-y-1">
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-teal-600 h-full rounded-full" style="width: 100%;"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span class="text-teal-800">Progres: 160% (Penuh)</span>
                                <span class="text-teal-700">Langsung Cetak</span>
                            </div>
                        </div>

                        {{-- SPK Tergabung --}}
                        <div class="pt-2 border-t border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">SPK Tergabung Saat Ini:</span>
                            <div class="space-y-0.5 text-xs text-slate-700 font-semibold">
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    </svg>
                                    <span>ORD-2026-002 (3 pcs Kaos Putih)</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    </svg>
                                    <span>ORD-2026-008 (5 pcs Kaos BW)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Button Sesuai Gambar 3 --}}
                <div class="pt-3 border-t border-slate-100 space-y-2.5">
                    <button wire:click="mulaiSablonKluster('DSN-TYPO-COTTON')"
                            class="w-full py-2.5 rounded-xl bg-teal-700 text-white text-xs font-black hover:bg-teal-800 transition cursor-pointer shadow-xs flex items-center justify-center gap-2">
                        <span>▷</span>
                        <span>Mulai Proses Sablon Sekarang (8 Pcs)</span>
                    </button>

                    <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium">
                        <div class="flex items-center gap-1 text-teal-800 font-semibold">
                            <span>✓</span>
                            <span>Screen afdruk #04 telah terpasang di Meja Cetak B</span>
                        </div>
                        <span>Estimasi Cetak: 45 Menit</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- 3. RINCIAN ANTREAN PESANAN SABLON (TABEL SESUAI GAMBAR 3) --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-black text-slate-900">Rincian Antrean Pesanan Sablon</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Daftar Surat Perintah Kerja (SPK) yang terdaftar dalam antrean afdruk aktif.</p>
            </div>

            <div class="flex items-center gap-2.5">
                <button wire:click="filterKlusterSet('semua')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition cursor-pointer shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    <span>Semua Kluster</span>
                </button>

                <button wire:click="perbaruiStatus"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold border border-slate-200 bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                    Perbarui Status
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="px-5 py-3.5">NO SPK</th>
                        <th class="px-5 py-3.5">NAMA BAJU & MODEL</th>
                        <th class="px-5 py-3.5">KODE GAMBAR</th>
                        <th class="px-5 py-3.5">JENIS SABLON</th>
                        <th class="px-5 py-3.5">JUMLAH</th>
                        <th class="px-5 py-3.5">STATUS KUOTA</th>
                        <th class="px-5 py-3.5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rincianAntrean as $row)
                        @php
                            $isSiap = $row->status_sablon_antrean === 'siap_cetak';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            {{-- NO SPK --}}
                            <td class="px-5 py-4">
                                <span class="font-extrabold text-slate-900 block text-xs">{{ $row->nomor_pemesanan }}</span>
                                <span class="text-[11px] text-slate-500 font-medium block mt-0.5">
                                    Pelanggan: {{ $row->pelanggan?->nama ?? 'PT Sinergi Kreatif' }}
                                </span>
                            </td>

                            {{-- NAMA BAJU & MODEL --}}
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900 block text-xs">{{ $row->jenis_kain }}</span>
                                <span class="text-[11px] text-slate-500 font-medium block mt-0.5">
                                    {{ $row->ukuran_baju ?? 'Ukuran L' }}
                                </span>
                            </td>

                            {{-- KODE GAMBAR --}}
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-black text-slate-700 bg-slate-100 px-2 py-1 rounded">
                                    {{ $row->kode_gambar ?? 'DSN-TYPO-COTTON' }}
                                </span>
                            </td>

                            {{-- JENIS SABLON --}}
                            <td class="px-5 py-4 text-slate-700 font-medium">
                                {{ $row->jenis_sablon ?? 'Rubber Soft (Hitam Pekat)' }}
                            </td>

                            {{-- JUMLAH --}}
                            <td class="px-5 py-4">
                                <span class="font-black text-slate-900 text-xs">{{ $row->jumlah }} Pcs</span>
                            </td>

                            {{-- STATUS KUOTA --}}
                            <td class="px-5 py-4">
                                @if ($isSiap)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                        Siap Sablon (Gabung)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Kurang 2 Pcs
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $row->id]) }}"
                                   class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-800 text-xs font-bold hover:bg-slate-200 transition cursor-pointer">
                                    Cek SPK
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 text-xs font-medium">
                                Tidak ada data antrean pesanan sablon.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 4. CATATAN SOP MEJA SABLON (SESUAI GAMBAR 3) --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900">Catatan SOP Meja Sablon COTTON.id</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Panduan operasional operator cetak manual dan semi-otomatis</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                <div class="flex items-center gap-2 font-bold text-xs text-slate-900">
                    <span class="w-5 h-5 rounded-full bg-teal-600 text-white text-[10px] flex items-center justify-center">1</span>
                    <span>Peracikan Pasta Cat Sablon</span>
                </div>
                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                    Campurkan pasta rubber atau plastisol secukupnya berdasarkan jumlah akumulasi pcs (takaran baku: 15–20 gram cat per kaos). Jangan membuka kemasan cat baru sebelum kuota minimal 5 potong terpenuhi sepenuhnya di sistem.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                <div class="flex items-center gap-2 font-bold text-xs text-slate-900">
                    <span class="w-5 h-5 rounded-full bg-teal-600 text-white text-[10px] flex items-center justify-center">2</span>
                    <span>Pencucian & Pemeliharaan Screen Afdruk</span>
                </div>
                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                    Begitu nomor kluster selesai digesut, screen harus langsung dicuci bersih menggunakan cairan pembersih khusus (reducer/screen stripper) dalam waktu maksimal 20 menit agar pori kain monofilamen tidak tersumbat tinta kering.
                </p>
            </div>
        </div>
    </div>

</div>
