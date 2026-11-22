<div class="space-y-6">
    {{-- ALERT NOTIFIKASI SUKSES / ERROR --}}
    @if($pesanSukses)
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 p-4 shadow-xs flex items-center justify-between gap-3 text-emerald-900 transition-all">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white font-bold">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold">{{ $pesanSukses }}</p>
                    <p class="text-[11px] text-emerald-700">Perubahan telah tersimpan dan tercatat di Audit Log sistem.</p>
                </div>
            </div>
            <button type="button" wire:click="$set('pesanSukses', '')" class="text-emerald-500 hover:text-emerald-700 p-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if($pesanError)
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 p-4 shadow-xs flex items-center justify-between gap-3 text-rose-900 transition-all">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white font-bold">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-xs font-bold">{{ $pesanError }}</p>
            </div>
            <button type="button" wire:click="$set('pesanError', '')" class="text-rose-500 hover:text-rose-700 p-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- HEADER HALAMAN & AKSI UTAMA --}}
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <span>Tata Kelola</span>
                <span class="text-slate-300">›</span>
                <span class="text-teal-600 font-extrabold">Manajemen Pengguna</span>
            </div>
            <h1 class="mt-1.5 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">Manajemen Pengguna &amp; Akses</h1>
            <p class="mt-1 max-w-3xl text-xs sm:text-sm leading-relaxed text-gray-500">
                Kelola hak akses pengguna, pendaftaran akun staf administrator, serta operator divisi workshop (pemotongan kain, sablon, jahit, steam) secara terpusat.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            @if($search !== '' || $filterRole !== 'semua')
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-gray-900"
                >
                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset Filter</span>
                </button>
            @endif

            <button
                type="button"
                wire:click="exportCsv"
                wire:loading.attr="disabled"
                wire:target="exportCsv"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-black disabled:cursor-wait"
            >
                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span wire:loading.remove wire:target="exportCsv">Ekspor CSV</span>
                <span wire:loading wire:target="exportCsv">Mengunduh...</span>
            </button>

            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-2 rounded-xl bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Pengguna Baru</span>
            </button>
        </div>
    </div>

    {{-- KARTU RINGKASAN METRIK PENGGUNA --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Pengguna --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Pengguna</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900">{{ number_format($totalPengguna, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">akun aktif</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Terdaftar di sistem konveksi</p>
        </div>

        {{-- Administrator --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Administrator</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600">{{ number_format($totalAdmin, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">pengelola penuh</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Akses modul data & finansial</p>
        </div>

        {{-- Operator Workshop --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Operator Workshop</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-amber-600">{{ number_format($totalWorkshop, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">staf produksi</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Pola, sablon, jahit & steam</p>
        </div>

        {{-- Akun Baru --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm transition hover:border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Akun Baru</span>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-blue-600">{{ number_format($totalTerbaru, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold text-gray-400">dalam 30 hari</span>
            </div>
            <p class="mt-1 text-[11px] text-gray-500">Pertambahan personel konveksi</p>
        </div>
    </div>

    {{-- FILTER & PENCARIAN --}}
    <div class="rounded-2xl border border-gray-200/80 bg-white p-4 sm:p-5 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            {{-- Input Search --}}
            <div class="md:col-span-6 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.350ms="search"
                    placeholder="Cari nama pengguna, username, email, atau no. telepon..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-9 text-xs font-medium text-gray-800 placeholder-gray-400 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                @if($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Dropdown Role --}}
            <div class="md:col-span-4">
                <select
                    wire:model.live="filterRole"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="semua">Semua Peran / Divisi</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Per Page --}}
            <div class="md:col-span-2">
                <select
                    wire:model.live="perPage"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-100"
                >
                    <option value="10">10 Baris</option>
                    <option value="20">20 Baris</option>
                    <option value="50">50 Baris</option>
                </select>
            </div>
        </div>

        {{-- Tabs Role Cepat --}}
        <div class="flex flex-wrap items-center gap-1.5 pt-1 border-t border-gray-100">
            <span class="text-[11px] font-bold text-gray-400 mr-1.5 uppercase tracking-wide">Pilih Divisi:</span>
            <button
                type="button"
                wire:click="$set('filterRole', 'semua')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ $filterRole === 'semua' ? 'bg-black text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
            >
                Semua
            </button>
            <button
                type="button"
                wire:click="$set('filterRole', 'admin')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterRole === 'admin' ? 'bg-teal-600 text-white shadow-sm' : 'bg-teal-50 text-teal-700 hover:bg-teal-100' }}"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                Administrator
            </button>
            <button
                type="button"
                wire:click="$set('filterRole', 'pemotongan_kain')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterRole === 'pemotongan_kain' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                Pemotongan Kain
            </button>
            <button
                type="button"
                wire:click="$set('filterRole', 'sablon')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterRole === 'sablon' ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                Sablon
            </button>
            <button
                type="button"
                wire:click="$set('filterRole', 'penjahitan')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterRole === 'penjahitan' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                Penjahitan (Sewing)
            </button>
            <button
                type="button"
                wire:click="$set('filterRole', 'steam')"
                class="rounded-xl px-3 py-1.5 text-xs font-bold transition flex items-center gap-1.5 {{ $filterRole === 'steam' ? 'bg-purple-600 text-white shadow-sm' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                Steam & Finishing
            </button>
        </div>
    </div>

    {{-- TABEL PENGGUNA --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/75 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 pl-5 pr-3">Profil Pengguna</th>
                        <th class="py-3.5 px-3">Kontak &amp; Email</th>
                        <th class="py-3.5 px-3">Peran / Hak Akses</th>
                        <th class="py-3.5 px-3">Bergabung</th>
                        <th class="py-3.5 pl-3 pr-5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($users as $user)
                        @php
                            $roleClass = match($user->role) {
                                'admin'             => 'bg-teal-50 text-teal-800 border-teal-200',
                                'pemotongan_kain'   => 'bg-blue-50 text-blue-800 border-blue-200',
                                'sablon'            => 'bg-amber-50 text-amber-800 border-amber-200',
                                'penjahitan'        => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                'steam'             => 'bg-purple-50 text-purple-800 border-purple-200',
                                default             => 'bg-slate-50 text-slate-800 border-slate-200',
                            };
                            $isCurrentUser = auth()->id() === $user->id;
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition group">
                            {{-- Profil Pengguna --}}
                            <td class="py-4 pl-5 pr-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white font-extrabold text-xs shadow-2xs">
                                        {{ $user->initials() }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-gray-900 truncate max-w-[200px]">{{ $user->name }}</p>
                                            @if($isCurrentUser)
                                                <span class="rounded-md bg-teal-100 text-teal-800 text-[10px] font-black px-1.5 py-0.5">Akun Anda</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-400 font-mono mt-0.5">
                                            {{ $user->username ? '@'.$user->username : 'ID: #'.$user->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kontak & Email --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                <div class="font-semibold text-gray-900">
                                    {{ $user->email }}
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                                    @if($user->telepon)
                                        <svg class="h-3 w-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                                        </svg>
                                        <span>{{ $user->telepon }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Telepon belum diisi</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-extrabold {{ $roleClass }}">
                                    <span>{{ $roles[$user->role] ?? $user->role }}</span>
                                </span>
                            </td>

                            {{-- Bergabung --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                <p class="font-semibold text-gray-800">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</p>
                                <p class="text-[11px] text-gray-400">{{ $user->created_at ? $user->created_at->diffForHumans() : '' }}</p>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 pl-3 pr-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $user->id }})"
                                        class="inline-flex items-center gap-1 rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 shadow-2xs transition hover:bg-gray-100 hover:text-black"
                                    >
                                        <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
                                    </button>

                                    @if(!$isCurrentUser)
                                        <button
                                            type="button"
                                            wire:click="confirmDelete({{ $user->id }})"
                                            class="inline-flex items-center gap-1 rounded-xl border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-bold text-rose-700 shadow-2xs transition hover:bg-rose-100 hover:text-rose-900"
                                        >
                                            <svg class="h-3.5 w-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-50 text-gray-400 border border-gray-100">
                                    <svg class="h-7 w-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="mt-3 text-sm font-bold text-gray-900">Pengguna Tidak Ditemukan</h3>
                                <p class="mt-1 text-xs text-gray-500 max-w-sm mx-auto">
                                    Tidak ada data pengguna yang sesuai dengan kata kunci pencarian atau filter peran yang dipilih.
                                </p>
                                @if($search !== '' || $filterRole !== 'semua')
                                    <button
                                        type="button"
                                        wire:click="resetFilters"
                                        class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-black px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-gray-800 transition"
                                    >
                                        <span>Reset Semua Filter</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINASI --}}
        @if($users->hasPages() || $users->total() > 0)
            <div class="border-t border-gray-200 bg-gray-50/50 px-5 py-3.5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
                <div>
                    Menampilkan <span class="font-bold text-gray-900">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-900">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $users->total() }}</span> akun pengguna
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL FORM (TAMBAH / EDIT PENGGUNA) --}}
    @if($showModalForm)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity"
            x-data
            @keydown.escape.window="$wire.closeModalForm()"
        >
            <div
                class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-gray-200 overflow-hidden transform transition-all"
                @click.outside="$wire.closeModalForm()"
            >
                {{-- Header Modal --}}
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-6 py-4">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-700 font-bold border border-teal-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">
                                {{ $isEditMode ? 'Perbarui Data Pengguna' : 'Tambah Pengguna Baru' }}
                            </h3>
                            <p class="text-[11px] text-gray-500">
                                {{ $isEditMode ? 'Ubah informasi profil atau hak akses staf' : 'Daftarkan personel baru ke dalam sistem konveksi' }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModalForm"
                        class="rounded-xl p-1.5 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form Body --}}
                <form wire:submit="simpan">
                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                placeholder="Contoh: Budi Santoso"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                            >
                            @error('name')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Username & Nomor WhatsApp (2 Kolom) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">
                                    Username <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="username"
                                    placeholder="Contoh: budi_produksi"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                                >
                                @error('username')
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">
                                    Nomor WhatsApp <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="telepon"
                                    placeholder="Contoh: 081234567890"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                                >
                                @error('telepon')
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="email"
                                wire:model="email"
                                placeholder="Contoh: staf@cotton.id"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                            >
                            @error('email')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Peran / Hak Akses --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                Peran / Divisi Akses <span class="text-rose-500">*</span>
                            </label>
                            <select
                                wire:model="role"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs font-bold text-gray-800 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                            >
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password & Konfirmasi Password --}}
                        <div class="border-t border-gray-100 pt-3 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">
                                    {{ $isEditMode ? 'Ganti Sandi (Opsional)' : 'Pengaturan Sandi Akun' }}
                                </span>
                                @if($isEditMode)
                                    <span class="text-[10px] text-gray-400">Kosongkan bila tidak diganti</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">
                                        Kata Sandi {{ $isEditMode ? '' : '*' }}
                                    </label>
                                    <input
                                        type="password"
                                        wire:model="password"
                                        placeholder="Minimal 6 karakter"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                                    >
                                    @error('password')
                                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">
                                        Konfirmasi Kata Sandi
                                    </label>
                                    <input
                                        type="password"
                                        wire:model="password_confirmation"
                                        placeholder="Ulangi sandi"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs text-gray-800 transition focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-100"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="border-t border-gray-100 bg-gray-50/80 px-6 py-4 flex items-center justify-end gap-2.5">
                        <button
                            type="button"
                            wire:click="closeModalForm"
                            class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-100 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="rounded-xl bg-black px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-gray-800 disabled:opacity-60 transition"
                        >
                            <span wire:loading.remove>{{ $isEditMode ? 'Simpan Perubahan' : 'Daftarkan Pengguna' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL KONFIRMASI HAPUS --}}
    @if($showModalDelete)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity"
            x-data
            @keydown.escape.window="$wire.closeModalDelete()"
        >
            <div
                class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-gray-200 overflow-hidden transform transition-all"
                @click.outside="$wire.closeModalDelete()"
            >
                <div class="p-6 text-center space-y-3">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-base font-extrabold text-gray-900">Konfirmasi Hapus Akun</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Apakah Anda yakin ingin menghapus akun pengguna <strong class="text-gray-900 font-bold">{{ $userToDeleteName }}</strong>?
                        Setelah dihapus, akun ini tidak lagi dapat masuk ke dalam sistem konveksi.
                    </p>
                </div>

                <div class="border-t border-gray-100 bg-gray-50/80 px-6 py-3.5 flex items-center justify-center gap-2.5">
                    <button
                        type="button"
                        wire:click="closeModalDelete"
                        class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-100 transition"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="hapusUser"
                        wire:loading.attr="disabled"
                        class="rounded-xl bg-rose-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-rose-700 disabled:opacity-60 transition"
                    >
                        <span wire:loading.remove>Ya, Hapus Akun</span>
                        <span wire:loading>Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

