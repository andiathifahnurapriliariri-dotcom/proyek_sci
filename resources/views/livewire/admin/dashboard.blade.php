<div class="space-y-6" x-data="{ modalOpen: @entangle('showModalPesanan'), modalAktivitasOpen: @entangle('showModalAktivitas') }">

    {{-- ALERT NOTIFIKASI SUKSES (JIKA SPK BARU DIBUAT) --}}
    @if($pesanSukses)
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-emerald-900">Pesanan Berhasil Disimpan!</h4>
                    <p class="text-xs text-emerald-700 font-medium mt-0.5">{{ $pesanSukses }}</p>
                </div>
            </div>
            <button type="button" wire:click="$set('pesanSukses', '')" class="text-emerald-500 hover:text-emerald-800 text-sm font-bold p-1">
                ✕
            </button>
        </div>
    @endif

    {{-- 1. HEADER BERSIH & KALENDER BULANAN REAL-TIME INTERAKTIF --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Sapaan Admin Bersih (Tanpa Badge Lini Produksi) --}}
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">
                Selamat Datang, {{ $adminUser->username === 'admin' ? 'Michel' : $adminUser->name }}
            </h1>
            <p class="text-sm text-gray-500 font-normal mt-1">
                Ringkasan pesanan masuk, proses pengerjaan baju di workshop, dan stok baju hari ini.
            </p>
        </div>

        {{-- Kalender Bulanan Real-Time Interaktif (Tanpa Tombol Unduh Laporan) --}}
        <div class="flex items-center gap-2 self-start md:self-auto">
            <div class="flex items-center bg-white border border-gray-200/80 rounded-2xl p-1 shadow-sm hover:border-gray-300 transition">
                {{-- Tombol Bulan Sebelumnya --}}
                <button
                    type="button"
                    wire:click="prevBulan"
                    class="p-2 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition active:scale-90"
                    title="Bulan Sebelumnya"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                {{-- Input Bulan & Tahun Real-Time --}}
                <div class="relative flex items-center px-3 py-1 cursor-pointer group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600 mr-2 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                    <div class="flex flex-col text-left">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider leading-none">Periode Data</span>
                        <span class="text-xs font-black text-gray-900 leading-tight mt-0.5">{{ $bulanTerpilihNama }}</span>
                    </div>

                    {{-- Native Month Input Overlay untuk Memilih Bulan Langsung --}}
                    <input
                        type="month"
                        wire:model.live="bulanTahun"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                        title="Klik untuk memilih bulan & tahun"
                    />
                </div>

                {{-- Tombol Bulan Berikutnya --}}
                <button
                    type="button"
                    wire:click="nextBulan"
                    class="p-2 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition active:scale-90"
                    title="Bulan Berikutnya"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 2. ENAM KARTU STATISTIK (SUMMARY CARDS DENGAN KONEKSI DATABASE & BULAN TERPILIH) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5">
        {{-- Card 1: Pesanan Aktif --}}
        <div class="bg-white rounded-2xl border border-gray-200/70 p-4 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Pesanan Aktif</span>
                <div class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $statistik['pesanan_aktif'] }}</div>
            <p class="text-[11px] text-gray-400 mt-1 font-medium">Total pesanan berjalan</p>
        </div>

        {{-- Card 2: Siap Dikerjakan --}}
        <div class="bg-white rounded-2xl border border-gray-200/70 p-4 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Siap Dikerjakan</span>
                <div class="w-6 h-6 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $statistik['siap_dikerjakan'] }}</div>
            <p class="text-[11px] text-gray-400 mt-1 font-medium">Antrean kain siap</p>
        </div>

        {{-- Card 3: Sedang Dikerjakan --}}
        <div class="bg-white rounded-2xl border border-gray-200/70 p-4 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Sedang Dikerjakan</span>
                <div class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $statistik['sedang_dikerjakan'] }}</div>
            <p class="text-[11px] text-gray-400 mt-1 font-medium">Potong, jahit, steam, sablon</p>
        </div>

        {{-- Card 4: Siap Diambil / Kirim --}}
        <div class="bg-white rounded-2xl border border-gray-200/70 p-4 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Siap Diambil / Kirim</span>
                <div class="w-6 h-6 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                        <path d="M15 18H9"/>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                        <circle cx="17" cy="18" r="2"/>
                        <circle cx="7" cy="18" r="2"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $statistik['siap_diambil'] }}</div>
            <p class="text-[11px] text-teal-600 font-semibold mt-1">Sudah selesai dipak</p>
        </div>

        {{-- Card 5: Penjualan Bulan Ini --}}
        <div class="bg-white rounded-2xl border border-gray-200/70 p-4 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-semibold text-gray-600">Penjualan Bulan Ini</span>
                <div class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" x2="12" y1="2" y2="22"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
            </div>
            <div class="text-xl font-black text-gray-900 tracking-tight">{{ $statistik['penjualan_format'] }}</div>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">
                Data periode {{ $bulanTerpilihNama }}
            </p>
        </div>

        {{-- Card 6: Stok Baju Menipis (ROP) --}}
        <div class="bg-white rounded-2xl border border-rose-200 p-4 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-xs font-bold text-rose-600">Stok Baju Menipis</span>
                <div class="w-6 h-6 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <line x1="12" x2="12" y1="9" y2="13"/>
                        <line x1="12" x2="12.01" y1="17" y2="17"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-rose-600 tracking-tight">{{ $statistik['kain_menipis'] }} Jenis</div>
            <p class="text-[11px] text-rose-500 font-semibold mt-1">Perlu segera dibeli</p>
        </div>
    </div>

    {{-- 3. ROW GRAFIK BULANAN (CHART.JS TERHUBUNG REAL-TIME) & TAHAP PENGERJAAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- Kiri (Col 8): Target vs Hasil Pengerjaan Baju Bulanan --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200/70 p-5 shadow-sm flex flex-col justify-between">
            <div>
                {{-- Header Grafik --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Target vs Hasil Pengerjaan Baju Bulanan</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Perbandingan jumlah target baju jadi dan hasil pengerjaan nyata (6 bulan terakhir hingga {{ $bulanTerpilihNama }})</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <div class="flex items-center gap-1.5 text-gray-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            <span>Target Pabrik</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-900">
                            <span class="w-2.5 h-2.5 rounded-full bg-teal-800"></span>
                            <span>Hasil Jadi</span>
                        </div>
                    </div>
                </div>

                {{-- Area Canvas Chart.js --}}
                <div class="relative w-full h-72 mt-2" wire:ignore>
                    <canvas id="targetHasilChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Kanan (Col 4): Tahap Pengerjaan Baju Saat Ini (Donut Chart) --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-gray-200/70 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Tahap Pengerjaan Baju Saat Ini</h2>
                <p class="text-xs text-gray-400 mt-0.5">Sebaran {{ $tahapProduksi['total'] }} pesanan yang sedang diproses</p>

                {{-- Donut Chart Container --}}
                <div class="relative w-48 h-48 mx-auto my-4 flex items-center justify-center" wire:ignore>
                    <canvas id="tahapDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black text-gray-900 leading-none">{{ $tahapProduksi['total'] }}</span>
                        <span class="text-[10px] text-gray-400 font-semibold mt-1">Pesanan Aktif</span>
                    </div>
                </div>

                {{-- List Keterangan Persentase --}}
                <div class="space-y-2.5 px-2">
                    @foreach($tahapProduksi['items'] as $item)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $item['warna'] }};"></span>
                                <span class="text-gray-700 font-medium">{{ $item['nama'] }}</span>
                            </div>
                            <span class="font-bold text-gray-900">{{ $item['persen'] }}% <span class="text-gray-400 font-normal">({{ $item['jumlah'] }})</span></span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Lihat Papan Alur Kerja --}}
            <button type="button" class="mt-5 w-full flex items-center justify-center gap-1.5 py-2.5 px-4 rounded-xl bg-gray-50 border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                <span>Lihat Papan Alur Kerja</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </div>
    </div>

    {{-- 4. ROW PERINGATAN ROP & PESANAN TENGGAT TERDEKAT --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- Kiri (Col 6): Peringatan Reorder Point (ROP) --}}
        <div class="lg:col-span-6 bg-white rounded-2xl border border-gray-200/70 p-5 shadow-sm flex flex-col justify-between">
            <div>
                {{-- Header Card --}}
                <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                <line x1="12" x2="12" y1="9" y2="13"/>
                                <line x1="12" x2="12.01" y1="17" y2="17"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Peringatan Reorder Point (ROP)</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Stok Baju di bawah batas aman perlu stok kembali</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                        {{ count($peringatanRop) }} Kritis
                    </span>
                </div>

                {{-- List Item ROP --}}
                <div class="divide-y divide-gray-100 mt-2">
                    @foreach($peringatanRop as $item)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 font-extrabold text-xs text-gray-700 flex items-center justify-center shrink-0">
                                    {{ $item->ukuran ?? 'XL' }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">{{ $item->nama_produk }}</h4>
                                    <p class="text-[11px] text-gray-400 mt-0.5">
                                        Sisa stok: <span class="font-bold text-rose-600">{{ $item->sisa_stok }} {{ $item->satuan ?? 'pcs' }}</span>
                                        (Batas aman: {{ $item->batas_rop }} {{ $item->satuan ?? 'pcs' }})
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="bukaModalPesanan" class="flex items-center gap-1 bg-black text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-800 transition active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Buat SPK</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer Card --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-400 text-[11px]">Perhitungan otomatis berdasarkan lead time bahan 3 hari</span>
                <a href="{{ route('admin.persediaan.index') }}" class="font-bold text-gray-900 hover:text-black flex items-center gap-1">
                    <span>Lihat Stok Baju</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kanan (Col 6): Pesanan dengan Batas Waktu Terdekat --}}
        <div class="lg:col-span-6 bg-white rounded-2xl border border-gray-200/70 p-5 shadow-sm flex flex-col justify-between">
            <div>
                {{-- Header Card --}}
                <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Pesanan dengan Batas Waktu Terdekat</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Perlu dipantau agar tepat waktu saat diserahkan ke pelanggan</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-rose-600">Mendesak</span>
                </div>

                {{-- Detail Box Pesanan Terdekat --}}
                @if($pesananTerdekat)
                    <div class="bg-gray-50/70 rounded-xl p-4 mt-3 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-black text-xs text-gray-900">{{ $pesananTerdekat->nomor_pemesanan }}</span>
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold">
                                    {{ $pesananTerdekat->prioritas }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-rose-600">{{ $pesananTerdekat->sisa_hari }}</span>
                                <p class="text-[10px] text-gray-400">Tenggat: {{ $pesananTerdekat->tgl_tenggat }}</p>
                            </div>
                        </div>

                        <div class="mt-2.5">
                            <h4 class="text-sm font-extrabold text-gray-900">{{ $pesananTerdekat->pelanggan_nama }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $pesananTerdekat->detail_produk }}</p>
                        </div>

                        {{-- Progress Bar Pengerjaan --}}
                        <div class="mt-3">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600 font-semibold text-[11px]">Tahap pengerjaan: <strong class="text-gray-900">{{ $pesananTerdekat->tahap }}</strong></span>
                                <span class="font-extrabold text-gray-900 text-xs">{{ $pesananTerdekat->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-teal-600 h-2 rounded-full transition-all duration-500" style="width: {{ $pesananTerdekat->progress }}%;"></div>
                            </div>
                        </div>

                        {{-- Penanggung Jawab & Aksi --}}
                        <div class="mt-3.5 pt-3 border-t border-gray-200/60 flex items-center justify-between">
                            <p class="text-xs text-gray-500 font-medium">
                                Penanggung Jawab: <span class="font-bold text-gray-800">{{ $pesananTerdekat->penanggung_jawab ?? 'Kang Deden' }}</span>
                            </p>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="bukaModalPesanan" class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition active:scale-95">
                                    Lihat SPK
                                </button>
                                <button type="button" class="px-3 py-1.5 rounded-lg bg-black text-white text-xs font-semibold hover:bg-gray-800 transition active:scale-95">
                                    Prioritaskan
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer Card --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-400 text-[11px]">Semua pesanan minggu ini masih sesuai target waktu</span>
                <a href="{{ route('admin.pesanan.index') }}" class="font-bold text-gray-900 hover:text-black flex items-center gap-1">
                    <span>Jadwal Pengiriman Lengkap</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- 5. ROW TABEL DAFTAR PESANAN TERBARU & CATATAN AKTIVITAS --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Kiri (Col 8): Tabel Daftar Pesanan Terbaru --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200/70 p-6 shadow-sm flex flex-col justify-between">
            <div>
                {{-- Header Tabel & Filter Tabs dengan Spasi yang Lega --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Daftar Pesanan Terbaru</h3>
                        <p class="text-xs text-gray-400 mt-1 font-normal">Status dan perkembangan pesanan yang sedang diproses</p>
                    </div>

                    {{-- 4 Tombol Filter Tab dengan Animasi Interaktif & Badge Counter --}}
                    <div class="inline-flex items-center gap-1.5 p-1.5 rounded-2xl bg-gray-100/90 border border-gray-200/70 self-start md:self-auto shadow-inner">
                        {{-- Tab 1: Semua --}}
                        <button
                            type="button"
                            wire:click="setTab('semua')"
                            class="relative flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 ease-out active:scale-90 cursor-pointer select-none {{ $filterTab === 'semua' ? 'bg-black text-white shadow-md font-bold scale-[1.02]' : 'text-gray-600 hover:text-gray-900 hover:bg-white/60' }}"
                        >
                            <span>Semua</span>
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $filterTab === 'semua' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $tabCounts['semua'] ?? $daftarPesanan->total() }}
                            </span>
                        </button>

                        {{-- Tab 2: Sedang Dibuat --}}
                        <button
                            type="button"
                            wire:click="setTab('sedang_dibuat')"
                            class="relative flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 ease-out active:scale-90 cursor-pointer select-none {{ $filterTab === 'sedang_dibuat' ? 'bg-black text-white shadow-md font-bold scale-[1.02]' : 'text-gray-600 hover:text-gray-900 hover:bg-white/60' }}"
                        >
                            <span>Sedang Dibuat</span>
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $filterTab === 'sedang_dibuat' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $tabCounts['sedang_dibuat'] ?? 0 }}
                            </span>
                        </button>

                        {{-- Tab 3: Antrean --}}
                        <button
                            type="button"
                            wire:click="setTab('antrean')"
                            class="relative flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 ease-out active:scale-90 cursor-pointer select-none {{ $filterTab === 'antrean' ? 'bg-black text-white shadow-md font-bold scale-[1.02]' : 'text-gray-600 hover:text-gray-900 hover:bg-white/60' }}"
                        >
                            <span>Antrean</span>
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $filterTab === 'antrean' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $tabCounts['antrean'] ?? 0 }}
                            </span>
                        </button>

                        {{-- Tab 4: Siap Diambil --}}
                        <button
                            type="button"
                            wire:click="setTab('siap_diambil')"
                            class="relative flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 ease-out active:scale-90 cursor-pointer select-none {{ $filterTab === 'siap_diambil' ? 'bg-black text-white shadow-md font-bold scale-[1.02]' : 'text-gray-600 hover:text-gray-900 hover:bg-white/60' }}"
                        >
                            <span>Siap Diambil</span>
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $filterTab === 'siap_diambil' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $tabCounts['siap_diambil'] ?? 0 }}
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Tabel Pesanan dengan Spasi Lebih Luas & Efek Loading Livewire --}}
                <div class="overflow-x-auto mt-4" wire:loading.class="opacity-50 transition-opacity duration-200">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-400 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3.5 pr-4 pl-1">No. Pesanan</th>
                                <th class="py-3.5 px-4">Nama Pelanggan</th>
                                <th class="py-3.5 px-4">Pesanan & Jumlah</th>
                                <th class="py-3.5 px-4">Batas Waktu (Tenggat)</th>
                                <th class="py-3.5 px-4">Tahap Pengerjaan</th>
                                <th class="py-3.5 px-4">Pembayaran</th>
                                <th class="py-3.5 pl-4 pr-1 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($daftarPesanan as $pesanan)
                                @php
                                    $tenggat = \Carbon\Carbon::parse($pesanan->tanggal_selesai_estimasi)->startOfDay();
                                    $sekarang = now()->startOfDay();
                                    $sisaHari = (int) $sekarang->diffInDays($tenggat, false);
                                    $sisaText = $sisaHari > 0 ? $sisaHari . ' hari lagi' : ($sisaHari === 0 ? 'Hari ini' : 'Lewat ' . abs($sisaHari) . ' hari');
                                    $tahap = $pesanan->produksi ? $pesanan->produksi->tahap_saat_ini : 'antrean';
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                    {{-- No. Pesanan --}}
                                    <td class="py-4 pr-4 pl-1 font-extrabold text-gray-900 whitespace-nowrap">
                                        {{ $pesanan->nomor_pemesanan }}
                                    </td>

                                    {{-- Nama Pelanggan --}}
                                    <td class="py-4 px-4">
                                        <p class="font-bold text-gray-900 leading-snug">{{ $pesanan->pelanggan->nama ?? 'Pelanggan Umum' }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ Str::limit($pesanan->pelanggan->alamat ?? 'Bandung', 24) }}</p>
                                    </td>

                                    {{-- Pesanan & Jumlah --}}
                                    <td class="py-4 px-4">
                                        <p class="font-bold text-gray-900">{{ $pesanan->jumlah }} pcs {{ $pesanan->ukuran_baju }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5 leading-tight">
                                            {{ Str::limit($pesanan->produk->nama_produk ?? $pesanan->jenis_kain, 26) }}
                                        </p>
                                    </td>

                                    {{-- Batas Waktu (Tenggat) - Angka Bulat Rapi --}}
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <p class="font-bold {{ $sisaHari <= 2 ? 'text-rose-600' : 'text-gray-900' }}">
                                            {{ $tenggat->format('d M Y') }}
                                        </p>
                                        <p class="text-[11px] font-semibold mt-0.5 {{ $sisaHari <= 2 ? 'text-rose-500' : 'text-gray-400' }}">
                                            {{ $sisaText }}
                                        </p>
                                    </td>

                                    {{-- Tahap Pengerjaan --}}
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($tahap === 'penjahitan')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Sedang Dijahit
                                            </span>
                                        @elseif($tahap === 'pemotongan_kain')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Siap Dipotong
                                            </span>
                                        @elseif($tahap === 'sablon')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                Sedang Sablon
                                            </span>
                                        @elseif($tahap === 'steam' || $tahap === 'selesai')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                                Steam & Packing
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700 border border-gray-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                Antrean
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Pembayaran --}}
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($pesanan->status_pembayaran === 'lunas')
                                            <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-extrabold border border-emerald-200/60">
                                                Lunas
                                            </span>
                                        @elseif($pesanan->status_pembayaran === 'dp')
                                            <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-[11px] font-bold border border-gray-200/60">
                                                DP 50%
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-lg bg-amber-50 text-amber-700 text-[11px] font-bold border border-amber-200/60">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Tindakan --}}
                                    <td class="py-4 pl-4 pr-1 text-right whitespace-nowrap">
                                        <button type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-50 border border-gray-200 text-gray-700 hover:bg-black hover:text-white hover:border-black transition-all duration-200 active:scale-95 shadow-2xs">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-400 text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        Tidak ada data pesanan pada kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Pagination --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>
                    Menampilkan <strong class="text-gray-900">{{ $daftarPesanan->count() }}</strong> dari <strong class="text-gray-900">{{ $daftarPesanan->total() }}</strong> pesanan
                </span>
                <div>
                    {{ $daftarPesanan->links('pagination::simple-tailwind') }}
                </div>
            </div>
        </div>

        {{-- Kanan (Col 4): Catatan Aktivitas Terbaru Real-Time --}}
        <div wire:poll.5s class="lg:col-span-4 bg-white rounded-2xl border border-gray-200/70 p-5 shadow-sm flex flex-col justify-between">
            <div>
                {{-- Header Aktivitas dengan Status LIVE Real-Time --}}
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Catatan Aktivitas Terbaru</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Kabar terbaru dari tim di lapangan</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200/70" title="Pencatatan Real-Time Aktif (Diperbarui otomatis tiap 5 detik)">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-[10px] font-black text-emerald-700 tracking-wider">LIVE</span>
                        </span>
                        <button
                            type="button"
                            wire:click="$refresh"
                            title="Segarkan Aktivitas Sekarang"
                            class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition active:scale-90"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                                <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                                <path d="M16 21h5v-5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- List Timeline Aktivitas Real-Time --}}
                <div class="space-y-4 mt-4">
                    @forelse($catatanAktivitas as $act)
                        <div class="flex items-start gap-3 group">
                            {{-- Ikon --}}
                            <div class="w-7 h-7 rounded-lg mt-0.5 shrink-0 flex items-center justify-center 
                                {{ $act['warna'] === 'emerald' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($act['warna'] === 'blue' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-rose-50 text-rose-600 border border-rose-100') }}">
                                @if($act['tipe'] === 'pembayaran')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="20" height="14" x="2" y="5" rx="2"/>
                                        <line x1="2" x2="22" y1="10" y2="10"/>
                                    </svg>
                                @elseif($act['tipe'] === 'produksi')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m17 2 4 4-4 4"/>
                                        <path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                                    </svg>
                                @elseif($act['tipe'] === 'pesanan')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                        <line x1="12" x2="12" y1="9" y2="13"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Deskripsi Aktivitas --}}
                            <div class="flex-1 text-xs">
                                <p class="text-gray-800 leading-snug">
                                    <strong class="font-bold text-gray-900">{{ $act['user'] }}</strong> {{ $act['aksi'] }}
                                    @if(!empty($act['ref']))
                                        <strong class="text-teal-700 font-bold">{{ $act['ref'] }}</strong>
                                    @endif
                                </p>
                                <span class="text-[10px] text-gray-400 mt-1 inline-block">{{ $act['waktu'] }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-gray-400">
                            Belum ada aktivitas yang tercatat.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Tombol Lihat Semua Catatan --}}
            <button
                type="button"
                wire:click="bukaModalAktivitas"
                class="mt-6 w-full flex items-center justify-center gap-1.5 py-2.5 px-4 rounded-xl border border-gray-200 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 active:scale-95 transition-all duration-150 cursor-pointer shadow-2xs"
            >
                <span>Lihat Semua Catatan</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                    <path d="M16 21h5v-5"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- 6. MODAL POP-UP FORM INPUT PESANAN (SPK) BARU --}}
    @include('livewire.admin.partials.modal-form-spk')

    {{-- 7. MODAL RIWAYAT SEMUA LOG AKTIVITAS REAL-TIME --}}
    <div
        x-show="modalAktivitasOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-aktivitas-title"
        role="dialog"
        aria-modal="true"
    >
        {{-- Backdrop Blur Overlay --}}
        <div
            x-show="modalAktivitasOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            x-on:click="$wire.tutupModalAktivitas()"
        ></div>

        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div
                x-show="modalAktivitasOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100"
                x-on:click.stop
            >
                {{-- Header Modal --}}
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-slate-900 via-gray-900 to-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/20 border border-teal-500/30 flex items-center justify-center text-teal-300 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-black tracking-tight text-white leading-tight" id="modal-aktivitas-title">
                                    Catatan Aktivitas Operasional
                                </h3>
                                <span class="flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-[10px] font-bold text-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    LIVE REAL-TIME
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-300 font-medium">Riwayat kegiatan tim lapangan, pembayaran, SPK & stok konveksi</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        x-on:click="$wire.tutupModalAktivitas()"
                        class="text-slate-400 hover:text-white rounded-lg p-1.5 hover:bg-white/10 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body Modal: Timeline List --}}
                <div class="p-6 max-h-[65vh] overflow-y-auto divide-y divide-gray-100">
                    @forelse($semuaAktivitas as $act)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-start gap-3.5">
                            {{-- Ikon Bulat --}}
                            <div class="w-8 h-8 rounded-xl mt-0.5 shrink-0 flex items-center justify-center font-bold text-xs
                                {{ $act->warna === 'emerald' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200/60' : ($act->warna === 'blue' ? 'bg-blue-50 text-blue-600 border border-blue-200/60' : 'bg-rose-50 text-rose-600 border border-rose-200/60') }}">
                                @if($act->tipe === 'pembayaran')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="20" height="14" x="2" y="5" rx="2"/>
                                        <line x1="2" x2="22" y1="10" y2="10"/>
                                    </svg>
                                @elseif($act->tipe === 'produksi')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m17 2 4 4-4 4"/>
                                        <path d="M3 11v-1a4 4 0 0 1 4-4h14"/>
                                    </svg>
                                @elseif($act->tipe === 'pesanan')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                        <line x1="12" x2="12" y1="9" y2="13"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Isi Log --}}
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-gray-900 leading-snug">
                                        <span class="font-bold text-gray-950">{{ $act->nama_user }}</span>
                                        <span class="text-gray-600">{{ $act->aksi }}</span>
                                        @if(!empty($act->referensi))
                                            <span class="font-bold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded border border-teal-200/50">{{ $act->referensi }}</span>
                                        @endif
                                    </p>
                                    <span class="text-[11px] text-gray-400 whitespace-nowrap shrink-0">{{ $act->created_at ? $act->created_at->diffForHumans() : '-' }}</span>
                                </div>
                                @if(!empty($act->keterangan))
                                    <p class="text-[11px] text-gray-500 mt-1 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                        {{ $act->keterangan }}
                                    </p>
                                @endif
                                <div class="flex items-center gap-2 mt-1.5 text-[10px] text-gray-400">
                                    <span>{{ $act->created_at ? $act->created_at->format('d M Y, H:i:s') : '' }} WIB</span>
                                    <span>•</span>
                                    <span class="capitalize font-semibold text-gray-500">{{ $act->tipe }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-xs text-gray-400">
                            Belum ada riwayat aktivitas yang tercatat.
                        </div>
                    @endforelse
                </div>

                {{-- Footer Modal --}}
                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] text-gray-500">
                        Total <strong class="text-gray-800">{{ $semuaAktivitas->count() }}</strong> catatan riwayat aktivitas
                    </span>
                    <button
                        type="button"
                        x-on:click="$wire.tutupModalAktivitas()"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-gray-700 bg-white border border-gray-200 hover:bg-gray-100 transition active:scale-95 cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 8. INITIALIZE & DYNAMIC REAL-TIME UPDATE CHART.JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initDashboardCharts();
        });

        document.addEventListener('livewire:navigated', function () {
            initDashboardCharts();
        });

        // Listener Real-Time saat Kalender Bulan/Tahun Diubah oleh User
        window.addEventListener('update-chart-data', function (event) {
            const data = event.detail;
            if (data && data.grafik && window.targetHasilChartInstance) {
                window.targetHasilChartInstance.data.labels = data.grafik.labels;
                window.targetHasilChartInstance.data.datasets[0].data = data.grafik.target;
                window.targetHasilChartInstance.data.datasets[1].data = data.grafik.hasil;
                window.targetHasilChartInstance.update('active');
            }

            if (data && data.tahap && window.tahapDonutChartInstance) {
                const labels = data.tahap.items.map(item => item.nama);
                const values = data.tahap.items.map(item => item.persen);
                const colors = data.tahap.items.map(item => item.warna);

                window.tahapDonutChartInstance.data.labels = labels;
                window.tahapDonutChartInstance.data.datasets[0].data = values;
                window.tahapDonutChartInstance.data.datasets[0].backgroundColor = colors;
                window.tahapDonutChartInstance.update('active');
            }
        });

        function initDashboardCharts() {
            // 1. Grafik Target vs Hasil Pengerjaan Baju Bulanan
            const targetCanvas = document.getElementById('targetHasilChart');
            if (targetCanvas) {
                if (window.targetHasilChartInstance) {
                    window.targetHasilChartInstance.destroy();
                }

                const ctx = targetCanvas.getContext('2d');
                window.targetHasilChartInstance = new Chart(ctx, {
                    data: {
                        labels: @json($grafikBulanan['labels']),
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Target Pabrik',
                                data: @json($grafikBulanan['target']),
                                backgroundColor: '#E2E8F0',
                                borderRadius: 6,
                                barThickness: 24,
                                order: 2
                            },
                            {
                                type: 'line',
                                label: 'Hasil Jadi',
                                data: @json($grafikBulanan['hasil']),
                                borderColor: '#115E59',
                                backgroundColor: '#0D9488',
                                borderWidth: 3,
                                pointBackgroundColor: '#111827',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                tension: 0.35,
                                order: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111827',
                                titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                                bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                                padding: 10,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: 8000,
                                ticks: {
                                    stepSize: 2000,
                                    color: '#94A3B8',
                                    font: { family: 'Plus Jakarta Sans', size: 10 }
                                },
                                grid: {
                                    color: '#F1F5F9',
                                    drawBorder: false
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#64748B',
                                    font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // 2. Tahap Pengerjaan Donut Chart
            const donutCanvas = document.getElementById('tahapDonutChart');
            if (donutCanvas) {
                if (window.tahapDonutChartInstance) {
                    window.tahapDonutChartInstance.destroy();
                }

                const ctxDonut = donutCanvas.getContext('2d');
                window.tahapDonutChartInstance = new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_column($tahapProduksi['items'], 'nama')),
                        datasets: [{
                            data: @json(array_column($tahapProduksi['items'], 'persen')),
                            backgroundColor: @json(array_column($tahapProduksi['items'], 'warna')),
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '76%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111827',
                                titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                                bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                                padding: 8,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ': ' + context.raw + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    </script>
</div>
