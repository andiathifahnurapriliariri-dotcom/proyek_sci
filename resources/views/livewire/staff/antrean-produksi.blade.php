<div class="space-y-6">

    {{-- ALERT SUKSES DENGAN NOTIFIKASI ESTAFET ALUR --}}
    @if ($pesanSukses)
        <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200/80 flex items-center justify-between gap-3 text-teal-800 text-xs font-semibold shadow-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-teal-600 text-white flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
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

    {{-- 1. HEADER ANTREAN BERDASARKAN DEPARTEMEN / ROLE --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-extrabold text-teal-700 tracking-wider uppercase mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                    <polyline points="2 17 12 22 22 17"/>
                    <polyline points="2 12 12 17 22 12"/>
                </svg>
                @if (in_array($roleStaff, ['pemotongan', 'pemotongan_kain']))
                    <span>DEPARTEMEN PEMOTONGAN KAIN • TAHAP 1 DARI 4</span>
                @elseif ($roleStaff === 'penjahitan')
                    <span>DEPARTEMEN PENJAHITAN • TAHAP 2 DARI 4</span>
                @elseif ($roleStaff === 'steam')
                    <span>DEPARTEMEN STEAM & FINISHING • TAHAP 3 DARI 4</span>
                @elseif ($roleStaff === 'sablon')
                    <span>DEPARTEMEN SABLON & QC AKHIR • TAHAP 4 DARI 4</span>
                @else
                    <span>WORKFLOW KENDALI WORKSHOP KONVEKSI</span>
                @endif
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug">
                @if (in_array($roleStaff, ['pemotongan', 'pemotongan_kain']))
                    Antrean Pemotongan Kain (SPK Masuk)
                @elseif ($roleStaff === 'penjahitan')
                    Antrean Penjahitan Pakaian
                @elseif ($roleStaff === 'steam')
                    Antrean Steam & Finishing
                @elseif ($roleStaff === 'sablon')
                    Antrean Sablon & QC Akhir
                @else
                    Antrean & Alur Pengerjaan Pakaian
                @endif
            </h1>

            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1 max-w-3xl leading-normal">
                @if (in_array($roleStaff, ['pemotongan', 'pemotongan_kain']))
                   
                @elseif ($roleStaff === 'penjahitan')
                    Menerima potongan kain dari tim Pemotongan. Jahit dan rakit potongan kain sesuai pola, lalu klik <strong>Konfirmasi Selesai Jahit</strong> agar pesanan otomatis diteruskan ke tim Steam.
                @elseif ($roleStaff === 'steam')
                    Menerima pakaian yang sudah dijahit dari tim Penjahitan. Lakukan perapian setrika uap, lalu klik <strong>Konfirmasi Selesai Steam</strong> agar pesanan diteruskan ke tim Sablon.
                @elseif ($roleStaff === 'sablon')
                    Menerima pakaian dari tim Steam. Lakukan cetak sablon sesuai desain, lalu klik <strong>Konfirmasi Selesai Produksi</strong> agar pesanan dinyatakan rampung dan Admin menerima konfirmasi.
                @else
                    Alur berurutan otomatis: Pemesanan Admin ➔ Pemotongan Kain ➔ Penjahitan ➔ Steam ➔ Sablon ➔ Selesai Produksi (Admin).
                @endif
            </p>
        </div>

        {{-- Switcher Button Tampilan --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2.5 shrink-0">
            <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200/80">
                <button wire:click="gantiView('departemen')"
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $viewMode === 'departemen' || (!in_array($viewMode, ['kanban', 'tabel']) && in_array($roleStaff, ['pemotongan', 'pemotongan_kain', 'penjahitan', 'steam', 'sablon'])) ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                        <path d="M3 9h18"/>
                    </svg>
                    <span>Tampilan Kartu</span>
                </button>

                @if (!in_array($roleStaff, ['pemotongan', 'pemotongan_kain', 'penjahitan', 'steam', 'sablon']))
                    <button wire:click="gantiView('kanban')"
                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $viewMode === 'kanban' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M9 3v18M15 3v18"/>
                        </svg>
                        <span>Semua Alur (4 Tahap)</span>
                    </button>
                @endif

                <button wire:click="gantiView('tabel')"
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $viewMode === 'tabel' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                    <span>Tabel Ringkas</span>
                </button>
            </div>

            <button wire:click="$set('showModalSupervisor', true)"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <span>Panggil Supervisor</span>
            </button>
        </div>
    </div>

    {{-- 2. KARTU STATISTIK: KHUSUS DEPARTEMEN MASING-MASING (HAPUS DEPARTEMEN LAIN) --}}
    @if (in_array($roleStaff, ['pemotongan', 'pemotongan_kain']))
        {{-- KHUSUS PEMOTONGAN KAIN: HANYA TAMPILKAN STATUS PEMOTONGAN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Antrean Potong --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ANTREAN POTONG</span>
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="6" cy="6" r="3"/>
                            <circle cx="6" cy="18" r="3"/>
                            <line x1="20" y1="4" x2="8.12" y2="15.88"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkPemotongan->count() }} Pesanan Masuk</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Pesanan Masuk dari Admin</span>
                </div>
            </div>

            {{-- Card 2: Sedang Dipotong --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SEDANG DIPOTONG</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="2" x2="12" y2="6"/>
                            <line x1="12" y1="18" x2="12" y2="22"/>
                            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/>
                            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $sedangDikerjakanCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-amber-700">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Aktif di Meja Potong Pola</span>
                </div>
            </div>

            {{-- Card 3: Menunggu Dipotong --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">MENUNGGU DIPOTONG</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $menungguCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>Rol Kain Siap di Rak Potong</span>
                </div>
            </div>

            {{-- Card 4: Selesai Dipotong --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SELESAI DIPOTONG</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $selesaiDepartemenCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Diteruskan ke Penjahitan</span>
                </div>
            </div>
        </div>

    @elseif ($roleStaff === 'penjahitan')
        {{-- KHUSUS PENJAHITAN: HANYA TAMPILKAN STATUS PENJAHITAN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ANTREAN JAHIT</span>
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkPenjahitan->count() }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Potongan Kain dari Pemotongan</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SEDANG DIJAHIT</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="2" x2="12" y2="6"/>
                            <line x1="12" y1="18" x2="12" y2="22"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $sedangDikerjakanCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-amber-700">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Aktif di Meja & Mesin Jahit</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">MENUNGGU DIJAHIT</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $menungguCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>Pola Kain Siap Rakit</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SELESAI DIJAHIT</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $selesaiDepartemenCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Diteruskan ke Steam</span>
                </div>
            </div>
        </div>

    @elseif ($roleStaff === 'steam')
        {{-- KHUSUS STEAM: HANYA TAMPILKAN STATUS STEAM --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ANTREAN STEAM</span>
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 4c0-1.1-.9-2-2-2s-2 .9-2 2"/>
                            <path d="M17 8h1a4 4 0 1 1 0 8h-1"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkSteam->count() }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Pakaian Jadi dari Penjahitan</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SEDANG DISTEAM</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="2" x2="12" y2="6"/>
                            <line x1="12" y1="18" x2="12" y2="22"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $sedangDikerjakanCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-amber-700">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Aktif di Stasiun Setrika Uap</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">MENUNGGU DISTEAM</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $menungguCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>Menunggu Perapian Bentuk</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SELESAI STEAM</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $selesaiDepartemenCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Diteruskan ke Sablon</span>
                </div>
            </div>
        </div>

    @elseif ($roleStaff === 'sablon')
        {{-- KHUSUS SABLON: HANYA TAMPILKAN STATUS SABLON --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ANTREAN SABLON</span>
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkSablon->count() }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Pakaian Siap Sablon dari Steam</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SEDANG DISABLON</span>
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="2" x2="12" y2="6"/>
                            <line x1="12" y1="18" x2="12" y2="22"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $sedangDikerjakanCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-amber-700">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Aktif di Meja Cetak Sablon</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">MENUNGGU DISABLON</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $menungguCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span>Screen & Kuota Desain Siap</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">SELESAI PRODUKSI</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $selesaiDepartemenCount }} Pesanan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Lolos QC & Admin Terkonfirmasi</span>
                </div>
            </div>
        </div>

    @else
        {{-- UNTUK ADMIN ATAU STAFF UMUM: TAMPILKAN 4 LINI KERJA KESELURUHAN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">1. MEJA POTONG</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="6" cy="6" r="3"/>
                            <circle cx="6" cy="18" r="3"/>
                            <line x1="20" y1="4" x2="8.12" y2="15.88"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkPemotongan->count() }} Pesanan Masuk</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Tahap Awal dari Admin</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">2. LINI JAHIT</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkPenjahitan->count() }} Antrean Jahit</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Setelah Selesai Potong</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">3. STASIUN STEAM</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 4c0-1.1-.9-2-2-2s-2 .9-2 2"/>
                            <path d="M17 8h1a4 4 0 1 1 0 8h-1"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkSteam->count() }} Antrean Steam</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Setelah Selesai Jahit</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">4. SABLON & QC</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $spkSablon->count() }} Antrean Sablon</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span>Tahap Akhir Menuju Selesai</span>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================== --}}
    {{-- 3. TAMPILAN UTAMA ANTREAN DEPARTEMEN SPESIFIK                  --}}
    {{-- ============================================================== --}}
    @if ($viewMode === 'departemen' || (!in_array($viewMode, ['kanban', 'tabel']) && in_array($roleStaff, ['pemotongan', 'pemotongan_kain', 'penjahitan', 'steam', 'sablon'])))

        @php
            $targetAntrean = match ($roleStaff) {
                'pemotongan', 'pemotongan_kain' => $spkPemotongan,
                'penjahitan' => $spkPenjahitan,
                'steam' => $spkSteam,
                'sablon' => $spkSablon,
                default => $spkPemotongan,
            };

            $tahapKey = match ($roleStaff) {
                'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
                'penjahitan' => 'penjahitan',
                'steam' => 'steam',
                'sablon' => 'sablon',
                default => 'pemotongan_kain',
            };

            $labelAksiMulai = match ($roleStaff) {
                'pemotongan', 'pemotongan_kain' => 'Mulai Potong Kain',
                'penjahitan' => 'Mulai Menjahit',
                'steam' => 'Mulai Proses Steam',
                'sablon' => 'Mulai Cetak Sablon',
                default => 'Mulai Dikerjakan',
            };

            $labelAksiSelesai = match ($roleStaff) {
                'pemotongan', 'pemotongan_kain' => 'Konfirmasi Selesai Potong (Kirim ke Penjahitan)',
                'penjahitan' => 'Konfirmasi Selesai Jahit (Kirim ke Steam)',
                'steam' => 'Konfirmasi Selesai Steam (Kirim ke Sablon)',
                'sablon' => 'Konfirmasi Selesai Produksi (Kirim ke Admin)',
                default => 'Konfirmasi Selesai',
            };
        @endphp

        <div class="space-y-6">
            {{-- Header Antrean Aktif --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900">
                        Daftar Pesanan Siap Dikerjakan ({{ $targetAntrean->count() }} SPK)
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">
                        Klik mulai untuk mencatat pengerjaan, lalu konfirmasi selesai agar pesanan otomatis berlanjut ke tahap berikutnya.
                    </p>
                </div>
            </div>

            {{-- Grid Kartu Pesanan Departemen --}}
            @if ($targetAntrean->isEmpty())
                <div class="p-12 text-center bg-white rounded-3xl border border-dashed border-slate-200 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Tidak ada antrean pesanan aktif</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Semua tugas telah selesai dikerjakan atau sedang menunggu alur dari tahap sebelumnya.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach ($targetAntrean as $item)
                        @php
                            $pesanan = $item->pemesanan;
                            $tahapObj = $item->tahapan->firstWhere('nama_tahap', $tahapKey);
                            $isSedangDikerjakan = $tahapObj && $tahapObj->status === 'sedang_dikerjakan';
                        @endphp
                        <div class="bg-white rounded-2xl p-5 border {{ $isSedangDikerjakan ? 'border-teal-500 ring-2 ring-teal-500/20 shadow-md' : 'border-slate-200/90 shadow-xs' }} flex flex-col justify-between space-y-4 hover:border-slate-300 transition">
                            
                            {{-- Top Header Kartu --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-slate-100 text-slate-900 border border-slate-200">
                                        {{ $pesanan?->nomor_pemesanan ?? $item->kode_produksi }}
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        @if ($isSedangDikerjakan)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                                Sedang Dikerjakan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                                Menunggu Giliran
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm sm:text-base font-black text-slate-900 leading-snug">
                                        {{ $pesanan?->jenis_kain ?? 'Bahan Pakaian Custom' }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-0.5">
                                        <span>Pelanggan: <strong class="text-slate-800">{{ $pesanan?->pelanggan?->nama ?? 'Pelanggan Umum' }}</strong></span>
                                        <span>•</span>
                                        <span>Warna: <strong class="text-slate-800">{{ $pesanan?->warna ?? 'Sesuai SPK' }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Rincian Ukuran & Jumlah Potong (Penting untuk Pemotongan & Penjahitan) --}}
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                    <span>Total Kuantitas:</span>
                                    <span class="text-teal-700 font-black text-sm">{{ $pesanan?->jumlah ?? 0 }} Pcs</span>
                                </div>

                                {{-- Rincian Variasi Ukuran dari Database --}}
                                @if ($pesanan && $pesanan->variasi->isNotEmpty())
                                    <div class="pt-1 border-t border-slate-200/60">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Rincian Variasi Ukuran:</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($pesanan->variasi as $var)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-white text-slate-800 border border-slate-200 shadow-2xs">
                                                    {{ $var->ukuran }}: <strong class="ml-1 text-teal-800">{{ $var->jumlah }} pcs</strong>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif ($pesanan?->ukuran_baju)
                                    <div class="pt-1 border-t border-slate-200/60 text-xs text-slate-600">
                                        Ukuran Pesanan: <strong>{{ $pesanan->ukuran_baju }}</strong>
                                    </div>
                                @endif

                                {{-- Kesiapan Bahan & Catatan --}}
                                <div class="pt-1 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                                    <span>Kesiapan Bahan:</span>
                                    <span class="font-bold text-slate-800">{{ $pesanan?->kesiapan_bahan ?? 'Rol Bahan Siap di Meja' }}</span>
                                </div>
                            </div>

                            {{-- Operator & Catatan Pengerjaan --}}
                            @if ($tahapObj && $tahapObj->operator)
                                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100/70 text-xs">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($tahapObj->operator->name, 0, 2)) }}
                                    </div>
                                    <div class="truncate">
                                        <span class="font-bold text-slate-800 block truncate">{{ $tahapObj->operator->name }}</span>
                                        <span class="text-[10px] text-slate-400">Dimulai {{ $tahapObj->waktu_mulai?->format('H:i') ?? 'Baru saja' }} WIB</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Tombol Aksi Estafet --}}
                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                @if (! $isSedangDikerjakan)
                                    <button wire:click="mulaiTahap({{ $item->id }}, '{{ $tahapKey }}')"
                                            class="w-full py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition cursor-pointer shadow-xs flex items-center justify-center gap-2">
                                        <span>▷</span>
                                        <span>{{ $labelAksiMulai }}</span>
                                    </button>
                                @else
                                    <button wire:click="konfirmasiSelesaiTahap({{ $item->id }}, '{{ $tahapKey }}')"
                                            class="w-full py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition cursor-pointer shadow-xs flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span>{{ $labelAksiSelesai }}</span>
                                    </button>
                                @endif

                                <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $pesanan?->id]) }}"
                                   class="text-xs font-bold text-slate-500 hover:text-slate-900 text-center block py-1 transition">
                                    Lihat Lembar SPK Lengkap
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Riwayat Selesai Departemen (Telah Diteruskan) --}}
            @if ($riwayatDepartemen->isNotEmpty())
                <div class="pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-slate-900">
                            Riwayat Pekerjaan Selesai (Sudah Diteruskan ke Tahap Berikutnya)
                        </h3>
                        <span class="text-xs font-bold text-teal-700">Tercatat di Sistem</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                        <th class="px-5 py-3">No. SPK</th>
                                        <th class="px-5 py-3">Pelanggan & Produk</th>
                                        <th class="px-5 py-3">Jumlah</th>
                                        <th class="px-5 py-3">Status Saat Ini</th>
                                        <th class="px-5 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($riwayatDepartemen as $riw)
                                        @php $rp = $riw->pemesanan; @endphp
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="px-5 py-3 font-extrabold text-slate-900">
                                                {{ $rp?->nomor_pemesanan ?? $riw->kode_produksi }}
                                            </td>
                                            <td class="px-5 py-3">
                                                <span class="font-bold text-slate-800 block">{{ $rp?->jenis_kain }}</span>
                                                <span class="text-[11px] text-slate-400">{{ $rp?->pelanggan?->nama }}</span>
                                            </td>
                                            <td class="px-5 py-3 font-bold text-slate-900">
                                                {{ $rp?->jumlah ?? 0 }} pcs
                                            </td>
                                            <td class="px-5 py-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800 border border-teal-200/60">
                                                    Sedang di {{ ucwords(str_replace('_', ' ', $riw->tahap_saat_ini)) }} ({{ $riw->progress_persen }}%)
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-right">
                                                <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $rp?->id]) }}"
                                                   class="text-xs font-bold text-slate-600 hover:text-slate-900">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    {{-- ============================================================== --}}
    {{-- 4. PAPAN ALUR KANBAN (SEMUA 4 TAHAP LENGKAP)                  --}}
    {{-- ============================================================== --}}
    @elseif ($viewMode === 'kanban')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- KOLOM 1: 1. Pemotongan Kain --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-1 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-black"></span>
                        <h3 class="text-sm font-black text-slate-900">1. Pemotongan</h3>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600">
                        {{ $spkPemotongan->count() }} SPK
                    </span>
                </div>

                <div class="space-y-3.5">
                    @forelse ($spkPemotongan as $item)
                        @php $pesanan = $item->pemesanan; @endphp
                        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3 hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-extrabold bg-slate-100 text-slate-800">
                                    {{ $pesanan?->nomor_pemesanan ?? 'ORD-2026-001' }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="6" cy="6" r="3"/>
                                    <circle cx="6" cy="18" r="3"/>
                                    <line x1="20" y1="4" x2="8.12" y2="15.88"/>
                                </svg>
                            </div>

                            <div>
                                <h4 class="text-xs font-black text-slate-900 leading-snug">{{ $pesanan?->jenis_kain ?? 'Kaos Combed 30s' }}</h4>
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">Kuantitas: <strong class="text-slate-800">{{ $pesanan?->jumlah ?? 30 }} Pcs</strong></p>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                    <span>Pemotongan Pola</span>
                                    <span class="text-teal-700">{{ $item->progress_persen }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-black h-full rounded-full" style="width: {{ $item->progress_persen }}%;"></div>
                                </div>
                            </div>

                            <div class="space-y-2 pt-1">
                                <button wire:click="lanjutkanTahap({{ $item->id }}, 'pemotongan_kain')"
                                        class="w-full py-2 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition cursor-pointer shadow-xs flex items-center justify-center gap-1.5">
                                    <span>▷</span>
                                    <span>Proses / Selesaikan</span>
                                </button>
                                <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $pesanan?->id]) }}"
                                   class="text-[11px] font-bold text-slate-500 hover:text-slate-900 text-center block transition">
                                    Buka Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
                            Tidak ada antrean potong kain.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- KOLOM 2: 2. Penjahitan --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-1 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                        <h3 class="text-sm font-black text-slate-900">2. Penjahitan</h3>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600">
                        {{ $spkPenjahitan->count() }} SPK
                    </span>
                </div>

                <div class="space-y-3.5">
                    @forelse ($spkPenjahitan as $item)
                        @php $pesanan = $item->pemesanan; @endphp
                        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3 hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-extrabold bg-slate-100 text-slate-800">
                                    {{ $pesanan?->nomor_pemesanan ?? 'ORD-2026-003' }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23z"/>
                                </svg>
                            </div>

                            <div>
                                <h4 class="text-xs font-black text-slate-900 leading-snug">{{ $pesanan?->jenis_kain ?? 'Polo Shirt Lacoste' }}</h4>
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">Kuantitas: <strong class="text-slate-800">{{ $pesanan?->jumlah ?? 50 }} Pcs</strong></p>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                    <span>Perakitan Jahit</span>
                                    <span class="text-teal-700">{{ $item->progress_persen }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-teal-600 h-full rounded-full" style="width: {{ $item->progress_persen }}%;"></div>
                                </div>
                            </div>

                            <div class="space-y-2 pt-1">
                                <button wire:click="lanjutkanTahap({{ $item->id }}, 'penjahitan')"
                                        class="w-full py-2 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition cursor-pointer shadow-xs flex items-center justify-center gap-1.5">
                                    <span>→</span>
                                    <span>Proses / Selesaikan</span>
                                </button>
                                <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $pesanan?->id]) }}"
                                   class="text-[11px] font-bold text-slate-500 hover:text-slate-900 text-center block transition">
                                    Buka Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
                            Tidak ada antrean penjahitan.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- KOLOM 3: 3. Steam --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-1 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h3 class="text-sm font-black text-slate-900">3. Steam</h3>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600">
                        {{ $spkSteam->count() }} SPK
                    </span>
                </div>

                <div class="space-y-3.5">
                    @forelse ($spkSteam as $item)
                        @php $pesanan = $item->pemesanan; @endphp
                        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3 hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-extrabold bg-slate-100 text-slate-800">
                                    {{ $pesanan?->nomor_pemesanan ?? 'ORD-2026-005' }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 4c0-1.1-.9-2-2-2s-2 .9-2 2"/>
                                    <path d="M17 8h1a4 4 0 1 1 0 8h-1"/>
                                </svg>
                            </div>

                            <div>
                                <h4 class="text-xs font-black text-slate-900 leading-snug">{{ $pesanan?->jenis_kain ?? 'Jaket Parasut Taslan' }}</h4>
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">Kuantitas: <strong class="text-slate-800">{{ $pesanan?->jumlah ?? 50 }} Pcs</strong></p>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                    <span>Perapian Uap</span>
                                    <span class="text-teal-700">{{ $item->progress_persen }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ $item->progress_persen }}%;"></div>
                                </div>
                            </div>

                            <div class="space-y-2 pt-1">
                                <button wire:click="lanjutkanTahap({{ $item->id }}, 'steam')"
                                        class="w-full py-2 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition cursor-pointer shadow-xs flex items-center justify-center gap-1.5">
                                    <span>→</span>
                                    <span>Proses / Selesaikan</span>
                                </button>
                                <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $pesanan?->id]) }}"
                                   class="text-[11px] font-bold text-slate-500 hover:text-slate-900 text-center block transition">
                                    Buka Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
                            Tidak ada antrean steam.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- KOLOM 4: 4. Sablon & QC --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-1 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                        <h3 class="text-sm font-black text-slate-900">4. Sablon & QC</h3>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600">
                        {{ $spkSablon->count() }} SPK
                    </span>
                </div>

                <div class="space-y-3.5">
                    @forelse ($spkSablon as $item)
                        @php $pesanan = $item->pemesanan; @endphp
                        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3 hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-extrabold bg-slate-100 text-slate-800">
                                    {{ $pesanan?->nomor_pemesanan ?? 'ORD-2026-004' }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            </div>

                            <div>
                                <h4 class="text-xs font-black text-slate-900 leading-snug">{{ $pesanan?->jenis_kain ?? 'Kaos Sablon Plastisol' }}</h4>
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">Kuantitas: <strong class="text-slate-800">{{ $pesanan?->jumlah ?? 40 }} Pcs</strong></p>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                    <span>Sablon & QC Akhir</span>
                                    <span class="text-indigo-700">{{ $item->progress_persen }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $item->progress_persen }}%;"></div>
                                </div>
                            </div>

                            <div class="space-y-2 pt-1">
                                <button wire:click="lanjutkanTahap({{ $item->id }}, 'sablon')"
                                        class="w-full py-2 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition cursor-pointer shadow-xs flex items-center justify-center gap-1.5">
                                    <span>✓</span>
                                    <span>Selesaikan Produksi</span>
                                </button>
                                <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $pesanan?->id]) }}"
                                   class="text-[11px] font-bold text-slate-500 hover:text-slate-900 text-center block transition">
                                    Buka Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center bg-white rounded-2xl border border-dashed border-slate-200 text-xs text-slate-400">
                            Tidak ada antrean sablon.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    {{-- ============================================================== --}}
    {{-- 5. VIEW TABEL RINGKAS                                          --}}
    {{-- ============================================================== --}}
    @else
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                            <th class="px-5 py-3.5">SPK & Pelanggan</th>
                            <th class="px-5 py-3.5">Produk</th>
                            <th class="px-5 py-3.5">Jumlah</th>
                            <th class="px-5 py-3.5">Tahap Saat Ini</th>
                            <th class="px-5 py-3.5">Kesiapan Bahan</th>
                            <th class="px-5 py-3.5 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($semuaAntrean as $row)
                            @php $p = $row->pemesanan; @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-3.5">
                                    <span class="font-extrabold text-slate-900 block">{{ $p?->nomor_pemesanan ?? $row->kode_produksi }}</span>
                                    <span class="text-[11px] text-slate-500">{{ $p?->pelanggan?->nama }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $p?->jenis_kain }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-900">{{ $p?->jumlah }} pcs</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800">
                                        {{ ucwords(str_replace('_', ' ', $row->tahap_saat_ini)) }} ({{ $row->progress_persen }}%)
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $p?->kesiapan_bahan ?? 'Bahan Siap' }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('staff.detail-pekerjaan', ['pemesananId' => $p?->id]) }}"
                                       class="px-3 py-1.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- MODAL PANGGIL SUPERVISOR --}}
    @if ($showModalSupervisor)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Panggil Supervisor Lantai Workshop</h3>
                    <button wire:click="$set('showModalSupervisor', false)" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-3">
                    <p class="text-xs text-slate-500">
                        Kirimkan pesan mendesak untuk memanggil Supervisor Produksi ke meja / stasiun kerja Anda.
                    </p>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pesan Bantuan / Lokasi Meja</label>
                        <textarea wire:model="pesanSupervisor"
                                  rows="3"
                                  placeholder="Contoh: Butuh verifikasi toleransi potongan kain di Meja Potong 1..."
                                  class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-hidden focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500"></textarea>
                        @error('pesanSupervisor') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button wire:click="$set('showModalSupervisor', false)"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button wire:click="panggilSupervisor"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-black hover:bg-zinc-800 transition cursor-pointer">
                        Kirim Panggilan
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
