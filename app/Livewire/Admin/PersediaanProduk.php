<?php

namespace App\Livewire\Admin;

use App\Models\MutasiStok;
use App\Models\Pemesanan;
use App\Models\Produk;
use App\Models\StokProduk;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PersediaanProduk extends Component
{
    use WithPagination;

    private const UKURAN = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

    private const MUTASI_FILTERS = ['semua', 'masuk', 'keluar'];

    // Navigasi Tab Utama
    #[Url(as: 'tab')]
    public string $activeTab = 'persediaan'; // 'persediaan' | 'mutasi'

    // Filter & Search Persediaan Produk
    public string $search = '';

    public string $filterStatus = 'semua';

    // Filter & Search Riwayat Mutasi
    public string $filterTab = 'semua'; // 'semua', 'masuk', 'keluar'

    public string $searchMutasi = '';

    public int $perPageMutasi = 10;

    // Modals Persediaan
    public bool $showModalCreate = false;

    public bool $showModalMasuk = false;

    public bool $showModalRop = false;

    public bool $showModalDetail = false;

    // Modal Mutasi Masuk / Keluar
    public bool $showModalMutasi = false;

    public string $jenisPergerakan = 'masuk'; // 'masuk' | 'keluar'

    public string $tujuanKeluar = 'Kirim'; // 'Kirim' | 'Diambil'

    public ?int $stokProdukId = null;

    public int $jumlah = 1;

    public string $referensi = '';

    // Form Tambah SKU & Stok Masuk
    public ?int $selectedStokId = null;

    public ?int $selectedProdukId = null;

    public string $kodeProduk = '';

    public string $namaProduk = '';

    public string $jenisKain = '';

    public string $warna = '';

    public string $ukuran = 'M';

    public int $hargaSatuan = 0;

    public int $stokAwal = 0;

    public int $batasRop = 10;

    public int $jumlahMasuk = 1;

    public string $tanggalMutasi = '';

    public string $keterangan = '';

    public string $pesanSukses = '';

    public function mount(): void
    {
        $this->tanggalMutasi = now()->format('Y-m-d\TH:i');

        if (request()->routeIs('admin.mutasi-stok.*') || request()->query('tab') === 'mutasi') {
            $this->activeTab = 'mutasi';
        }

        if (request()->query('filterStatus')) {
            $this->filterStatus = (string) request()->query('filterStatus');
        }

        if (request()->filled('search')) {
            $this->search = (string) request()->query('search');
        }
    }

    public function setMainTab(string $tab): void
    {
        if (in_array($tab, ['persediaan', 'mutasi'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearchMutasi(): void
    {
        $this->perPageMutasi = 10;
    }

    // --- FITUR MUTASI STOK ---

    public function setTab(string $tab): void
    {
        if (! in_array($tab, self::MUTASI_FILTERS, true)) {
            throw ValidationException::withMessages([
                'filterTab' => 'Filter pergerakan stok tidak tersedia.',
            ]);
        }

        $this->filterTab = $tab;
        $this->perPageMutasi = 10;
    }

    public function muatLebihBanyak(): void
    {
        $this->perPageMutasi += 10;
    }

    #[On('buka-modal-stok-masuk')]
    public function bukaModalMasuk(): void
    {
        if ($this->activeTab === 'mutasi') {
            $this->bukaForm('masuk');
        } else {
            $this->resetValidation();
            $this->selectedStokId = null;
            $this->jumlahMasuk = 1;
            $this->tanggalMutasi = now()->format('Y-m-d\TH:i');
            $this->keterangan = '';
            $this->showModalMasuk = true;
        }
    }

    public function bukaModalKeluar(): void
    {
        $this->bukaForm('keluar');
    }

    public function bukaForm(string $jenisMutasi): void
    {
        if (! in_array($jenisMutasi, ['masuk', 'keluar'], true)) {
            throw ValidationException::withMessages([
                'jenisPergerakan' => 'Jenis mutasi tidak tersedia.',
            ]);
        }

        $this->resetValidation();
        $this->jenisPergerakan = $jenisMutasi;
        $this->tujuanKeluar = 'Kirim';
        $this->stokProdukId = null;
        $this->jumlah = 1;
        $this->tanggalMutasi = now()->format('Y-m-d\TH:i');
        $this->referensi = '';
        $this->keterangan = '';
        $this->showModalMutasi = true;
    }

    public function tutupModalMutasi(): void
    {
        $this->showModalMutasi = false;
        $this->resetValidation();
    }

    public function simpanMutasi(): void
    {
        $data = $this->validate([
            'jenisPergerakan' => ['required', Rule::in(['masuk', 'keluar'])],
            'tujuanKeluar' => [Rule::requiredIf($this->jenisPergerakan === 'keluar'), 'nullable', Rule::in(['Kirim', 'Diambil'])],
            'stokProdukId' => ['required', 'integer', Rule::exists('stok_produk', 'id')],
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000000'],
            'tanggalMutasi' => ['required', 'date', 'before_or_equal:now'],
            'referensi' => [
                Rule::requiredIf($this->jenisPergerakan === 'keluar'),
                'nullable',
                'string',
                'max:255',
                Rule::when($this->jenisPergerakan === 'keluar', Rule::exists('pemesanan', 'nomor_pemesanan')),
            ],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tujuanKeluar.required' => 'Pilih tujuan pengeluaran stok.',
            'stokProdukId.required' => 'Varian produk wajib dipilih.',
            'jumlah.required' => 'Jumlah pergerakan stok wajib diisi.',
            'jumlah.min' => 'Jumlah pergerakan stok minimal 1 pcs.',
            'tanggalMutasi.before_or_equal' => 'Tanggal mutasi tidak boleh di masa depan.',
            'referensi.required' => 'Nomor pesanan wajib dipilih untuk stok keluar.',
            'referensi.exists' => 'Nomor pesanan tidak ditemukan.',
        ]);

        $mutasi = DB::transaction(function () use ($data): MutasiStok {
            $stok = StokProduk::query()
                ->whereKey($data['stokProdukId'])
                ->lockForUpdate()
                ->firstOrFail();
            $saldoSebelum = $stok->sisa_stok;
            $jumlahMasuk = $data['jenisPergerakan'] === 'masuk' ? $data['jumlah'] : 0;
            $jumlahKeluar = $data['jenisPergerakan'] === 'keluar' ? $data['jumlah'] : 0;

            if ($jumlahKeluar > $saldoSebelum) {
                throw ValidationException::withMessages([
                    'jumlah' => "Stok tidak mencukupi. Sisa stok saat ini {$saldoSebelum} pcs.",
                ]);
            }

            if ($jumlahMasuk > 2147483647 - $saldoSebelum) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah stok melebihi kapasitas penyimpanan.',
                ]);
            }

            $saldoSesudah = $saldoSebelum + $jumlahMasuk - $jumlahKeluar;
            $deskripsi = $data['jenisPergerakan'] === 'masuk'
                ? 'Masuk (Produksi)'
                : 'Keluar ('.$data['tujuanKeluar'].')';
            $catatan = trim($data['referensi'] ?? '');

            if (filled($data['keterangan'] ?? null)) {
                $catatan .= ($catatan !== '' ? ' · ' : '').trim($data['keterangan']);
            }

            $mutasi = new MutasiStok([
                'user_id' => auth()->id(),
                'jenis_mutasi' => $data['jenisPergerakan'],
                'jumlah_stok_masuk' => $jumlahMasuk,
                'jumlah_stok_keluar' => $jumlahKeluar,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'keterangan' => $deskripsi.($catatan !== '' ? ' · '.$catatan : ''),
                'tanggal_mutasi' => $data['tanggalMutasi'],
            ]);

            $stok->sisa_stok = $saldoSesudah;
            $stok->perbaruiStatusRop();
            $stok->mutasiStok()->save($mutasi);

            return $mutasi;
        });

        $this->showModalMutasi = false;
        $this->pesanSukses = $mutasi->jenis_mutasi === 'masuk'
            ? 'Stok hasil produksi berhasil ditambahkan dan dicatat.'
            : 'Pengeluaran stok berhasil dicatat.';
        $this->perPageMutasi = 10;
        $this->resetPage();
    }

    // --- FITUR PERSEDIAAN PRODUK ---

    public function bukaModalCreate(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->kodeProduk = $this->generateKodeSku();
        $this->showModalCreate = true;
    }

    public function generateKodeSku(): string
    {
        return Produk::generateNextKodeProduk();
    }

    public function buatUlangKodeSku(): void
    {
        $this->kodeProduk = $this->generateKodeSku();
    }

    public function tutupModalCreate(): void
    {
        $this->showModalCreate = false;
        $this->resetValidation();
    }

    public function simpanProduk(): void
    {
        if (blank($this->kodeProduk)) {
            $this->kodeProduk = $this->generateKodeSku();
        }

        $data = $this->validate([
            'kodeProduk' => ['required', 'string', 'max:50', Rule::unique('produk', 'kode_produk')],
            'namaProduk' => ['required', 'string', 'max:255'],
            'jenisKain' => ['required', 'string', 'max:255'],
            'warna' => ['required', 'string', 'max:255'],
            'ukuran' => ['required', Rule::in(self::UKURAN)],
            'hargaSatuan' => ['required', 'integer', 'min:0', 'max:9999999999'],
            'stokAwal' => ['required', 'integer', 'min:0', 'max:1000000'],
            'batasRop' => ['required', 'integer', 'min:0', 'max:1000000'],
        ], [
            'kodeProduk.required' => 'Kode SKU wajib diisi.',
            'kodeProduk.unique' => 'Kode SKU sudah digunakan.',
            'namaProduk.required' => 'Nama produk wajib diisi.',
            'jenisKain.required' => 'Jenis kain wajib diisi.',
            'warna.required' => 'Warna produk wajib diisi.',
            'ukuran.required' => 'Ukuran produk wajib dipilih.',
            'stokAwal.required' => 'Jumlah stok awal wajib diisi.',
            'batasRop.required' => 'Batas ROP wajib diisi.',
        ]);

        DB::transaction(function () use ($data): void {
            $produk = new Produk([
                'kode_produk' => Str::upper(trim($data['kodeProduk'])),
                'nama_produk' => trim($data['namaProduk']),
                'jenis_kain' => trim($data['jenisKain']),
                'warna' => trim($data['warna']),
                'ukuran_baju' => $data['ukuran'],
                'harga_satuan' => $data['hargaSatuan'],
            ]);
            $produk->save();

            $stok = new StokProduk([
                'nama_produk' => $produk->nama_produk,
                'warna' => $produk->warna,
                'ukuran' => $produk->ukuran_baju,
                'sisa_stok' => $data['stokAwal'],
                'batas_rop' => $data['batasRop'],
                'status_rop' => 'Aman',
            ]);
            $produk->stok()->save($stok);
            $stok->perbaruiStatusRop();

            if ($data['stokAwal'] > 0) {
                $mutasi = new MutasiStok([
                    'user_id' => auth()->id(),
                    'jenis_mutasi' => 'masuk',
                    'jumlah_stok_masuk' => $data['stokAwal'],
                    'jumlah_stok_keluar' => 0,
                    'saldo_sebelum' => 0,
                    'saldo_sesudah' => $data['stokAwal'],
                    'keterangan' => 'Stok awal varian produk.',
                    'tanggal_mutasi' => now(),
                ]);
                $stok->mutasiStok()->save($mutasi);
            }
        });

        $this->showModalCreate = false;
        $this->pesanSukses = 'SKU '.Str::upper(trim($data['kodeProduk'])).' berhasil ditambahkan ke persediaan.';
        $this->resetForm();
    }

    public function tutupModalMasuk(): void
    {
        $this->showModalMasuk = false;
        $this->resetValidation();
    }

    public function simpanStokMasuk(): void
    {
        $data = $this->validate([
            'selectedStokId' => ['required', 'integer', Rule::exists('stok_produk', 'id')],
            'jumlahMasuk' => ['required', 'integer', 'min:1', 'max:1000000'],
            'tanggalMutasi' => ['required', 'date', 'before_or_equal:now'],
            'keterangan' => ['required', 'string', 'max:255'],
        ], [
            'selectedStokId.required' => 'Varian produk wajib dipilih.',
            'jumlahMasuk.required' => 'Jumlah stok masuk wajib diisi.',
            'jumlahMasuk.min' => 'Jumlah stok masuk minimal 1 pcs.',
            'tanggalMutasi.before_or_equal' => 'Tanggal mutasi tidak boleh di masa depan.',
            'keterangan.required' => 'Keterangan penerimaan stok wajib diisi.',
        ]);

        DB::transaction(function () use ($data): void {
            $stok = StokProduk::query()->whereKey($data['selectedStokId'])->lockForUpdate()->firstOrFail();
            $saldoSebelum = $stok->sisa_stok;
            $saldoSesudah = $saldoSebelum + $data['jumlahMasuk'];

            if ($saldoSesudah > 2147483647) {
                throw ValidationException::withMessages([
                    'jumlahMasuk' => 'Jumlah stok melebihi kapasitas penyimpanan.',
                ]);
            }

            $stok->sisa_stok = $saldoSesudah;
            $stok->perbaruiStatusRop();

            $mutasi = new MutasiStok([
                'user_id' => auth()->id(),
                'jenis_mutasi' => 'masuk',
                'jumlah_stok_masuk' => $data['jumlahMasuk'],
                'jumlah_stok_keluar' => 0,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'keterangan' => 'Masuk (Produksi) · '.trim($data['keterangan']),
                'tanggal_mutasi' => $data['tanggalMutasi'],
            ]);
            $stok->mutasiStok()->save($mutasi);
        });

        $this->showModalMasuk = false;
        $this->pesanSukses = 'Stok masuk berhasil dicatat.';
        $this->resetPage();
    }

    public function bukaModalRop(int $stokId): void
    {
        $stok = StokProduk::query()->findOrFail($stokId);

        $this->resetValidation();
        $this->selectedStokId = $stok->id;
        $this->batasRop = $stok->batas_rop;
        $this->showModalRop = true;
    }

    public function simpanBatasRop(): void
    {
        $data = $this->validate([
            'selectedStokId' => ['required', 'integer', Rule::exists('stok_produk', 'id')],
            'batasRop' => ['required', 'integer', 'min:0', 'max:1000000'],
        ], [
            'batasRop.required' => 'Batas minimum ROP wajib diisi.',
            'batasRop.min' => 'Batas ROP tidak boleh kurang dari 0.',
        ]);

        DB::transaction(function () use ($data): StokProduk {
            $stok = StokProduk::query()->whereKey($data['selectedStokId'])->lockForUpdate()->firstOrFail();
            $stok->batas_rop = $data['batasRop'];
            $stok->perbaruiStatusRop();

            return $stok;
        });

        $this->showModalRop = false;
        $this->pesanSukses = 'Batas ROP berhasil diperbarui.';
    }

    public function lihatDetail(int $stokId): void
    {
        StokProduk::query()->findOrFail($stokId);
        $this->selectedStokId = $stokId;
        $this->showModalDetail = true;
    }

    public function tutupModalDetail(): void
    {
        $this->showModalDetail = false;
        $this->selectedStokId = null;
    }

    public function jadwalkanProduksi(int $stokId): mixed
    {
        $stok = StokProduk::query()->with('produk')->findOrFail($stokId);
        $jumlahProduksi = max(1, ($stok->batas_rop * 2) - $stok->sisa_stok);

        return $this->redirectRoute('admin.pesanan.index', [
            'produk_id' => $stok->produk_id,
            'jumlah_pcs' => $jumlahProduksi,
        ]);
    }

    protected function resetForm(): void
    {
        $this->kodeProduk = $this->generateKodeSku();
        $this->namaProduk = '';
        $this->jenisKain = '';
        $this->warna = '';
        $this->ukuran = 'M';
        $this->hargaSatuan = 0;
        $this->stokAwal = 0;
        $this->batasRop = 10;
    }

    /**
     * @return Builder<StokProduk>
     */
    private function queryStok(): Builder
    {
        $query = StokProduk::query()->with(['produk']);

        if ($this->filterStatus === 'aman') {
            $query->aman();
        } elseif ($this->filterStatus === 'menipis') {
            $query->perluReorder();
        } elseif ($this->filterStatus === 'habis') {
            $query->habis();
        }

        if (filled($this->search)) {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $stok) use ($term): void {
                $stok->where('ukuran', 'like', $term)
                    ->orWhere('warna', 'like', $term)
                    ->orWhere('nama_produk', 'like', $term)
                    ->orWhereHas('produk', function (Builder $produk) use ($term): void {
                        $produk->where('kode_produk', 'like', $term)
                            ->orWhere('nama_produk', 'like', $term)
                            ->orWhere('jenis_kain', 'like', $term);
                    });
            });
        }

        return $query->orderByRaw('CASE WHEN sisa_stok <= 0 THEN 0 WHEN sisa_stok <= batas_rop THEN 1 ELSE 2 END')
            ->orderBy('nama_produk')
            ->orderBy('ukuran');
    }

    /**
     * @return Builder<MutasiStok>
     */
    private function queryMutasi(): Builder
    {
        $query = MutasiStok::query()->with(['stokProduk.produk', 'user']);

        if ($this->filterTab !== 'semua') {
            $query->where('jenis_mutasi', $this->filterTab);
        }

        $termMutasi = filled($this->searchMutasi) ? $this->searchMutasi : (filled($this->search) && $this->activeTab === 'mutasi' ? $this->search : '');

        if (filled($termMutasi)) {
            $term = '%'.$termMutasi.'%';
            $query->where(function (Builder $mutasi) use ($term): void {
                $mutasi->where('keterangan', 'like', $term)
                    ->orWhereHas('stokProduk', function (Builder $stok) use ($term): void {
                        $stok->where('nama_produk', 'like', $term)
                            ->orWhere('warna', 'like', $term)
                            ->orWhere('ukuran', 'like', $term)
                            ->orWhereHas('produk', function (Builder $produk) use ($term): void {
                                $produk->where('kode_produk', 'like', $term)
                                    ->orWhere('nama_produk', 'like', $term);
                            });
                    });
            });
        }

        return $query->latest('tanggal_mutasi')->latest('id');
    }

    public function render(): View
    {
        $jumlahSku = StokProduk::query()->count();
        $jumlahAman = StokProduk::query()->aman()->count();
        $jumlahKritis = $jumlahSku - $jumlahAman;
        $jumlahSiapDiambil = Pemesanan::query()
            ->whereIn('status_pemesanan', ['selesai_produksi', 'siap_diambil'])
            ->sum('jumlah');
        $stokKritis = StokProduk::query()
            ->with('produk')
            ->whereColumn('sisa_stok', '<=', 'batas_rop')
            ->orderBy('sisa_stok')
            ->orderBy('nama_produk')
            ->limit(4)
            ->get();

        $awalMinggu = now()->startOfWeek();
        $akhirMinggu = now()->endOfWeek();

        return view('livewire.admin.persediaan-produk', [
            'activeTab' => $this->activeTab,
            'pesanSukses' => $this->pesanSukses,
            'showModalCreate' => $this->showModalCreate,
            'showModalMasuk' => $this->showModalMasuk,
            'showModalRop' => $this->showModalRop,
            'showModalDetail' => $this->showModalDetail,
            'showModalMutasi' => $this->showModalMutasi,
            'filterTab' => $this->filterTab,
            'jenisPergerakan' => $this->jenisPergerakan,

            // Data Persediaan Produk
            'daftarStok' => $this->queryStok()->paginate(10),
            'jumlahSku' => $jumlahSku,
            'totalStok' => StokProduk::query()->sum('sisa_stok'),
            'jumlahKritis' => $jumlahKritis,
            'jumlahSiapDiambil' => $jumlahSiapDiambil,
            'stokKritis' => $stokKritis,
            'persentaseAman' => $jumlahSku === 0
                ? 0
                : (int) round($jumlahAman / $jumlahSku * 100),
            'stokPilihan' => StokProduk::query()->with('produk')->orderBy('nama_produk')->orderBy('ukuran')->get(),
            'detailStok' => $this->showModalDetail && $this->selectedStokId
                ? StokProduk::query()->with(['produk', 'mutasiStok' => fn (HasMany $mutasi): HasMany => $mutasi->with('user')->latest('tanggal_mutasi')->limit(20)])
                    ->find($this->selectedStokId)
                : null,

            // Data Riwayat Mutasi
            'daftarMutasi' => $this->queryMutasi()->limit($this->perPageMutasi)->get(),
            'jumlahMutasi' => $this->queryMutasi()->count(),
            'jumlahMasukMingguIni' => MutasiStok::query()
                ->where('jenis_mutasi', 'masuk')
                ->whereBetween('tanggal_mutasi', [$awalMinggu, $akhirMinggu])
                ->sum('jumlah_stok_masuk'),
            'jumlahKeluarMingguIni' => MutasiStok::query()
                ->where('jenis_mutasi', 'keluar')
                ->whereBetween('tanggal_mutasi', [$awalMinggu, $akhirMinggu])
                ->sum('jumlah_stok_keluar'),
            'totalStokSiapAmbil' => StokProduk::query()->sum('sisa_stok'),
            'pesananPilihan' => $this->showModalMutasi && $this->jenisPergerakan === 'keluar'
                ? Pemesanan::query()
                    ->with('pelanggan')
                    ->whereNotIn('status_pemesanan', ['dibatalkan'])
                    ->latest('tanggal_pemesanan')
                    ->limit(100)
                    ->get()
                : collect(),
        ]);
    }
}
