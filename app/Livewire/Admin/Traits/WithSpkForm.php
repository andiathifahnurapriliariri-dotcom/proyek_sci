<?php

namespace App\Livewire\Admin\Traits;

use App\Models\AktivitasLog;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\PemesananVariasi;
use App\Models\Produk;
use App\Models\Produksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use RuntimeException;
use Throwable;

trait WithSpkForm
{
    public const DAFTAR_PRODUK = [
        'Kaos 30s',
        'Kaos 24s',
        'Jersey',
        'Hoodie',
    ];

    public const KATEGORI_UKURAN = ['Dewasa', 'Anak-anak'];

    public const UKURAN_DEWASA = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL', '5XL'];

    public const UKURAN_ANAK = ['2', '4', '6', '8', '10', '12', '14'];

    public const WARNA_MASTER = [
        'Hitam Pekat',
        'Putih Bersih',
        'Biru Dongker',
        'Merah Marun',
        'Hijau Army',
        'Abu Misty',
        'Kuning Mustard',
        'Cokelat Kopi',
        'Biru Benhur',
        'Sage Green',
    ];

    // Status Modal
    public bool $showModalCreate = false;

    public bool $showModalPesanan = false;

    public ?int $editPesananId = null;

    // Field Form SPK
    public string $nama_pelanggan = '';

    public string $nomor_telepon = '';

    public string $alamat = '';

    public string $jenis_pakaian = 'Kaos 30s';

    public string $warna = '';

    public string $ukuran_baju = '';

    public array $variasi = [];

    public string $pesanErrorVariasi = '';

    public ?array $infoDuplikat = null;

    // Ringkasan Pesanan Otomatis
    public int $totalJenisProduk = 0;

    public int $totalVariasi = 0;

    public int $totalPakaianPcs = 0;

    public int $totalHargaPesanan = 0;

    public int|string|null $jumlah_pcs = null;

    public int|string|null $harga_satuan = null;

    public string $tanggal_deadline = '';

    public string $catatan_tambahan = '';

    public string $status_pembayaran_awal = 'dp'; // 'belum_bayar', 'dp', 'lunas'

    public ?TemporaryUploadedFile $file_desain = null;

    public string $pesanSukses = '';

    #[On('buka-modal-pesanan')]
    public function bukaModalCreate(): void
    {
        $this->resetValidation();
        $this->resetFormSpk();
        $this->showModalCreate = true;
        $this->showModalPesanan = true;
    }

    public function bukaModalPesanan(): void
    {
        $this->bukaModalCreate();
    }

    public function tutupModalCreate(): void
    {
        $this->showModalCreate = false;
        $this->showModalPesanan = false;
    }

    public function tutupModalPesanan(): void
    {
        $this->tutupModalCreate();
    }

    public function buatBarisVariasiKosong(string $kategori = 'Dewasa'): array
    {
        $produkDefault = self::DAFTAR_PRODUK[0] ?? 'Kaos 30s';
        $kategoriDefault = in_array($kategori, self::KATEGORI_UKURAN, true) ? $kategori : 'Dewasa';
        $warnaDefault = self::WARNA_MASTER[0] ?? 'Hitam Pekat';
        $opsiUkuran = $this->getUkuranOptionsByKategori($kategoriDefault);

        $ukuranPilihan = $opsiUkuran[0] ?? ($kategoriDefault === 'Anak-anak' ? '10' : 'M');
        foreach ($opsiUkuran as $u) {
            $sudahAda = false;
            foreach ($this->variasi as $item) {
                if (($item['produk'] ?? '') === $produkDefault &&
                    ($item['kategori'] ?? '') === $kategoriDefault &&
                    ($item['warna'] ?? '') === $warnaDefault &&
                    ($item['ukuran'] ?? '') === $u) {
                    $sudahAda = true;
                    break;
                }
            }
            if (! $sudahAda) {
                $ukuranPilihan = $u;
                break;
            }
        }

        $hargaSatuan = $this->hitungHargaSatuan($produkDefault, $kategoriDefault, $ukuranPilihan);

        return [
            'produk' => $produkDefault,
            'kategori' => $kategoriDefault,
            'warna' => $warnaDefault,
            'ukuran' => $ukuranPilihan,
            'jumlah' => '',
            'harga_satuan' => $hargaSatuan,
            'subtotal' => 0,
        ];
    }

    public function mountWithSpkForm(): void
    {
        if (empty($this->variasi)) {
            $this->resetFormSpk();
        }
    }

    public function resetForm(): void
    {
        $this->resetFormSpk();
    }

    public function resetFormSpk(): void
    {
        $this->editPesananId = null;
        $this->nama_pelanggan = '';
        $this->nomor_telepon = '';
        $this->alamat = '';
        $this->jenis_pakaian = 'Kaos 30s';
        $this->warna = '';
        $this->ukuran_baju = '';
        $this->jumlah_pcs = null;
        $this->harga_satuan = 60000;
        $this->tanggal_deadline = '';
        $this->catatan_tambahan = '';
        $this->status_pembayaran_awal = 'dp';
        $this->file_desain = null;
        $this->pesanErrorVariasi = '';
        $this->infoDuplikat = null;

        $this->variasi = [
            $this->buatBarisVariasiKosong('Dewasa'),
        ];

        $this->hitungTotalDanHarga();
    }

    public function bukaModalEdit(int $id): void
    {
        $this->editPesanan($id);
    }

    public function editPesanan(int $id): void
    {
        $this->resetValidation();
        $this->infoDuplikat = null;
        $this->pesanErrorVariasi = '';

        $pesanan = Pemesanan::with(['pelanggan', 'variasi'])->findOrFail($id);

        $this->editPesananId = $pesanan->id;
        $this->nama_pelanggan = $pesanan->pelanggan?->nama ?? '';
        $this->nomor_telepon = $pesanan->pelanggan?->nomor_telepon ?? '';
        $this->alamat = $pesanan->pelanggan?->alamat ?? '';
        $this->jenis_pakaian = $pesanan->jenis_kain ?? 'Kaos 30s';
        $this->tanggal_deadline = $pesanan->tanggal_selesai_estimasi?->toDateString() ?? '';
        $this->catatan_tambahan = $pesanan->catatan ?? $pesanan->detail_desain ?? '';
        $this->status_pembayaran_awal = $pesanan->status_pembayaran ?? 'dp';
        $this->file_desain = null;

        $variasiDb = $pesanan->variasi;
        if ($variasiDb->isNotEmpty()) {
            $this->variasi = $variasiDb->map(function ($v) {
                return [
                    'id' => $v->id,
                    'produk' => $v->nama_produk,
                    'kategori' => $v->kategori_ukuran,
                    'warna' => $v->warna,
                    'ukuran' => $v->ukuran,
                    'jumlah' => $v->jumlah,
                    'harga_satuan' => (int) $v->harga_satuan,
                    'subtotal' => (int) $v->subtotal,
                ];
            })->toArray();
        } else {
            $this->variasi = [
                $this->buatBarisVariasiKosong('Dewasa'),
            ];
        }

        $this->hitungTotalDanHarga();

        if (property_exists($this, 'showModalDetail')) {
            $this->showModalDetail = false;
        }

        $this->showModalCreate = true;
        $this->showModalPesanan = true;
    }

    public function getUkuranOptionsByKategori(string $kategori): array
    {
        return $kategori === 'Anak-anak' ? self::UKURAN_ANAK : self::UKURAN_DEWASA;
    }

    public function hitungHargaSatuan(string $produk, string $kategori, string $ukuran): int
    {
        $cleanProduk = trim($produk);
        $basePrice = 0;

        if (Str::contains($cleanProduk, ['30s', '30 s'], true)) {
            $basePrice = ($kategori === 'Anak-anak') ? 50000 : 60000;
        } elseif (Str::contains($cleanProduk, ['24s', '24 s'], true)) {
            $basePrice = ($kategori === 'Anak-anak') ? 50000 : 60000;
        } elseif (Str::contains($cleanProduk, 'Jersey', true)) {
            $basePrice = ($kategori === 'Anak-anak') ? 50000 : 60000;
        } elseif (Str::contains($cleanProduk, 'Hoodie', true)) {
            $basePrice = ($kategori === 'Anak-anak') ? 50000 : 60000;
        } else {
            $basePrice = ($kategori === 'Anak-anak') ? 50000 : 60000;
        }

        $surcharge = 0;
        if ($kategori === 'Dewasa') {
            $u = strtoupper(trim($ukuran));
            if ($u === 'XXL' || $u === '2XL') {
                $surcharge = 5000;
            } elseif ($u === 'XXXL' || $u === '3XL') {
                $surcharge = 10000;
            } elseif ($u === '4XL' || $u === 'XXXXL') {
                $surcharge = 15000;
            } elseif ($u === '5XL' || $u === 'XXXXXL') {
                $surcharge = 20000;
            }
        }

        return $basePrice + $surcharge;
    }

    public function getPreviewSkuUkuran(string $produk, string $warna, string $ukuran): string
    {
        if (blank($ukuran)) {
            return '-';
        }

        $cleanProduk = trim($produk);
        $cleanWarna = trim($warna);
        $cleanUkuran = trim($ukuran);

        $keyword = match (true) {
            Str::contains($cleanProduk, ['30s', '30 s'], true) => '30s',
            Str::contains($cleanProduk, ['24s', '24 s'], true) => '24s',
            Str::contains($cleanProduk, 'Jersey', true) => 'Jersey',
            Str::contains($cleanProduk, ['Hoodie', 'fleece'], true) => 'Hoodie',
            default => $cleanProduk,
        };

        $matching = Produk::query()
            ->where('warna', $cleanWarna)
            ->where('ukuran_baju', $cleanUkuran)
            ->where(function ($q) use ($cleanProduk, $keyword) {
                $q->where('nama_produk', 'like', "%{$keyword}%")
                    ->orWhere('jenis_kain', 'like', "%{$keyword}%")
                    ->orWhere('nama_produk', 'like', "%{$cleanProduk}%");
            })
            ->first();

        if ($matching && $matching->kode_produk) {
            return $matching->kode_produk . '-' . $cleanUkuran;
        }

        return 'PRD-Auto-' . $cleanUkuran;
    }

    public function hitungTotalDanHarga(): void
    {
        $totalPcs = 0;
        $totalHarga = 0;
        $distinctProduk = [];

        foreach ($this->variasi as $index => &$item) {
            $item['produk'] = $item['produk'] ?? self::DAFTAR_PRODUK[0];
            $item['kategori'] = $item['kategori'] ?? 'Dewasa';
            $item['warna'] = $item['warna'] ?? self::WARNA_MASTER[0];

            $opsiUkuran = $this->getUkuranOptionsByKategori($item['kategori']);
            if (! in_array($item['ukuran'] ?? '', $opsiUkuran, true)) {
                $item['ukuran'] = $opsiUkuran[0] ?? ($item['kategori'] === 'Anak-anak' ? '10' : 'M');
            }

            $rawJumlah = $item['jumlah'] ?? '';
            $jml = (is_numeric($rawJumlah) && (int) $rawJumlah > 0) ? (int) $rawJumlah : 0;

            $hargaSatuan = $this->hitungHargaSatuan($item['produk'], $item['kategori'], $item['ukuran']);
            $item['harga_satuan'] = $hargaSatuan;

            $subtotal = $hargaSatuan * $jml;
            $item['subtotal'] = $subtotal;

            $totalPcs += $jml;
            $totalHarga += $subtotal;
            if (! empty($item['produk'])) {
                $distinctProduk[$item['produk']] = true;
            }
        }
        unset($item);

        $this->totalJenisProduk = count($distinctProduk);
        $this->totalVariasi = count($this->variasi);
        $this->totalPakaianPcs = $totalPcs;
        $this->totalHargaPesanan = $totalHarga;

        $this->jenis_pakaian = implode(', ', array_keys($distinctProduk)) ?: (self::DAFTAR_PRODUK[0] ?? 'Kaos 30s');

        $this->jumlah_pcs = $totalPcs > 0 ? $totalPcs : null;
        $this->harga_satuan = $totalPcs > 0 ? (int) round($totalHarga / $totalPcs) : 60000;
    }

    public function tambahVariasi(): void
    {
        $this->pesanErrorVariasi = '';
        $this->infoDuplikat = null;

        $this->variasi[] = $this->buatBarisVariasiKosong();

        $this->hitungTotalDanHarga();
        $this->cekDuplikasiVariasi();
    }

    public function hapusVariasi(int $index): void
    {
        if (count($this->variasi) > 1 && isset($this->variasi[$index])) {
            unset($this->variasi[$index]);
            $this->variasi = array_values($this->variasi);
        } else {
            $this->variasi = [$this->buatBarisVariasiKosong()];
        }

        $this->infoDuplikat = null;
        $this->pesanErrorVariasi = '';
        $this->hitungTotalDanHarga();
        $this->cekDuplikasiVariasi();
    }

    public function updatedVariasi($value, $key): void
    {
        if (str_ends_with($key, '.kategori')) {
            $parts = explode('.', $key);
            $index = (int) $parts[0];

            if (isset($this->variasi[$index])) {
                $kategoriBaru = $this->variasi[$index]['kategori'];
                $ukuranTersedia = $this->getUkuranOptionsByKategori($kategoriBaru);
                $ukuranSekarang = $this->variasi[$index]['ukuran'] ?? '';

                if (! in_array($ukuranSekarang, $ukuranTersedia, true)) {
                    $this->variasi[$index]['ukuran'] = $kategoriBaru === 'Anak-anak' ? '10' : 'M';
                }
            }
        }

        $this->hitungTotalDanHarga();
        $this->cekDuplikasiVariasi();
    }

    public function cekDuplikasiVariasi(): bool
    {
        $map = [];
        $this->infoDuplikat = null;

        foreach ($this->variasi as $idx => $v) {
            $prod = $v['produk'] ?? self::DAFTAR_PRODUK[0];
            $kat = $v['kategori'] ?? 'Dewasa';
            $war = $v['warna'] ?? self::WARNA_MASTER[0];
            $uk = $v['ukuran'] ?? 'M';
            $sig = "{$prod}|{$kat}|{$war}|{$uk}";

            if (isset($map[$sig])) {
                $originalIdx = $map[$sig];
                $this->infoDuplikat = [
                    'source_index' => $idx,
                    'target_index' => $originalIdx,
                    'produk' => $prod,
                    'kategori' => $kat,
                    'warna' => $war,
                    'ukuran' => $uk,
                    'jumlah_tambah' => (int) ($v['jumlah'] ?? 1),
                ];
                $this->pesanErrorVariasi = "Kombinasi produk \"{$prod}\", kategori \"{$kat}\", warna \"{$war}\", dan ukuran \"{$uk}\" sudah ada di baris ke-" . ($originalIdx + 1) . ". Tidak boleh dimasukkan dua kali.";
                return false;
            }
            $map[$sig] = $idx;
        }

        $this->pesanErrorVariasi = '';
        return true;
    }

    public function gabungkanVariasiDuplikat(): void
    {
        if ($this->infoDuplikat) {
            $target = $this->infoDuplikat['target_index'];
            $source = $this->infoDuplikat['source_index'];

            if (isset($this->variasi[$target]) && isset($this->variasi[$source])) {
                $this->variasi[$target]['jumlah'] = max(1, (int) ($this->variasi[$target]['jumlah'] ?? 0) + (int) ($this->variasi[$source]['jumlah'] ?? 0));
                unset($this->variasi[$source]);
                $this->variasi = array_values($this->variasi);
            }

            $this->infoDuplikat = null;
            $this->pesanErrorVariasi = '';
            $this->hitungTotalDanHarga();
            $this->cekDuplikasiVariasi();
        }
    }

    public function hitungTotalPcs(): void
    {
        $this->hitungTotalDanHarga();
    }

    public function simpanPesanan(): void
    {
        $this->hitungTotalDanHarga();

        if (empty($this->variasi)) {
            $this->addError('variasi', 'Minimal harus ada 1 variasi ukuran & warna.');
            return;
        }

        if (! $this->cekDuplikasiVariasi()) {
            $this->addError('variasi', $this->pesanErrorVariasi);
            return;
        }

        $daftarProdukTerpilih = [];
        foreach ($this->variasi as $idx => $v) {
            $prod = $v['produk'] ?? '';
            if (! in_array($prod, self::DAFTAR_PRODUK, true)) {
                $this->addError("variasi.{$idx}.produk", 'Produk pakaian wajib dipilih dari master data.');
                return;
            }
            if ((int) ($v['jumlah'] ?? 0) < 1) {
                $this->addError("variasi.{$idx}.jumlah", 'Jumlah pcs minimal 1 untuk setiap variasi.');
                return;
            }
            $daftarProdukTerpilih[] = $prod;
        }

        $uniqueProduk = array_values(array_unique(array_filter($daftarProdukTerpilih)));
        $this->jenis_pakaian = implode(', ', $uniqueProduk) ?: (self::DAFTAR_PRODUK[0] ?? 'Kaos 30s');

        $warnaList = array_values(array_unique(array_filter(array_column($this->variasi, 'warna'))));
        $this->warna = implode(', ', $warnaList);

        $grouped = [];
        foreach ($this->variasi as $v) {
            $kat = $v['kategori'] ?? 'Dewasa';
            $prodName = $v['produk'] ?? (self::DAFTAR_PRODUK[0] ?? 'Kaos 30s');
            $grouped[$kat][] = "{$prodName} {$v['warna']} {$v['ukuran']} ({$v['jumlah']} pcs)";
        }
        $ringkasanUkuran = [];
        foreach ($grouped as $kat => $items) {
            $ringkasanUkuran[] = "[$kat] " . implode(', ', $items);
        }
        $this->ukuran_baju = implode(' | ', $ringkasanUkuran);

        $data = $this->validate([
            'nama_pelanggan' => ['required', 'string', 'min:3', 'max:150'],
            'nomor_telepon' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:2000'],
            'jenis_pakaian' => ['required', 'string', 'max:500'],
            'warna' => ['required', 'string', 'max:5000'],
            'ukuran_baju' => ['required', 'string', 'max:5000'],
            'jumlah_pcs' => ['required', 'integer', 'min:1'],
            'harga_satuan' => ['required', 'integer', 'min:0'],
            'tanggal_deadline' => ['required', 'date', 'after_or_equal:today'],
            'catatan_tambahan' => ['nullable', 'string', 'max:5000'],
            'status_pembayaran_awal' => ['required', Rule::in(['dp', 'lunas', 'belum_bayar'])],
            'file_desain' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:10240'],
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan / instansi wajib diisi.',
            'nomor_telepon.required' => 'Nomor WhatsApp pelanggan wajib diisi.',
            'alamat.required' => 'Alamat pelanggan wajib diisi.',
            'jenis_pakaian.required' => 'Produk pakaian pada tabel variasi wajib ditentukan.',
            'warna.required' => 'Warna kain wajib ditentukan dari variasi.',
            'ukuran_baju.required' => 'Rincian variasi ukuran baju wajib diisi.',
            'jumlah_pcs.min' => 'Jumlah minimal 1 pcs.',
            'tanggal_deadline.required' => 'Tanggal target selesai wajib ditentukan.',
            'tanggal_deadline.after_or_equal' => 'Target batas waktu tidak boleh sebelum hari ini.',
            'file_desain.mimes' => 'File desain harus berupa JPG, PNG, WEBP, atau PDF.',
            'file_desain.max' => 'Ukuran file desain maksimal 10 MB.',
        ]);

        $fileDesainPath = null;
        if ($this->file_desain) {
            $fileDesainPath = $this->file_desain->store('desain-pesanan', 'local');
            if (! $fileDesainPath) {
                throw new RuntimeException('File desain gagal disimpan.');
            }
        }

        try {
            $variasiData = $this->variasi;
            $editId = $this->editPesananId;

            $nomorPemesanan = DB::transaction(function () use ($data, $fileDesainPath, $variasiData, $editId): string {
                $totalPcsCalculated = 0;
                $totalHargaCalculated = 0;
                $variasiToSave = [];

                foreach ($variasiData as $item) {
                    $namaProd = $item['produk'] ?? self::DAFTAR_PRODUK[0];
                    $kat = $item['kategori'] ?? 'Dewasa';
                    $war = $item['warna'] ?? self::WARNA_MASTER[0];
                    $uk = $item['ukuran'] ?? 'M';
                    $jml = max(1, (int) ($item['jumlah'] ?? 1));

                    $hargaSatuanSnapshot = $this->hitungHargaSatuan($namaProd, $kat, $uk);
                    $subtotalSnapshot = $hargaSatuanSnapshot * $jml;

                    $totalPcsCalculated += $jml;
                    $totalHargaCalculated += $subtotalSnapshot;

                    $matchingProduk = Produk::findOrCreateForVariasi(
                        namaProduk: $namaProd,
                        warna: $war,
                        ukuran: $uk,
                        hargaSatuan: $hargaSatuanSnapshot
                    );

                    $variasiToSave[] = [
                        'produk_id' => $matchingProduk->id,
                        'nama_produk' => $namaProd,
                        'kategori_ukuran' => $kat,
                        'warna' => $war,
                        'ukuran' => $uk,
                        'jumlah' => $jml,
                        'harga_satuan' => $hargaSatuanSnapshot,
                        'subtotal' => $subtotalSnapshot,
                    ];
                }

                $hargaRataRata = $totalPcsCalculated > 0 ? (int) round($totalHargaCalculated / $totalPcsCalculated) : 60000;
                $catatanSablon = filled($data['catatan_tambahan']) ? trim($data['catatan_tambahan']) : null;

                if ($editId) {
                    $pesanan = Pemesanan::with(['pelanggan', 'variasi'])->findOrFail($editId);

                    if ($pesanan->pelanggan) {
                        $pesanan->pelanggan->update([
                            'nama' => trim($data['nama_pelanggan']),
                            'nomor_telepon' => trim($data['nomor_telepon']),
                            'alamat' => trim($data['alamat']),
                        ]);
                    }

                    $updateData = [
                        'jenis_kain' => $data['jenis_pakaian'],
                        'warna' => trim($data['warna']),
                        'ukuran_baju' => trim($data['ukuran_baju']),
                        'jumlah' => $totalPcsCalculated,
                        'tanggal_selesai_estimasi' => $data['tanggal_deadline'],
                        'harga_satuan' => $hargaRataRata,
                        'total_harga' => $totalHargaCalculated,
                        'catatan' => $catatanSablon,
                        'detail_desain' => $catatanSablon ?? $pesanan->detail_desain,
                    ];

                    if ($fileDesainPath) {
                        if ($pesanan->file_desain) {
                            Storage::disk('local')->delete($pesanan->file_desain);
                        }
                        $updateData['file_desain'] = $fileDesainPath;
                    }

                    $pesanan->update($updateData);

                    $pesanan->variasi()->delete();
                    foreach ($variasiToSave as $varRow) {
                        $varRow['pemesanan_id'] = $pesanan->id;
                        PemesananVariasi::create($varRow);
                    }

                    return $pesanan->nomor_pemesanan;
                }

                // Cari atau buat pelanggan baru
                $pelanggan = Pelanggan::where('nomor_telepon', trim($data['nomor_telepon']))->first()
                    ?? Pelanggan::where('nama', trim($data['nama_pelanggan']))->first();

                if ($pelanggan) {
                    $pelanggan->update([
                        'nama' => trim($data['nama_pelanggan']),
                        'nomor_telepon' => trim($data['nomor_telepon']),
                        'alamat' => trim($data['alamat']),
                    ]);
                } else {
                    do {
                        $kodePelanggan = 'PEL-'.now()->format('Ym').'-'.Str::upper(Str::random(6));
                    } while (Pelanggan::query()->where('kode_pelanggan', $kodePelanggan)->exists());

                    $pelanggan = Pelanggan::create([
                        'kode_pelanggan' => $kodePelanggan,
                        'nama' => trim($data['nama_pelanggan']),
                        'nomor_telepon' => trim($data['nomor_telepon']),
                        'alamat' => trim($data['alamat']),
                    ]);
                }

                // Generate Nomor Pemesanan Unik
                $count = Pemesanan::query()->count() + 1;
                $nomorPemesanan = 'ORD-'.now()->format('Y').'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
                while (Pemesanan::query()->where('nomor_pemesanan', $nomorPemesanan)->exists()) {
                    $count++;
                    $nomorPemesanan = 'ORD-'.now()->format('Y').'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
                }

                $statusPemesanan = match ($data['status_pembayaran_awal']) {
                    'lunas', 'dp' => 'dalam_produksi',
                    default => 'menunggu_pembayaran',
                };

                $pesanan = Pemesanan::create([
                    'nomor_pemesanan' => $nomorPemesanan,
                    'pelanggan_id' => $pelanggan->id,
                    'user_id' => auth()->id(),
                    'produk_id' => $variasiToSave[0]['produk_id'] ?? null,
                    'jenis_kain' => $data['jenis_pakaian'],
                    'warna' => trim($data['warna']),
                    'ukuran_baju' => trim($data['ukuran_baju']),
                    'jumlah' => $totalPcsCalculated,
                    'detail_desain' => $catatanSablon ?? 'Tidak ada catatan khusus sablon.',
                    'file_desain' => $fileDesainPath,
                    'tanggal_pemesanan' => now()->toDateString(),
                    'tanggal_selesai_estimasi' => $data['tanggal_deadline'],
                    'harga_satuan' => $hargaRataRata,
                    'total_harga' => $totalHargaCalculated,
                    'status_pemesanan' => $statusPemesanan,
                    'status_pembayaran' => $data['status_pembayaran_awal'],
                    'catatan' => $catatanSablon,
                ]);

                foreach ($variasiToSave as $varRow) {
                    $varRow['pemesanan_id'] = $pesanan->id;
                    PemesananVariasi::create($varRow);
                }

                if ($data['status_pembayaran_awal'] === 'lunas') {
                    Pembayaran::create([
                        'kode_pembayaran' => 'PAY-'.now()->format('Ymd').'-'.random_int(100, 999),
                        'pemesanan_id' => $pesanan->id,
                        'user_id' => auth()->id(),
                        'jenis_pembayaran' => 'Pelunasan',
                        'jumlah_bayar' => $totalHargaCalculated,
                        'tanggal_bayar' => now(),
                        'metode_pembayaran' => 'Transfer Bank',
                        'status_konfirmasi' => 'Diterima',
                        'catatan' => 'Pembayaran Lunas Saat Pemesanan SPK',
                    ]);
                } elseif ($data['status_pembayaran_awal'] === 'dp') {
                    Pembayaran::create([
                        'kode_pembayaran' => 'PAY-'.now()->format('Ymd').'-'.random_int(100, 999),
                        'pemesanan_id' => $pesanan->id,
                        'user_id' => auth()->id(),
                        'jenis_pembayaran' => 'DP',
                        'jumlah_bayar' => (int) round($totalHargaCalculated * 0.5),
                        'tanggal_bayar' => now(),
                        'metode_pembayaran' => 'Transfer Bank',
                        'status_konfirmasi' => 'Diterima',
                        'catatan' => 'DP 50% Pemesanan SPK Baru',
                    ]);
                }

                $prod = Produksi::create([
                    'kode_produksi' => 'PRD-'.now()->format('Y').'-'.str_pad((string) $pesanan->id, 4, '0', STR_PAD_LEFT),
                    'pemesanan_id' => $pesanan->id,
                    'user_id' => auth()->id(),
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => $data['tanggal_deadline'],
                    'status_pemotongan_kain' => 'belum_mulai',
                    'status_penjahitan' => 'belum_mulai',
                    'status_steam' => 'belum_mulai',
                    'status_sablon' => 'belum_mulai',
                    'tahap_saat_ini' => 'pemotongan_kain',
                    'progress_persen' => 0,
                    'status_produksi' => 'proses',
                    'catatan_produksi' => 'Antrean Pemotongan Kain',
                ]);
                $prod->inisialisasiTahapan();

                AktivitasLog::catat(
                    aksi: 'menerbitkan SPK pesanan baru',
                    referensi: $nomorPemesanan,
                    tipe: 'pesanan',
                    warna: 'teal',
                    namaUser: auth()->user()?->name ?? 'Admin',
                    keterangan: "SPK {$nomorPemesanan} diterbitkan untuk {$data['nama_pelanggan']} ({$totalPcsCalculated} pcs {$data['jenis_pakaian']}). Masuk tahap Pemotongan Kain."
                );

                return $nomorPemesanan;
            });
        } catch (Throwable $exception) {
            if ($fileDesainPath) {
                Storage::disk('local')->delete($fileDesainPath);
            }

            throw $exception;
        }

        $this->tutupModalCreate();
        $this->resetFormSpk();

        $this->pesanSukses = $editId
            ? "Perubahan SPK {$nomorPemesanan} berhasil disimpan."
            : "SPK {$nomorPemesanan} berhasil diterbitkan & diteruskan ke antrean Pemotongan Kain.";

        $this->dispatch('pesanan-tersimpan', nomorPemesanan: $nomorPemesanan);

        if (method_exists($this, 'dispatchGrafikUpdate')) {
            $this->dispatchGrafikUpdate();
        }

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }
}
