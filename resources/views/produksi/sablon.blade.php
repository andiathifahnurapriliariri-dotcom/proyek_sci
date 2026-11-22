<x-layouts::app title="Dashboard Sablon">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">
                <flux:icon name="paint-brush" class="text-purple-600 size-6" />
            </div>
            <div>
                <flux:heading size="xl">Dashboard Sablon & Bordir</flux:heading>
                <flux:subheading>Divisi Printing — COTTON.id</flux:subheading>
            </div>
        </div>
        <flux:callout variant="warning" icon="information-circle">
            Modul Sablon sedang dalam pengembangan. Halaman ini akan menampilkan antrean sablon dan bordir aktif.
        </flux:callout>
    </div>
</x-layouts::app>

