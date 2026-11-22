<div class="space-y-6">

    {{-- 1. HEADER HALAMAN & ACTION BUTTONS SESUAI SCREENSHOT 5 --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 mb-1">
                <span>DATABASE WORKSHOP</span>
                <span>•</span>
                <span class="text-teal-700 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                    Tersinkronisasi 3 menit lalu
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug">
                Riwayat Pekerjaan Selesai (Arsip Workshop)
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1 max-w-2xl leading-normal">
                Daftar seluruh batch pengerjaan pakaian yang telah selesai diproduksi dan lolos uji kualitas QC.
            </p>
        </div>

        {{-- Action Buttons Kanan --}}
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-800 text-xs font-bold hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect width="12" height="8" x="6" y="14"/>
                </svg>
                <span>Cetak Rekap Riwayat</span>
            </button>

            <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span>Unduh Excel</span>
            </button>
        </div>
    </div>

    {{-- 2. TIGA KARTU METRIK PRODUKSI SESUAI SCREENSHOT 5 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Card 1: Volume Produksi --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-start justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">VOLUME PRODUKSI</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-3xl font-black text-slate-900">{{ $totalPcsSelesai }}</span>
                    <span class="text-xs font-bold text-slate-500">Potong</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Total Baju Selesai Minggu Ini</p>
                <div class="pt-1 flex items-center gap-1 text-[11px] font-bold text-teal-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="18 15 12 9 6 15"/>
                    </svg>
                    <span>+18% dari target kuota mingguan</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2a3 3 0 0 0-3 3c0 1.3.8 2.4 2 2.8V9L3.5 15.5A2 2 0 0 0 5 19h14a2 2 0 0 0 1.5-3.5L13 9V7.8A3 3 0 0 0 12 2Z"/>
                </svg>
            </div>
        </div>

        {{-- Card 2: Mutu Hasil Jahit --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-start justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">MUTU HASIL JAHIT</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-3xl font-black text-slate-900">100%</span>
                    <span class="text-xs font-bold text-teal-700">Rapi</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Tingkat Lolos QC</p>
                <div class="pt-1 flex items-center gap-1.5 text-[11px] font-bold text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>0 potong reject / retur produksi</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-700 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
        </div>

        {{-- Card 3: Efisiensi Workshop --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-start justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">EFISIENSI WORKSHOP</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-3xl font-black text-slate-900">2.4</span>
                    <span class="text-xs font-bold text-slate-500">Jam/batch</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Rata-rata Waktu Kerja</p>
                <div class="pt-1 flex items-center gap-1.5 text-[11px] font-bold">
                    <span class="px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 border border-teal-200/50">Tepat Waktu</span>
                    <span class="text-slate-500">Sesuai SLA SPK lantai pabrik</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0" style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 14 14"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- 3. FILTER & SEARCH BAR SESUAI SCREENSHOT 5 --}}
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
        <div class="sm:col-span-6 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Cari nomor SPK, jenis kain, nama operator..."
                   class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-hidden focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition text-slate-800 placeholder-slate-400 font-medium shadow-2xs">
        </div>

        <div class="sm:col-span-3">
            <select wire:model.live="filterTahap"
                    class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 bg-white focus:outline-hidden focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition cursor-pointer shadow-2xs">
                <option value="semua">Semua Tahapan</option>
                <option value="pemotongan_kain">Pemotongan Kain</option>
                <option value="penjahitan">Penjahitan</option>
                <option value="steam">Steam & Finishing</option>
                <option value="sablon">Sablon</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <select wire:model.live="filterPeriode"
                    class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 bg-white focus:outline-hidden focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition cursor-pointer shadow-2xs">
                <option value="7_hari">7 Hari Terakhir</option>
                <option value="30_hari">30 Hari Terakhir</option>
                <option value="bulan_ini">Bulan Ini</option>
            </select>
        </div>

        <div class="sm:col-span-1 flex justify-end">
            <button wire:click="refreshData"
                    class="w-full p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition cursor-pointer shadow-2xs"
                    title="Segarkan">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                    <path d="M16 21h5v-5"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- 4. TABEL ARSIP PEKERJAAN SESUAI SCREENSHOT 5 --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-black text-slate-900">Daftar Batch Terarsip</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">
                    4 Berkas
                </span>
            </div>
            <span class="text-xs text-slate-400 font-medium">Menampilkan 4 entri aktif</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-5 py-3.5">NOMOR SPK</th>
                        <th class="px-5 py-3.5">PRODUK & MATERIAL</th>
                        <th class="px-5 py-3.5">TAHAP PEKERJAAN</th>
                        <th class="px-5 py-3.5">WAKTU PENYELESAIAN</th>
                        <th class="px-5 py-3.5">PETUGAS BERTANGGUNG JAWAB</th>
                        <th class="px-5 py-3.5">STATUS</th>
                        <th class="px-5 py-3.5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- Row 1 Sesuai Screenshot 5: ORD-2026-000 --}}
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                <div>
                                    <span class="font-black text-slate-900 block text-xs">ORD-2026-000</span>
                                    <span class="text-[10px] text-slate-400 font-medium">PO Klien: PT Digital Mandiri</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 block text-xs">Kaos Raglan Combed 24s</span>
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 mt-0.5">
                                <span class="font-bold">40 pcs</span>
                                <span>• Warna Hitam & Putih</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                <span>Selesai QC & Packing</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Siap Pengiriman Ekspedisi</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-medium">
                            <span class="font-bold text-slate-900 block">24 Okt 2024</span>
                            <span class="text-[10px] text-slate-400">10:15 WIB</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-slate-900 text-white text-[10px] font-black flex items-center justify-center shrink-0">
                                    BS
                                </div>
                                <div class="text-left leading-tight">
                                    <span class="text-xs font-bold text-slate-900 block">Budi Santoso & Regu QC</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Staf Operator #04</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/60">
                                <span>✓</span> Lolos Uji
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('staff.detail-pekerjaan') }}" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg inline-block transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        </td>
                    </tr>

                    {{-- Row 2 Sesuai Screenshot 5: ORD-2025-998 --}}
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                <div>
                                    <span class="font-black text-slate-900 block text-xs">ORD-2025-998</span>
                                    <span class="text-[10px] text-slate-400 font-medium">PO Klien: Seragam Bank Jateng</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 block text-xs">Polo Shirt Lacoste Pique</span>
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 mt-0.5">
                                <span class="font-bold">60 pcs</span>
                                <span>• Warna Navy Bordir</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                <span>Penjahitan Selesai</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Obras & Kelim Rapi</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-medium">
                            <span class="font-bold text-slate-900 block">23 Okt 2024</span>
                            <span class="text-[10px] text-slate-400">17:30 WIB</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 text-[10px] font-black flex items-center justify-center shrink-0">
                                    RB
                                </div>
                                <div class="text-left leading-tight">
                                    <span class="text-xs font-bold text-slate-900 block">Regu Jahit B</span>
                                    <span class="text-[10px] text-slate-400 font-medium">4 Penjahit Operator</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/60">
                                <span>✓</span> Lolos Uji
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('staff.detail-pekerjaan') }}" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg inline-block transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        </td>
                    </tr>

                    {{-- Row 3 Sesuai Screenshot 5: ORD-2025-994 --}}
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                <div>
                                    <span class="font-black text-slate-900 block text-xs">ORD-2025-994</span>
                                    <span class="text-[10px] text-slate-400 font-medium">PO Klien: Komunitas Kopi Nusantara</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 block text-xs">Kaos Katun Bambu</span>
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 mt-0.5">
                                <span class="font-bold">25 pcs</span>
                                <span>• Warna Olive Green</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                <span>Sablon Selesai</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Discharge 3 Warna Kering</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-medium">
                            <span class="font-bold text-slate-900 block">23 Okt 2024</span>
                            <span class="text-[10px] text-slate-400">11:45 WIB</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 text-[10px] font-black flex items-center justify-center shrink-0">
                                    MA
                                </div>
                                <div class="text-left leading-tight">
                                    <span class="text-xs font-bold text-slate-900 block">Meja Sablon A</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Operator Cetak Presisi</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/60">
                                <span>✓</span> Lolos Uji
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('staff.detail-pekerjaan') }}" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg inline-block transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        </td>
                    </tr>

                    {{-- Row 4 Sesuai Screenshot 5: ORD-2025-990 --}}
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                <div>
                                    <span class="font-black text-slate-900 block text-xs">ORD-2025-990</span>
                                    <span class="text-[10px] text-slate-400 font-medium">PO Klien: Merchandise Musik Fest</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 block text-xs">Hoodie Baby Terry</span>
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 mt-0.5">
                                <span class="font-bold">50 pcs</span>
                                <span>• Warna Charcoal Grey</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                <span>Potong Pola Selesai</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Gelaran Kain Presisi S-XL</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-medium">
                            <span class="font-bold text-slate-900 block">22 Okt 2024</span>
                            <span class="text-[10px] text-slate-400">11:00 WIB</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-slate-900 text-white text-[10px] font-black flex items-center justify-center shrink-0">
                                    BS
                                </div>
                                <div class="text-left leading-tight">
                                    <span class="text-xs font-bold text-slate-900 block">Budi Santoso</span>
                                    <span class="text-[10px] text-slate-400 font-medium">Operator Meja Potong</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200/60">
                                <span>✓</span> Lolos Uji
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('staff.detail-pekerjaan') }}" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg inline-block transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination Bar Sesuai Screenshot 5 --}}
        <div class="p-4 sm:p-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <span class="text-slate-500 font-medium">
                Menampilkan 1 – 4 dari total <strong class="text-slate-900">175</strong> potong pakaian minggu ini
            </span>

            <div class="flex items-center gap-1.5">
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">
                    Sebelumnya
                </button>
                <button class="w-8 h-8 rounded-lg bg-black text-white font-bold text-xs">
                    1
                </button>
                <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
                    2
                </button>
                <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold transition">
                    Berikutnya
                </button>
            </div>
        </div>
    </div>







