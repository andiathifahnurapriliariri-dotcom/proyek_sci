<?php

namespace App\Livewire\Admin;

use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\Produksi;
use App\Models\StokProduk;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public bool $isOpen = false;

    public int $selectedIndex = -1;

    public function updatedQuery(): void
    {
        $term = trim($this->query);
        if (strlen($term) >= 1) {
            $this->isOpen = true;
            $this->selectedIndex = -1;
        } else {
            $this->isOpen = false;
            $this->selectedIndex = -1;
        }
    }

    public function clearQuery(): void
    {
        $this->query = '';
        $this->isOpen = false;
        $this->selectedIndex = -1;
    }

    public function closeDropdown(): void
    {
        $this->isOpen = false;
        $this->selectedIndex = -1;
    }

    public function openDropdown(): void
    {
        if (strlen(trim($this->query)) >= 1) {
            $this->isOpen = true;
        }
    }

    public function selectNext(int $max): void
    {
        if ($max <= 0) {
            return;
        }
        $this->selectedIndex = ($this->selectedIndex + 1) >= $max ? 0 : $this->selectedIndex + 1;
    }

    public function selectPrev(int $max): void
    {
        if ($max <= 0) {
            return;
        }
        $this->selectedIndex = ($this->selectedIndex - 1) < 0 ? $max - 1 : $this->selectedIndex - 1;
    }

    public function render(): View
    {
        $term = trim($this->query);

        if (strlen($term) < 1) {
            return view('livewire.admin.global-search', [
                'pesanan' => collect(),
                'pesananTotal' => 0,
                'pelanggan' => collect(),
                'pelangganTotal' => 0,
                'stokProduk' => collect(),
                'stokTotal' => 0,
                'produksi' => collect(),
                'produksiTotal' => 0,
                'flatItems' => [],
                'totalHasil' => 0,
            ]);
        }

        $like = '%' . $term . '%';

        // 1. Modul Pesanan (SPK, jenis kain, warna, kategori, detail desain, pelanggan, variasi)
        $pesananQuery = Pemesanan::query()->with(['pelanggan', 'variasi'])
            ->where(function (Builder $q) use ($like): void {
                $q->where('nomor_pemesanan', 'like', $like)
                    ->orWhere('jenis_kain', 'like', $like)
                    ->orWhere('warna', 'like', $like)
                    ->orWhere('kategori_produk', 'like', $like)
                    ->orWhere('detail_desain', 'like', $like)
                    ->orWhere('status_pemesanan', 'like', $like)
                    ->orWhereHas('pelanggan', fn (Builder $p) => $p->where('nama', 'like', $like)->orWhere('nomor_telepon', 'like', $like))
                    ->orWhereHas('variasi', fn (Builder $v) => $v->where('nama_produk', 'like', $like)->orWhere('warna', 'like', $like)->orWhere('ukuran', 'like', $like));
            });

        $pesananTotal = (clone $pesananQuery)->count();
        $pesanan = $pesananQuery->latest('id')->limit(4)->get();

        // 2. Modul Data Pelanggan (nama, WhatsApp, email, alamat, kota, kode)
        $pelangganQuery = Pelanggan::query()
            ->where(function (Builder $q) use ($like): void {
                $q->where('nama', 'like', $like)
                    ->orWhere('nomor_telepon', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('alamat', 'like', $like)
                    ->orWhere('kota', 'like', $like)
                    ->orWhere('kode_pelanggan', 'like', $like)
                    ->orWhere('kategori', 'like', $like);
            });

        $pelangganTotal = (clone $pelangganQuery)->count();
        $pelanggan = $pelangganQuery->limit(4)->get();

        // 3. Modul Persediaan & Stok (nama produk, warna, ukuran, SKU produk, jenis kain)
        $stokQuery = StokProduk::query()->with('produk')
            ->where(function (Builder $q) use ($like): void {
                $q->where('nama_produk', 'like', $like)
                    ->orWhere('warna', 'like', $like)
                    ->orWhere('ukuran', 'like', $like)
                    ->orWhereHas('produk', fn (Builder $pr) => $pr->where('kode_produk', 'like', $like)
                        ->orWhere('nama_produk', 'like', $like)
                        ->orWhere('jenis_kain', 'like', $like)
                        ->orWhere('warna', 'like', $like));
            });

        $stokTotal = (clone $stokQuery)->count();
        $stokProduk = $stokQuery->orderByRaw('CASE WHEN sisa_stok <= 0 THEN 0 WHEN sisa_stok <= batas_rop THEN 1 ELSE 2 END')
            ->limit(4)
            ->get();

        // 4. Modul Monitoring Produksi (kode produksi, nomor SPK, pemesan, tahap saat ini)
        $produksiQuery = Produksi::query()->with(['pemesanan.pelanggan', 'operator'])
            ->where(function (Builder $q) use ($like): void {
                $q->where('kode_produksi', 'like', $like)
                    ->orWhere('tahap_saat_ini', 'like', $like)
                    ->orWhere('status_produksi', 'like', $like)
                    ->orWhereHas('pemesanan', function (Builder $pm) use ($like): void {
                        $pm->where('nomor_pemesanan', 'like', $like)
                            ->orWhere('jenis_kain', 'like', $like)
                            ->orWhere('warna', 'like', $like)
                            ->orWhereHas('pelanggan', fn (Builder $pl) => $pl->where('nama', 'like', $like));
                    });
            });

        $produksiTotal = (clone $produksiQuery)->count();
        $produksi = $produksiQuery->latest('id')->limit(4)->get();

        // Siapkan flat list untuk navigasi keyboard & deteksi index
        $flatItems = [];

        foreach ($pesanan as $item) {
            $flatItems[] = [
                'type' => 'pesanan',
                'url' => route('admin.pesanan.index', ['search' => $item->nomor_pemesanan, 'detail' => $item->id]),
            ];
        }

        foreach ($pelanggan as $item) {
            $flatItems[] = [
                'type' => 'pelanggan',
                'url' => route('admin.pelanggan.index', ['search' => $item->nama]),
            ];
        }

        foreach ($stokProduk as $item) {
            $flatItems[] = [
                'type' => 'stok',
                'url' => route('admin.persediaan.index', ['tab' => 'persediaan', 'search' => $item->produk?->kode_produk ?? $item->nama_produk]),
            ];
        }

        foreach ($produksi as $item) {
            $flatItems[] = [
                'type' => 'produksi',
                'url' => route('admin.produksi.index', ['search' => $item->pemesanan?->nomor_pemesanan ?? $item->kode_produksi]),
            ];
        }

        $totalHasil = $pesananTotal + $pelangganTotal + $stokTotal + $produksiTotal;

        return view('livewire.admin.global-search', [
            'pesanan' => $pesanan,
            'pesananTotal' => $pesananTotal,
            'pelanggan' => $pelanggan,
            'pelangganTotal' => $pelangganTotal,
            'stokProduk' => $stokProduk,
            'stokTotal' => $stokTotal,
            'produksi' => $produksi,
            'produksiTotal' => $produksiTotal,
            'flatItems' => $flatItems,
            'totalHasil' => $totalHasil,
        ]);
    }
}
