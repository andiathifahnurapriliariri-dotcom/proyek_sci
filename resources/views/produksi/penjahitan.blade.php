<x-layouts::app title="Dashboard Penjahitan">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                <flux:icon name="wrench-screwdriver" class="text-blue-600 size-6" />
            </div>
            <div>
                <flux:heading size="xl">Dashboard Penjahitan</flux:heading>
                <flux:subheading>Divisi Sewing — COTTON.id</flux:subheading>
            </div>
        </div>
        <flux:callout variant="warning" icon="information-circle">
            Modul Penjahitan sedang dalam pengembangan. Halaman ini akan menampilkan antrean penjahitan aktif.
        </flux:callout>
    </div>
</x-layouts::app>

