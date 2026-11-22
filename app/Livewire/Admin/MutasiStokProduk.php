<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class MutasiStokProduk extends PersediaanProduk
{
    public function mount(): void
    {
        parent::mount();
        $this->activeTab = 'mutasi';
    }
}
