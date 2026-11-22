<x-layouts::app title="Dashboard Pemotongan Kain">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100">
                <flux:icon name="scissors" class="text-orange-600 size-6" />
            </div>
            <div>
                <flux:heading size="xl">Dashboard Pemotongan Kain</flux:heading>
                <flux:subheading>Divisi Cutting — COTTON.id</flux:subheading>
            </div>
        </div>
        <flux:callout variant="warning" icon="information-circle">
            Modul Pemotongan Kain sedang dalam pengembangan. Halaman ini akan menampilkan antrean pemotongan kain aktif.
        </flux:callout>
    </div>
</x-layouts::app>

