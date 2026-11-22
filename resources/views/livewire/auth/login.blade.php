<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen antialiased" style="background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">

        {{-- Card Utama Login --}}
        <div class="w-full max-w-md">
            <div class="rounded-2xl bg-white shadow-xl overflow-hidden border border-gray-100">
                <div class="px-10 pt-8 pb-10">

                    {{-- Logo COTTON.id --}}
                    <div class="flex flex-col items-center mb-6">
                        <div class="mb-4 mt-2">
                            <img src="{{ asset('images/logo-cotton.jpg') }}" alt="COTTON.id Logo" class="rounded-full object-cover shadow-md" style="width:56px;height:56px;border-radius:9999px;object-fit:cover;">
                        </div>

                        {{-- Badge Selamat Datang --}}
                        <div class="flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 mb-4">
                            <span class="inline-block h-2 w-2 rounded-full bg-green-500"></span>
                            <span class="text-xs font-medium text-gray-600">Selamat Datang Sobat COTTON.id</span>
                        </div>

                        {{-- Judul --}}
                        <h1 class="text-center text-2xl font-bold text-gray-900 leading-tight">
                            Masuk <span class="font-extrabold"></span>
                        </h1>
                        <p class="mt-2 text-center text-sm text-gray-500 leading-snug">
                            
                        </p>
                    </div>

                    {{-- Session Status --}}
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Form Login --}}
                    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
                        @csrf

                        {{-- ID Karyawan / Username --}}
                        <div>
                            <label for="username" class="block text-sm font-semibold text-gray-800 mb-1.5">
                                ID Karyawan / Username
                            </label>
                            <div class="flex items-center border border-gray-300 rounded-lg bg-white shadow-sm focus-within:border-black focus-within:ring-2 focus-within:ring-black/10 transition @error('username') border-red-400 @enderror">
                                {{-- Ikon pengguna --}}
                                <span class="flex items-center pl-3 pr-2 text-gray-400 pointer-events-none shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </span>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Masukkan Username"
                                    class="flex-1 bg-transparent py-2.5 pr-4 text-sm outline-none placeholder-gray-400"
                                    style="color: #111111 !important;"
                                />
                            </div>
                            @error('username')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kata Sandi --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-sm font-semibold text-gray-800">
                                    Kata Sandi
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-gray-500 hover:text-black underline underline-offset-2 transition" wire:navigate>
                                        Lupa sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="flex items-center border border-gray-300 rounded-lg bg-white shadow-sm focus-within:border-black focus-within:ring-2 focus-within:ring-black/10 transition @error('password') border-red-400 @enderror" x-data="{ show: false }">
                                {{-- Ikon gembok --}}
                                <span class="flex items-center pl-3 pr-2 text-gray-400 pointer-events-none shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    name="password"
                                    :type="show ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="flex-1 bg-transparent py-2.5 text-sm outline-none placeholder-gray-400"
                                    style="color: #111111 !important;"
                                />
                                {{-- Tombol mata (show/hide) --}}
                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="flex items-center px-3 text-gray-400 hover:text-gray-700 transition shrink-0"
                                    tabindex="-1"
                                    :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                >
                                    {{-- Ikon mata terbuka --}}
                                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    {{-- Ikon mata dicoret --}}
                                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Login --}}
                        <button
                            type="submit"
                            class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-black py-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-900 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-all"
                        >
                            Masuk ke Dashboard
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </form>

                </div>

                {{-- Footer Card --}}
                <div class="border-t border-gray-100 bg-gray-50 px-10 py-4">
                    <div class="flex flex-col items-center gap-1">
                        <p class="flex items-center gap-1.5 text-xs text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            COTTON.id Apparel Production v2.4
                        </p>
                        <p class="text-xs text-gray-400">
                            Hak Akses Role Admin &amp; Tim Produksi • Enkripsi TLS 1.3
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
