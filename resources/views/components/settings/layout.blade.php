<div class="rounded-2xl border border-gray-200/80 bg-white p-6 sm:p-8 shadow-sm">
    <div class="flex items-start max-md:flex-col gap-6">
        <div class="w-full pb-4 md:w-[220px] shrink-0 border-b md:border-b-0 md:border-r border-gray-100 pr-0 md:pr-6">
            <flux:navlist aria-label="{{ __('Settings') }}">
                <flux:navlist.item :href="route('profile.edit')" wire:navigate>{{ __('Profil Akun') }}</flux:navlist.item>
                <flux:navlist.item :href="route('security.edit')" wire:navigate>{{ __('Keamanan & Sandi') }}</flux:navlist.item>
                <flux:navlist.item :href="route('appearance.edit')" wire:navigate>{{ __('Tampilan') }}</flux:navlist.item>
            </flux:navlist>
        </div>

        <div class="flex-1 self-stretch min-w-0">
            <flux:heading size="lg" class="font-extrabold text-gray-900">{{ $heading ?? '' }}</flux:heading>
            <flux:subheading class="text-xs text-gray-500 mt-0.5">{{ $subheading ?? '' }}</flux:subheading>

            <div class="mt-6 w-full max-w-xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
