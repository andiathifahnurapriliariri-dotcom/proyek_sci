<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran {{ $pembayaran->kode_pembayaran }} - COTTON.id</title>
    <link rel="icon" href="{{ asset('images/logo-cotton.jpg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .struk-card {
                border: 1px solid #e5e7eb !important;
                box-shadow: none !important;
                max-width: 100% !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen py-8 px-4 sm:px-6">

    {{-- Top Action Bar (Sembunyi saat cetak) --}}
    <div class="max-w-2xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-900 bg-white px-4 py-2.5 rounded-xl border border-gray-200 shadow-2xs hover:bg-gray-50 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            <span>Kembali ke Pembayaran</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                <span>Cetak Struk Kuitansi</span>
            </button>
        </div>
    </div>

    @php
        $pesanan = $pembayaran->pemesanan;
        $pelanggan = $pesanan?->pelanggan;
        $totalHarga = (float) ($pesanan?->total_harga ?? 0);
        $totalDiterima = (float) ($pesanan?->pembayaran()->where('status_konfirmasi', 'Diterima')->sum('jumlah_bayar') ?? 0);
        $sisaTagihan = max(0, $totalHarga - $totalDiterima);
        $terbilangTeks = \App\Livewire\Admin\ManajemenPembayaran::terbilang((int) $pembayaran->jumlah_bayar);
    @endphp

    {{-- KARTU STRUK / KUITANSI RESMI --}}
    <div class="struk-card max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200/90 shadow-lg p-6 sm:p-8 relative overflow-hidden">
        
        {{-- Watermark LUNAS / SAH di latar belakang --}}
        <div class="pointer-events-none absolute -right-6 -bottom-6 select-none opacity-[0.04]">
            <img src="{{ asset('images/logo-cotton.jpg') }}" alt="Watermark" class="w-72 h-72 object-cover rounded-full">
        </div>

        {{-- 1. Header Struk & Identitas Konveksi --}}
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-5 border-b-2 border-dashed border-gray-200">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200 p-0.5 bg-black shrink-0">
                    <img src="{{ asset('images/logo-cotton.jpg') }}" alt="COTTON.id" class="w-full h-full object-cover rounded-full">
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tight text-gray-900 leading-tight">COTTON.id Konveksi</h1>
                    <p class="text-xs font-semibold text-teal-700">Workshop & Apparel Custom</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Jl. Pemuda No. 45, Semarang · Telp: (024) 876-5432 · WA: 0812-9988-1234</p>
                </div>
            </div>

            <div class="text-left sm:text-right shrink-0">
                <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200">
                    Kuitansi Kas Masuk
                </span>
                <p class="font-mono text-sm font-bold text-gray-900 mt-1.5">{{ $pembayaran->kode_pembayaran }}</p>
                <p class="text-[11px] text-gray-500">{{ $pembayaran->tanggal_bayar?->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        {{-- 2. Data Pelanggan & Pesanan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-5 text-xs border-b border-gray-100">
            <div class="space-y-2">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Telah Diterima Dari:</span>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $pelanggan?->nama ?? 'Pelanggan Umum' }}</p>
                    @if($pelanggan?->telepon)
                        <p class="text-gray-500 font-medium">{{ $pelanggan->telepon }}</p>
                    @endif
                    @if($pelanggan?->alamat)
                        <p class="text-gray-400 text-[11px] mt-0.5 leading-snug">{{ $pelanggan->alamat }}</p>
                    @endif
                </div>

                <div class="pt-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Untuk Pembayaran:</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span @class([
                            'inline-flex px-2 py-0.5 rounded text-[10px] font-bold',
                            'bg-teal-50 text-teal-800 border border-teal-200' => $pembayaran->jenis_pembayaran === 'DP',
                            'bg-indigo-50 text-indigo-700 border border-indigo-200' => $pembayaran->jenis_pembayaran === 'Pelunasan',
                        ])>
                            {{ $pembayaran->jenis_pembayaran === 'DP' ? 'Uang Muka (DP)' : 'Pelunasan Tagihan' }}
                        </span>
                        <span class="font-semibold text-gray-700">Pesanan {{ $pesanan?->nomor_pemesanan ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2 sm:border-l sm:border-gray-100 sm:pl-4">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Rincian SPK / Pesanan:</span>
                    <p class="font-bold text-gray-800 mt-0.5">{{ $pesanan?->jenis_kain ?? 'Apparel Konveksi' }}</p>
                    <p class="text-gray-500">Jumlah: <strong class="text-gray-900">{{ $pesanan?->jumlah ?? 0 }} pcs</strong></p>
                </div>

                <div class="pt-1 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Metode Pembayaran:</span>
                        <p class="font-bold text-gray-800 mt-0.5">{{ $pembayaran->metode_pembayaran }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Status Transaksi:</span>
                        @if($pembayaran->status_konfirmasi === 'Diterima')
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Diterima Sah
                            </span>
                        @elseif($pembayaran->status_konfirmasi === 'Ditolak')
                            <span class="text-[11px] font-bold text-rose-600 mt-0.5">Ditolak</span>
                        @else
                            <span class="text-[11px] font-bold text-amber-600 mt-0.5">Menunggu Verifikasi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Box Nominal Uang Diterima & Terbilang --}}
        <div class="my-5 p-4 sm:p-5 rounded-2xl bg-teal-50/70 border border-teal-200/80">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2">
                <span class="text-[11px] font-bold text-teal-800 uppercase tracking-wider">Jumlah Pembayaran Diterima:</span>
                <span class="text-2xl sm:text-3xl font-black text-teal-900">
                    Rp {{ number_format((float) $pembayaran->jumlah_bayar, 0, ',', '.') }}
                </span>
            </div>
            <div class="mt-2.5 pt-2 border-t border-teal-200/50">
                <p class="text-xs text-teal-800 italic leading-relaxed font-medium">
                    <span class="font-bold not-italic text-[10px] text-teal-600 uppercase">Terbilang:</span>
                    "{{ $terbilangTeks }} Rupiah"
                </p>
            </div>
        </div>

        {{-- 4. Ringkasan Status Tagihan Pesanan --}}
        <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 text-xs space-y-2">
            <div class="flex items-center justify-between text-gray-600">
                <span>Total Nilai Pesanan (SPK):</span>
                <span class="font-bold text-gray-900">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between text-gray-600">
                <span>Total Diterima (Kumulatif):</span>
                <span class="font-bold text-teal-700">Rp {{ number_format($totalDiterima, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-gray-200/60 font-bold">
                <span class="text-gray-700">Sisa Tagihan Pesanan:</span>
                @if($sisaTagihan <= 0)
                    <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-black text-xs border border-emerald-200">
                        ✓ LUNAS
                    </span>
                @else
                    <span class="text-rose-600 font-black">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                @endif
            </div>
        </div>

        {{-- Catatan (jika ada) --}}
        @if($pembayaran->catatan)
            <div class="mt-4 p-3 rounded-xl bg-amber-50/60 border border-amber-200/60 text-xs text-amber-900">
                <span class="font-bold text-[10px] text-amber-700 uppercase tracking-wider block">Catatan Transaksi:</span>
                <p class="mt-0.5 leading-relaxed">{{ $pembayaran->catatan }}</p>
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
                <p class="text-[11px] text-gray-500">Semarang, {{ $pembayaran->tanggal_bayar?->translatedFormat('d F Y') }}</p>
                <p class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-wider">Kasir / Penerima,</p>
                <div class="h-12 flex items-center justify-center sm:justify-end">
                    <span class="text-xs font-serif italic text-teal-800 font-bold border-b border-gray-300 pb-0.5">
                        {{ $pembayaran->admin?->name ?? 'Admin Workshop' }}
                    </span>
                </div>
                <p class="text-[10px] text-gray-400 font-mono">ID Admin: #{{ $pembayaran->user_id ?? 1 }}</p>
            </div>
        </div>

    </div>

</body>
</html>
