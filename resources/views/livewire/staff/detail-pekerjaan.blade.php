<div class="space-y-6 w-full" style="width: 100%;">

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

    {{-- 1. BREADCRUMBS & TOP META STATUS --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-gray-500 font-medium">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('staff.dashboard') }}" class="hover:text-gray-900 transition flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
                <span>Dashboard Tugas</span>
            </a>
            <span>/</span>
            <span class="text-gray-600">Detail Pekerjaan SPK</span>
            <span>/</span>
            {{-- Pilihan SPK Dropdown Cepat --}}
            <div class="relative inline-block">
                <select wire:model.live="pemesananId"
                        class="bg-transparent font-bold text-gray-900 hover:text-[#0D7A68] cursor-pointer focus:outline-hidden py-0.5 pr-4 text-xs border-b border-dashed border-gray-300">
                    @foreach ($daftarPilihanSpk as $spk)
                        <option value="{{ $spk->id }}">{{ $spk->nomor_pemesanan }} - {{ $spk->jenis_kain }} ({{ $spk->pelanggan?->nama }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <button wire:click="$set('activeTab', 'detail')" class="inline-flex items-center gap-1 text-gray-700 hover:text-[#0D7A68] font-semibold cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <span>Lihat Informasi Pesanan</span>
            </button>
            <span class="text-gray-300">•</span>
            <span class="inline-flex items-center gap-1.5 text-gray-600 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Alur Kerja Estafet Workshop</span>
            </span>
        </div>
    </div>

    {{-- 2. TITLE & ACTION BUTTON BAR --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div>
                {{-- Status Pills Berdasarkan Role & Tahapan --}}
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    @php
                        $userAuth = auth()->user();
                        $roleUser = $userAuth?->role ?? 'staff';
                        $tahapRoleNama = match ($roleUser) {
                            'pemotongan', 'pemotongan_kain' => 'pemotongan_kain',
                            'penjahitan' => 'penjahitan',
                            'steam' => 'steam',
                            'sablon' => 'sablon',
                            default => null,
                        };

                        $tahapDepartemen = $tahapRoleNama
                            ? $tahapan->firstWhere('nama_tahap', $tahapRoleNama)
                            : ($tahapan->firstWhere('status', 'sedang_dikerjakan') ?? $tahapan->firstWhere('status', 'siap_dikerjakan') ?? $tahapan->first());

                        $tahapNamaLabel = $tahapDepartemen ? $tahapDepartemen->label_tahap : 'Pengerjaan Konveksi';
                        $tahapUrutan = $tahapDepartemen ? $tahapDepartemen->urutan : 1;

                        // Pengecekan apakah tahap sebelumnya sudah selesai
                        $bisaDikerjakan = true;
                        $pesanMenunggu = '';
                        if ($tahapRoleNama === 'penjahitan') {
                            $prev = $tahapan->firstWhere('nama_tahap', 'pemotongan_kain');
                            if ($prev && $prev->status !== 'selesai') {
                                $bisaDikerjakan = false;
                                $pesanMenunggu = 'Menunggu Pemotongan Kain Selesai';
                            }
                        } elseif ($tahapRoleNama === 'steam') {
                            $prev = $tahapan->firstWhere('nama_tahap', 'penjahitan');
                            if ($prev && $prev->status !== 'selesai') {
                                $bisaDikerjakan = false;
                                $pesanMenunggu = 'Menunggu Penjahitan Selesai';
                            }
                        } elseif ($tahapRoleNama === 'sablon') {
                            $prev = $tahapan->firstWhere('nama_tahap', 'steam');
                            if ($prev && $prev->status !== 'selesai') {
                                $bisaDikerjakan = false;
                                $pesanMenunggu = 'Menunggu Steam Selesai';
                            }
                        }

                        $isTahapSelesai = $tahapDepartemen && $tahapDepartemen->status === 'selesai';
                        $isTahapSedang = $tahapDepartemen && $tahapDepartemen->status === 'sedang_dikerjakan';
                    @endphp

                    @if ($isTahapSelesai)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Tugas {{ $tahapNamaLabel }} Selesai (Diteruskan ke Tahap Berikutnya)</span>
                        </span>
                    @elseif (! $bisaDikerjakan)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>{{ $pesanMenunggu }}</span>
                        </span>
                    @elseif ($isTahapSedang)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#E6F7F5] text-[#0D7A68] border border-teal-200/60">
                            <span class="w-2 h-2 rounded-full bg-[#0D9488] animate-pulse"></span>
                            <span>Sedang Dikerjakan (Tahap {{ $tahapUrutan }}: {{ $tahapNamaLabel }})</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Siap Dikerjakan (Tahap {{ $tahapUrutan }}: {{ $tahapNamaLabel }})</span>
                        </span>
                    @endif

                    <span class="text-xs font-semibold text-rose-500">
                        Target Selesai : {{ $pesanan?->tanggal_selesai_estimasi ? \Carbon\Carbon::parse($pesanan->tanggal_selesai_estimasi)->format('d F Y') : 'Sesuai SPK' }}
                    </span>
                </div>

                <h1 class="text-2xl font-black text-gray-900 tracking-tight">
                    Detail Pekerjaan: {{ $pesanan?->jenis_kain ?? 'Pesanan Pakaian' }}
                </h1>
                {{-- Metadata SPK Tersusun Terstruktur ke Bawah --}}
                <div class="mt-3 space-y-1.5 text-xs text-gray-600 font-medium">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 font-medium min-w-[75px]">No. SPK:</span>
                        <strong class="text-gray-900 font-bold font-mono">{{ $pesanan?->nomor_pemesanan ?? 'SPK' }}</strong>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 font-medium min-w-[75px]">Pelanggan:</span>
                        <strong class="text-gray-900 font-bold">{{ $pesanan?->pelanggan?->nama ?? 'Umum' }}</strong>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 font-medium min-w-[75px]">Kuantitas:</span>
                        <strong class="text-gray-900 font-bold">{{ $pesanan?->jumlah ?? 0 }} Pcs</strong>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 font-medium min-w-[75px]">Kategori:</span>
                        <strong class="text-gray-900 font-bold">{{ $pesanan?->kategori_produk ?: ($pesanan?->produk?->kategori ?: 'Pakaian Konveksi') }}</strong>
                    </div>
                </div>
            </div>

            {{-- Action Buttons Kanan (Dibatasi Sesuai Wewenang Role) --}}
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                {{-- Cetak Lembar SPK --}}
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-xs font-bold hover:bg-gray-50 transition shadow-2xs cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9"/>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect width="12" height="8" x="6" y="14"/>
                    </svg>
                    <span>Cetak Lembar SPK</span>
                </button>

                {{-- Laporkan Kendala --}}
                <button wire:click="$set('showModalKendala', true)"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50/70 text-rose-700 text-xs font-bold hover:bg-rose-100 transition shadow-2xs cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <span>Laporkan Kendala</span>
                </button>

                {{-- Tombol Pengerjaan Spesifik Role --}}
                @if ($isTahapSelesai)
                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/80 text-xs font-bold shadow-2xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>✓ {{ $tahapNamaLabel }} Selesai</span>
                    </div>
                @elseif (! $bisaDikerjakan)
                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200/80 text-xs font-bold shadow-2xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>{{ $pesanMenunggu }}</span>
                    </div>
                @elseif ($isTahapSedang)
                    <button wire:click="selesaikanTahapDepartemen"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0D7A68] hover:bg-[#0b6556] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>Selesaikan {{ $tahapNamaLabel }}</span>
                    </button>
                @else
                    <button wire:click="mulaiTahapDepartemen"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                        <span>Mulai {{ $tahapNamaLabel }}</span>
                    </button>
                    <button wire:click="selesaikanTahapDepartemen"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0D7A68] hover:bg-[#0b6556] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-teal-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>Selesaikan {{ $tahapNamaLabel }}</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- 3. TABS NAVIGASI LEMBAR KERJA --}}
        <div class="flex items-center gap-2 pt-3 border-t border-gray-100 overflow-x-auto">
            <button wire:click="$set('activeTab', 'alur')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeTab === 'alur' ? 'bg-black text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M7 7h10M7 12h10M7 17h6"/>
                </svg>
                <span>Lembar Kerja SPK & Alur</span>
            </button>

            <button wire:click="$set('activeTab', 'detail')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeTab === 'detail' ? 'bg-black text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                <span>Detail Pesanan & Pelanggan</span>
            </button>

            @if ($pesanan?->perlu_sablon)
                <button wire:click="$set('activeTab', 'sablon')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeTab === 'sablon' ? 'bg-black text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/>
                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.992 6.012 17.48 2 12 2z"/>
                    </svg>
                    <span>Spesifikasi Desain & Sablon</span>
                </button>
            @endif

            <button wire:click="$set('activeTab', 'log')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer {{ $activeTab === 'log' ? 'bg-black text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 14 14"/>
                </svg>
                <span>Log Riwayat Pengerjaan</span>
            </button>
        </div>
    </div>

    {{-- KONTEN TAB 1: LEMBAR KERJA SPK & ALUR --}}
    @if ($activeTab === 'alur')
        {{-- 4. SECTION: RINGKASAN PESANAN & PELANGGAN --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0D7A68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <h3 class="text-sm font-black text-gray-900">Ringkasan Pesanan & Pelanggan</h3>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold text-gray-600 bg-gray-100 font-mono">
                    {{ $pesanan?->nomor_pemesanan ?? 'SPK' }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Card 1: Nama Pemesan --}}
                <div class="p-4 rounded-xl bg-gray-50/70 border border-gray-100 space-y-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">NAMA PEMESAN / INSTANSI</span>
                    <p class="text-xs font-bold text-gray-900">{{ $pesanan?->pelanggan?->nama ?? 'Pelanggan Umum' }}</p>
                    <p class="text-[11px] text-gray-500 font-medium">PIC: {{ $pesanan?->admin?->name ?? ($pesanan?->pic_pelanggan ?: 'Michel') }} ({{ $pesanan?->admin?->telepon ?? '0812-3456-7890' }})</p>
                </div>

                {{-- Card 2: Tenggat Waktu --}}
                <div class="p-4 rounded-xl bg-gray-50/70 border border-gray-100 space-y-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">TENGGAT WAKTU SELESAI</span>
                    <p class="text-xs font-bold text-[#0D7A68]">
                        {{ $pesanan?->tanggal_selesai_estimasi ? \Carbon\Carbon::parse($pesanan->tanggal_selesai_estimasi)->format('d F Y') : 'Sesuai SPK' }}
                    </p>
                    <p class="text-[11px] text-gray-500 font-medium">
                        {{ $pesanan?->is_prioritas ? 'Prioritas Mendesak' : 'Reguler (Tepat Waktu)' }}
                    </p>
                </div>

                {{-- Card 3: Total Kuantitas --}}
                <div class="p-4 rounded-xl bg-gray-50/70 border border-gray-100 space-y-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">TOTAL KUANTITAS ORDER</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-base font-black text-gray-900">{{ $pesanan?->jumlah ?? 0 }}</span>
                        <span class="text-xs font-bold text-gray-600">Pcs</span>
                    </div>
                    <p class="text-[11px] text-gray-500 font-medium truncate">
                        @if ($pesanan && $pesanan->variasi->isNotEmpty())
                            {{ $pesanan->variasi->map(fn($v) => $v->ukuran . ': ' . $v->jumlah)->implode(', ') }} Pcs
                        @else
                            {{ $pesanan?->ukuran_baju ?? 'Ukuran Standar' }}
                        @endif
                    </p>
                </div>

                {{-- Card 4: Catatan / Kesiapan Bahan --}}
                <div class="p-4 rounded-xl bg-gray-50/70 border border-gray-100 space-y-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">STATUS KESIAPAN BAHAN</span>
                    <p class="text-xs font-bold text-gray-900">{{ $pesanan?->kesiapan_bahan ?: 'Bahan Siap di Meja Kerja' }}</p>
                    <p class="text-[11px] text-gray-500 font-medium truncate">{{ $pesanan?->catatan ?: 'Sesuai SOP Konveksi' }}</p>
                </div>
            </div>
        </div>

        {{-- 5. DUA KOLOM: SPESIFIKASI PRODUK & RINCIAN VARIASI PESANAN --}}
        <div class="staff-detail-grid grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">

            {{-- KOLOM KIRI (7/12): SPESIFIKASI PRODUK & BREAKDOWN UKURAN DARI DATABASE --}}
            <div class="staff-col-left lg:col-span-7 space-y-6 w-full min-w-0">

                {{-- Card Spesifikasi Produk Jadi --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0D7A68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23z"/>
                            </svg>
                            <h3 class="text-sm font-black text-gray-900">Spesifikasi Produk Jadi</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">
                            {{ $pesanan?->kategori_produk ?: 'Pakaian Konveksi' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Model & Jenis Pakaian --}}
                        <div class="p-3.5 rounded-xl bg-gray-50/70 border border-gray-100 space-y-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">PRODUK / JENIS PAKAIAN</span>
                            <p class="text-xs font-bold text-gray-900">{{ $pesanan?->jenis_kain ?? 'Kaos Custom' }}</p>
                            <p class="text-[11px] text-gray-500 font-medium">Bahan Kain: {{ $pesanan?->jenis_kain ?? '-' }}</p>
                        </div>

                        {{-- Warna Pakaian --}}
                        <div class="p-3.5 rounded-xl bg-gray-50/70 border border-gray-100 space-y-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">WARNA KAIN</span>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-800 shrink-0"></span>
                                <p class="text-xs font-bold text-gray-900">{{ $pesanan?->warna ?: 'Sesuai SPK' }}</p>
                            </div>
                            <p class="text-[11px] text-gray-500 font-medium">{{ $pesanan?->kesiapan_bahan ?: 'Bahan Kain Tersedia' }}</p>
                        </div>

                        {{-- Standar Jahitan & Finishing --}}
                        <div class="p-3.5 rounded-xl bg-gray-50/70 border border-gray-100 sm:col-span-2 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">STANDAR JAHITAN & FINISHING</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#0D7A68] bg-[#E6F7F5] px-2 py-0.5 rounded-full border border-teal-200/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[#0D7A68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    Standar Mutu Konveksi
                                </span>
                            </div>
                            <p class="text-xs font-bold text-gray-900">Jahitan Presisi Standar Mutu Workshop</p>
                            <p class="text-[11px] text-gray-500 leading-relaxed">
                                @if ($pesanan?->catatan)
                                    Catatan Pesanan: {{ $pesanan->catatan }}
                                @else
                                    Pola potongan, jahitan obras, dan finishing diperiksa sesuai SOP konveksi COTTON.id.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card Rincian Ukuran (DARI DATABASE VARIASI PESANAN) --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0D7A68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect width="18" height="18" x="3" y="3" rx="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                                <line x1="9" y1="21" x2="9" y2="9"/>
                            </svg>
                            <h3 class="text-sm font-black text-gray-900">Rincian Ukuran (Size Breakdown)</h3>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-black text-white">
                            Total Pesanan: {{ $pesanan?->jumlah ?? 0 }} Pcs
                        </span>
                    </div>

                    {{-- Kotak Ukuran Dinamis Sesuai Database --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        @if ($pesanan && $pesanan->variasi->isNotEmpty())
                            @foreach ($pesanan->variasi as $v)
                                <div class="p-3.5 rounded-xl bg-gray-50/80 border border-gray-100 space-y-1">
                                    <span class="text-[10px] font-bold text-gray-400 block">{{ $v->kategori_ukuran ? $v->kategori_ukuran . ' • ' : '' }}Ukuran {{ $v->ukuran }}</span>
                                    <div class="text-2xl font-black text-gray-900">{{ $v->jumlah }}</div>
                                    <span class="text-[10px] text-gray-400 font-medium">Pcs</span>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3.5 rounded-xl bg-gray-50/80 border border-gray-100 space-y-1 col-span-2 sm:col-span-4">
                                <span class="text-[10px] font-bold text-gray-400 block">Ukuran Pesanan</span>
                                <div class="text-xl font-black text-gray-900">{{ $pesanan?->ukuran_baju ?: 'Standar All Size' }}</div>
                                <span class="text-[10px] text-gray-400 font-medium">Total: {{ $pesanan?->jumlah ?? 0 }} Pcs</span>
                            </div>
                        @endif
                    </div>

                    {{-- Note Toleransi di bagian bawah --}}
                    <div class="flex items-center justify-between text-[11px] text-gray-500 pt-1">
                        <div class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            <span>Toleransi potongan kain ±1.5 cm mengikuti chart standar konveksi COTTON.id</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (5/12): AREA DESAIN / SABLON ATAU BAJU POLOS --}}
            <div class="staff-col-right lg:col-span-5 bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4 w-full min-w-0">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0D7A68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/>
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.992 6.012 17.48 2 12 2z"/>
                        </svg>
                        <h3 class="text-sm font-black text-gray-900">Area Desain & Sablon</h3>
                    </div>
                    @if ($pesanan?->perlu_sablon)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#E6F7F5] text-[#0D7A68]">
                            {{ $pesanan?->titik_sablon ?? 1 }} Titik Sablon
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                            Pakaian Polos
                        </span>
                    @endif
                </div>

                @if ($pesanan?->perlu_sablon)
                    {{-- Visual Mockup / File Desain Sesuai Pesanan --}}
                    <div class="bg-zinc-950 rounded-2xl p-5 text-center relative overflow-hidden shadow-inner border border-zinc-800">
                        @if ($pesanan?->file_desain)
                            <div class="max-w-[220px] mx-auto py-3">
                                <img src="{{ asset('storage/' . $pesanan->file_desain) }}" alt="Desain Sablon" class="max-h-48 rounded-xl object-contain mx-auto shadow-md">
                            </div>
                        @else
                            <div class="max-w-[220px] mx-auto py-5 flex flex-col items-center justify-center relative">
                                <div class="w-40 h-44 bg-[#141417] rounded-3xl border border-zinc-700/80 flex flex-col items-center justify-start p-3 relative shadow-2xl">
                                    <div class="w-14 h-5 rounded-b-full border-b-2 border-zinc-500/80 bg-zinc-900/60 mb-4"></div>
                                    <div class="w-28 border border-dashed border-zinc-600 rounded bg-zinc-900/70 p-2 text-center shadow-xs">
                                        <span class="text-[8px] font-mono text-zinc-400 block leading-tight font-bold">{{ $pesanan?->kode_gambar ?: 'Desain SPK' }}</span>
                                        <span class="text-[9px] font-serif text-white tracking-widest block mt-1 font-bold">{{ Str::limit($pesanan?->detail_desain ?: 'SABLON PESANAN', 20) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-[11px] text-zinc-400 pt-3 border-t border-zinc-800/80">
                            <span>Warna: {{ $pesanan?->warna ?: 'Sesuai SPK' }}</span>
                            <span class="font-bold text-teal-300">Tinta: {{ $pesanan?->jenis_sablon ?: 'Plastisol' }}</span>
                        </div>
                    </div>

                    {{-- Detail Instruksi Sablon Sesuai Pesanan --}}
                    <div class="space-y-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-gray-50/70 border border-gray-100 space-y-1">
                            <div class="flex items-center justify-between font-bold text-gray-900">
                                <span>Detail Desain Sablon:</span>
                                <span class="text-[10px] text-teal-700 font-mono">{{ $pesanan?->titik_sablon ?? 1 }} Titik</span>
                            </div>
                            <p class="text-[11px] text-gray-700 leading-relaxed">
                                {{ $pesanan?->detail_desain ?: 'Desain sablon sesuai file dan instruksi SPK dari Admin.' }}
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Tampilan Jika Pakaian Polos (Tanpa Sablon) --}}
                    <div class="p-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200 space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-gray-200 text-gray-500 flex items-center justify-center mx-auto">
                            ✓
                        </div>
                        <h4 class="text-xs font-bold text-gray-800">Pesanan Pakaian Polos (Tanpa Sablon)</h4>
                        <p class="text-[11px] text-gray-500 max-w-xs mx-auto">
                            Pesanan ini tidak memerlukan cetak sablon. Setelah proses pemotongan dan penjahitan selesai, pesanan langsung diteruskan ke stasiun steam.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- 6. ALUR 4 TAHAP PENGERJAAN BERURUTAN (DINAMIS DARI DATABASE TAHAPAN) --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="6" y1="3" x2="6" y2="15"/>
                            <circle cx="18" cy="6" r="3"/>
                            <circle cx="6" cy="18" r="3"/>
                            <path d="M18 9a9 9 0 0 1-9 9"/>
                        </svg>
                        <h3 class="text-sm font-black text-gray-900">Alur 4 Tahap Pengerjaan Berurutan</h3>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Alur berurutan: Pemotongan Kain → Penjahitan → Steam → Sablon & QC Selesai</p>
                </div>
                <span class="text-xs font-black text-[#0D7A68]">
                    Progres Produksi: {{ $produksi?->progress_persen ?? 0 }}% / 100%
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($tahapan as $index => $tahap)
                    @php
                        $isSedang = $tahap->status === 'sedang_dikerjakan';
                        $isSelesai = $tahap->status === 'selesai';
                    @endphp
                    <div class="p-4 rounded-xl border-2 {{ $isSedang ? 'border-teal-500 bg-[#F2FBF9] shadow-2xs' : ($isSelesai ? 'border-emerald-300 bg-emerald-50/40' : 'border-gray-200 bg-gray-50/70') }} space-y-3 relative">
                        <div class="flex items-center justify-between">
                            <div class="w-6 h-6 rounded-full {{ $isSedang ? 'bg-[#0D7A68] text-white' : ($isSelesai ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500') }} font-black text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </div>
                            @if ($isSedang)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#E6F7F5] text-[#0D7A68]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0D9488] animate-pulse"></span>
                                    Sedang Berjalan
                                </span>
                            @elseif ($isSelesai)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-200/80 text-gray-500">
                                    Menunggu Giliran
                                </span>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-xs font-black {{ $isSedang || $isSelesai ? 'text-gray-900' : 'text-gray-700' }}">{{ $tahap->label_tahap }}</h4>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">
                                @if ($tahap->nama_tahap === 'pemotongan_kain')
                                    Potong pola & bahan kain sesuai SPK
                                @elseif ($tahap->nama_tahap === 'penjahitan')
                                    Jahit & rakit potongan kain
                                @elseif ($tahap->nama_tahap === 'steam')
                                    Setrika uap & perapian bentuk
                                @elseif ($tahap->nama_tahap === 'sablon')
                                    Cetak sablon & QC akhir
                                @else
                                    Pengerjaan lantai workshop
                                @endif
                            </p>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-[10px] font-bold text-gray-500">
                                <span>Progres Tahap</span>
                                <span class="{{ $isSelesai ? 'text-emerald-700' : ($isSedang ? 'text-[#0D7A68]' : 'text-gray-400') }}">
                                    {{ $isSelesai ? '100%' : ($isSedang ? ($tahap->progress_persen ?: 50) . '%' : '0%') }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                <div class="{{ $isSelesai ? 'bg-emerald-600' : 'bg-[#0D7A68]' }} h-full rounded-full" style="width: {{ $isSelesai ? 100 : ($isSedang ? ($tahap->progress_persen ?: 50) : 0) }}%;"></div>
                            </div>
                        </div>

                        <div class="pt-2 border-t {{ $isSedang ? 'border-teal-100' : 'border-gray-200/60' }} text-[10px] text-gray-500 font-medium space-y-0.5">
                            @if ($tahap->operator)
                                <p class="flex items-center gap-1 text-gray-700 font-bold truncate">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <span>Operator: {{ $tahap->operator->name }}</span>
                                </p>
                            @endif
                            @if ($tahap->waktu_mulai)
                                <p>Mulai: {{ \Carbon\Carbon::parse($tahap->waktu_mulai)->format('H:i') }} WIB</p>
                            @endif
                            @if ($isSelesai && $tahap->waktu_selesai)
                                <p class="text-emerald-700 font-semibold">Selesai: {{ \Carbon\Carbon::parse($tahap->waktu_selesai)->format('H:i') }} WIB</p>
                            @elseif (!$isSedang && !$isSelesai)
                                <p class="text-gray-400 italic">Antrean tahap berikutnya</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 7. LOG RIWAYAT PENGERJAAN & QUICK OPERATOR INPUT --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0D7A68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 14 14"/>
                    </svg>
                    <h3 class="text-sm font-black text-gray-900">Log Riwayat Pengerjaan SPK</h3>
                </div>
                <button wire:click="$set('activeTab', 'log')" class="inline-flex items-center gap-1 text-xs font-bold text-gray-700 hover:text-black cursor-pointer px-3 py-1.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    <span>+ Tambah Catatan</span>
                </button>
            </div>

            {{-- Timeline Log Entries Dinamis --}}
            <div class="space-y-3">
                @forelse ($riwayatLog->take(3) as $log)
                    <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-gray-50/70 border border-gray-100">
                        <div class="w-8 h-8 rounded-full bg-[#E6F7F5] text-[#0D7A68] flex items-center justify-center shrink-0">
                            ✓
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span class="text-xs font-bold text-gray-900">{{ $log->aksi ?? $log->kegiatan }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                {{ $log->keterangan }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-4 rounded-xl bg-gray-50 border border-dashed border-gray-200 text-xs text-gray-400 text-center">
                        Belum ada riwayat catatan pengerjaan untuk SPK ini.
                    </div>
                @endforelse
            </div>

            {{-- Input Bar Catatan Operator --}}
            <div class="pt-2">
                <form wire:submit="kirimCatatanOperator" class="flex flex-col sm:flex-row items-center gap-2.5">
                    <div class="relative flex-1 w-full flex items-center">
                        <span class="text-xs font-bold text-gray-700 whitespace-nowrap pl-2 pr-3 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            <span>Catatan Operator:</span>
                        </span>
                        <input type="text"
                               wire:model="catatanOperator"
                               placeholder="Tuliskan catatan kondisi pengerjaan SPK..."
                               class="w-full px-4 py-2.5 text-xs rounded-xl border border-gray-200 bg-white focus:outline-hidden focus:ring-1 focus:ring-black transition text-gray-800 placeholder-gray-400 font-medium">
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-xl bg-black text-white text-xs font-bold hover:bg-zinc-800 transition shadow-xs cursor-pointer shrink-0">
                        <span>Kirim Catatan</span>
                    </button>
                </form>
            </div>
        </div>

    {{-- KONTEN TAB 2: DETAIL PESANAN & PELANGGAN --}}
    @elseif ($activeTab === 'detail')
        <div class="w-full">
            <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-4 w-full">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-black text-gray-900">Informasi Pelanggan & Pemesanan</h3>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        Status Pembayaran: {{ strtoupper($pesanan?->status_pembayaran ?? 'Lunas') }}
                    </span>
                </div>

                {{-- Grid Data Full Width --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-xs pt-1">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Instansi / Pelanggan:</span>
                        <span class="font-bold text-gray-900 text-right">{{ $pesanan?->pelanggan?->nama ?? 'Pelanggan Umum' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Nama PIC (Admin):</span>
                        <span class="font-bold text-gray-900 text-right">{{ $pesanan?->admin?->name ?? ($pesanan?->pic_pelanggan ?: 'Michel') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Nomor SPK / Pesanan:</span>
                        <span class="font-extrabold text-gray-900 text-right font-mono">{{ $pesanan?->nomor_pemesanan }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Kontak WhatsApp Admin:</span>
                        <span class="font-bold text-[#0D7A68] text-right">{{ $pesanan?->admin?->telepon ?? '0812-3456-7890' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Tanggal Pemesanan:</span>
                        <span class="font-bold text-gray-900 text-right">{{ $pesanan?->tanggal_pemesanan ? \Carbon\Carbon::parse($pesanan->tanggal_pemesanan)->format('d F Y') : now()->format('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Target Selesai Produksi:</span>
                        <span class="font-bold text-gray-900 text-right">{{ $pesanan?->tanggal_selesai_estimasi ? \Carbon\Carbon::parse($pesanan->tanggal_selesai_estimasi)->format('d F Y') : 'Sesuai SPK' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Alamat Pelanggan:</span>
                        <span class="font-bold text-gray-900 text-right">{{ $pesanan?->pelanggan?->alamat && !in_array($pesanan->pelanggan->alamat, ['Bandung, Jawa Barat']) ? $pesanan->pelanggan->alamat : 'Sesuai SPK Pemesanan' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-400 font-medium">Status Pembayaran:</span>
                        <span class="font-bold text-emerald-700 text-right">{{ ucfirst($pesanan?->status_pembayaran ?? 'Lunas') }}</span>
                    </div>
                </div>

                @if ($pesanan?->catatan)
                    <div class="pt-3 border-t border-gray-100 text-xs flex items-start gap-2 text-gray-600">
                        <span class="text-gray-400 font-medium shrink-0">Catatan Khusus:</span>
                        <span class="font-semibold text-gray-800">{{ $pesanan->catatan }}</span>
                    </div>
                @endif
            </div>
        </div>

    {{-- KONTEN TAB 3: SPESIFIKASI DESAIN & SABLON --}}
    @elseif ($activeTab === 'sablon')
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-black text-gray-900">Lembar Kerja Spesifikasi Sablon & Desain</h3>
                <span class="text-xs text-[#0D7A68] font-bold">{{ $pesanan?->titik_sablon ?? 1 }} Titik Sablon Aktif</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/60 space-y-3">
                    <div class="flex items-center justify-between font-bold text-xs text-gray-900">
                        <span>Spesifikasi Sablon Pesanan</span>
                        <span class="font-mono text-[10px] bg-white px-2 py-0.5 rounded border border-gray-200">{{ $pesanan?->kode_gambar ?: 'DSN-SPK' }}</span>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                        <li>Jenis Tinta: <strong class="text-gray-800">{{ $pesanan?->jenis_sablon ?: 'Plastisol / Rubber' }}</strong></li>
                        <li>Warna Kain: <strong class="text-gray-800">{{ $pesanan?->warna ?: 'Sesuai SPK' }}</strong></li>
                        <li>Jumlah Kaos yang Disablon: <strong class="text-gray-800">{{ $pesanan?->jumlah ?? 0 }} Pcs</strong></li>
                        <li>Detail Instruksi: <strong class="text-gray-800">{{ $pesanan?->detail_desain ?: 'Sesuai instruksi gambar SPK' }}</strong></li>
                    </ul>
                </div>

                <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/60 flex flex-col justify-between space-y-3">
                    <span class="font-bold text-xs text-gray-900">File Desain & Mockup SPK</span>
                    @if ($pesanan?->file_desain)
                        <div class="text-center py-2">
                            <img src="{{ asset('storage/' . $pesanan->file_desain) }}" alt="Desain" class="max-h-36 rounded-lg object-contain mx-auto shadow-xs">
                        </div>
                    @else
                        <div class="p-6 text-center text-xs text-gray-400 italic">
                            File vektor/desain diunggah langsung ke lembar kerja SPK fisik atau cetak pracetak.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    {{-- KONTEN TAB 4: LOG RIWAYAT PENGERJAAN FULL --}}
    @elseif ($activeTab === 'log')
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-xs space-y-6">
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3">Riwayat Catatan Operator & Log Aktivitas SPK</h3>
            <div class="space-y-3">
                @forelse ($riwayatLog as $log)
                    <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/60 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#E6F7F5] text-[#0D7A68] flex items-center justify-center shrink-0 font-bold text-xs">
                            ✓
                        </div>
                        <div class="min-w-0 flex-1 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-gray-900">{{ $log->aksi ?? $log->kegiatan }}</span>
                                <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                            <p class="text-gray-600 mt-1">{{ $log->keterangan }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 italic text-center py-4">Belum ada catatan aktivitas tambahan.</p>
                @endforelse
            </div>
        </div>
    @endif

    {{-- MODAL LAPORKAN KENDALA --}}
    @if ($showModalKendala)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-gray-200">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2 text-rose-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <h3 class="text-sm font-black text-gray-900">Laporkan Kendala Produksi</h3>
                    </div>
                    <button wire:click="$set('showModalKendala', false)" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Deskripsi Kendala di Meja Kerja / Mesin:</label>
                    <textarea wire:model="kendalaLaporan"
                              rows="3"
                              placeholder="Misal: Pisau potong tumpul, stok kain reject, benang kurang..."
                              class="w-full text-xs p-3 rounded-xl border border-gray-200 focus:outline-hidden focus:ring-1 focus:ring-black text-gray-800"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button wire:click="$set('showModalKendala', false)" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button wire:click="laporkanKendala" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl cursor-pointer shadow-xs">
                        Kirim Laporan Kendala
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
