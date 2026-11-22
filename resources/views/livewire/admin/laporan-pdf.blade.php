<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja &amp; Penjualan {{ $labelPeriode }}</title>
    <style>
        @page { margin: 28px 30px; }
        body { color: #172033; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h1 { font-size: 21px; margin: 0 0 5px; }
        h2 { font-size: 12px; margin: 0 0 5px; }
        p { color: #64748b; margin: 0; }
        .header { border-bottom: 2px solid #0f766e; margin-bottom: 16px; padding-bottom: 11px; }
        .brand { color: #0f766e; font-size: 10px; font-weight: bold; letter-spacing: 1px; margin-bottom: 8px; }
        .meta { margin-top: 6px; }
        .cards { border-collapse: separate; border-spacing: 8px 0; margin: 0 -8px 15px; table-layout: fixed; width: 100%; }
        .card { background: #f1f5f9; border-radius: 6px; padding: 11px; vertical-align: top; }
        .label { color: #64748b; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .value { color: #111827; font-size: 18px; font-weight: bold; margin: 7px 0 4px; }
        .subvalue { color: #475569; font-size: 8px; }
        .section { margin-top: 14px; }
        .section-head { margin-bottom: 7px; }
        table.data { border-collapse: collapse; width: 100%; }
        table.data th { background: #f1f5f9; color: #475569; font-size: 8px; padding: 8px 7px; text-align: left; text-transform: uppercase; }
        table.data td { border-bottom: 1px solid #e2e8f0; padding: 8px 7px; }
        table.data tfoot td { background: #e2e8f0; border: 0; font-weight: bold; }
        .right { text-align: right !important; }
        .muted { color: #64748b; }
        .two-col { border-collapse: separate; border-spacing: 8px 0; margin: 0 -8px; table-layout: fixed; width: 100%; }
        .two-col td { padding: 0; vertical-align: top; width: 50%; }
        .panel { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 11px; }
        .station { border-bottom: 1px solid #e2e8f0; padding: 6px 0; }
        .footer { border-top: 1px solid #e2e8f0; color: #64748b; font-size: 7px; margin-top: 15px; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">COTTON.ID · LAPORAN KINERJA BULANAN</div>
        <h1>Laporan Kinerja &amp; Penjualan</h1>
        <p>Ringkasan pendapatan, hasil produksi, persediaan, dan pemantauan stok.</p>
        <p class="meta"><strong>Periode:</strong> {{ $labelPeriode }} &nbsp; · &nbsp; <strong>Dibuat:</strong> {{ $dibuatPada }}</p>
    </div>

    <table class="cards">
        <tr>
            <td class="card">
                <div class="label">Penjualan &amp; pendapatan</div>
                <div class="value">Rp {{ number_format($nilaiPenjualan, 0, ',', '.') }}</div>
                <div class="subvalue">{{ number_format($jumlahPesanan) }} pesanan · Rata-rata Rp {{ number_format($rataRataPerPesanan, 0, ',', '.') }}</div>
            </td>
            <td class="card">
                <div class="label">Hasil baju jadi</div>
                <div class="value">{{ number_format($jumlahPcs) }} pcs</div>
                <div class="subvalue">{{ number_format($rataRataHariKerja, 1, ',', '.') }} hari kerja rata-rata · Pesanan selesai dan lunas</div>
            </td>
            <td class="card">
                <div class="label">Kepatuhan ROP</div>
                <div class="value">{{ number_format($persentaseRopAman, 1, ',', '.') }}%</div>
                <div class="subvalue">{{ number_format($skuAman) }} dari {{ number_format($totalSku) }} SKU aman · {{ number_format($skuPerluPerhatian) }} perlu perhatian</div>
            </td>
            <td class="card">
                <div class="label">Ketepatan waktu</div>
                <div class="value">{{ number_format($persentaseTepatWaktu, 1, ',', '.') }}%</div>
                <div class="subvalue">{{ number_format($jumlahTepatWaktu) }} dari {{ number_format($jumlahDenganEstimasi) }} pesanan dengan estimasi</div>
            </td>
        </tr>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <div class="panel">
                    <h2>Distribusi Output Produksi</h2>
                    <p class="section-head">Berdasarkan jumlah pcs produk yang selesai dikerjakan.</p>
                    @forelse($distribusiOutput as $kategori)
                        <div class="station">
                            <strong>{{ $kategori['nama'] }}</strong>
                            <span class="right" style="float: right">{{ number_format($kategori['jumlah_pcs']) }} pcs ({{ number_format($kategori['porsi_output'], 1, ',', '.') }}%)</span>
                        </div>
                    @empty
                        <p>Belum ada hasil produksi pada periode ini.</p>
                    @endforelse
                </div>
            </td>
            <td>
                <div class="panel">
                    <h2>Aktivitas Workshop</h2>
                    <p class="section-head">Catatan penyelesaian aktivitas per stasiun kerja.</p>
                    <div class="station">Pemotongan kain <span class="right" style="float: right">{{ number_format($aktivitasWorkshop['pemotongan']) }} aktivitas</span></div>
                    <div class="station">Sablon &amp; bordir <span class="right" style="float: right">{{ number_format($aktivitasWorkshop['sablon']) }} aktivitas</span></div>
                    <div class="station">Penjahitan <span class="right" style="float: right">{{ number_format($aktivitasWorkshop['penjahitan']) }} aktivitas</span></div>
                    <div class="station">Steam &amp; finishing <span class="right" style="float: right">{{ number_format($aktivitasWorkshop['steam']) }} aktivitas</span></div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2>Rincian Hasil Pengerjaan Produk</h2>
        <p class="section-head">Pesanan yang telah selesai diproduksi dan pembayaran lunas, dikelompokkan berdasarkan kategori.</p>
        <table class="data">
            <thead>
                <tr>
                    <th>Kategori pakaian</th>
                    <th class="right">Jumlah pesanan</th>
                    <th class="right">Baju jadi (pcs)</th>
                    <th class="right">Nilai penjualan</th>
                    <th class="right">Efisiensi waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rincianKategori as $kategori)
                    <tr>
                        <td>{{ $kategori['nama'] }}</td>
                        <td class="right">{{ number_format($kategori['jumlah_pesanan']) }}</td>
                        <td class="right">{{ number_format($kategori['jumlah_pcs']) }}</td>
                        <td class="right">Rp {{ number_format($kategori['nilai_penjualan'], 0, ',', '.') }}</td>
                        <td class="right">{{ number_format($kategori['efisiensi_waktu']) }}%</td>
                    </tr>
                @empty
                    <tr><td class="muted" colspan="5">Tidak ada pesanan yang memenuhi kriteria pada periode ini.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td>Total rekapitulasi</td>
                    <td class="right">{{ number_format($jumlahPesanan) }}</td>
                    <td class="right">{{ number_format($jumlahPcs) }}</td>
                    <td class="right">Rp {{ number_format($nilaiPenjualan, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($persentaseTepatWaktu, 1, ',', '.') }}%</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        Catatan: Periode laporan mengikuti tanggal selesai produksi. Pesanan yang direkap harus berstatus selesai dan lunas. Persentase ROP menggunakan snapshot persediaan terkini, bukan rekaman historis. Dokumen dibuat otomatis oleh COTTON.id pada {{ $dibuatPada }}.
    </div>
</body>
</html>
