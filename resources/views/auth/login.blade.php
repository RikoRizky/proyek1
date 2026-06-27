<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-xl font-bold tracking-tight text-slate-900">Masuk</h1>
        <p class="mt-2 text-sm text-slate-600">Sistem penguploadan data akreditasi</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
                <x-input-label for="email" value="Email" class="text-sm font-medium text-slate-700" />
                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fas fa-envelope text-sm"></i>
                    </div>
                    <x-text-input id="email" 
                        class="block w-full rounded-xl border-slate-200 bg-white/80 pl-10 pr-4 py-3 text-sm placeholder:text-slate-400 focus:border-violet-400 focus:ring-2 focus:ring-violet-400/30 transition-shadow duration-200" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="nama@institusi.ac.id" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" value="Kata sandi" class="text-sm font-medium text-slate-700" />
                </div>
                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fas fa-lock text-sm"></i>
                    </div>
                    <x-text-input id="password" 
                        class="block w-full rounded-xl border-slate-200 bg-white/80 pl-10 pr-4 py-3 text-sm placeholder:text-slate-400 focus:border-violet-400 focus:ring-2 focus:ring-violet-400/30 transition-shadow duration-200" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-violet-600 shadow-sm focus:ring-violet-500/30" name="remember">
                <span class="text-sm text-slate-600">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-violet-600 hover:text-violet-500" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ui-btn-secondary order-2 flex-1 justify-center sm:order-1 sm:flex-initial">Daftar</a>
            @endif
            <x-primary-button class="order-1 flex-1 justify-center sm:order-2 sm:flex-initial">
                Masuk
            </x-primary-button>
        </div>
        @unless (Route::has('register'))
            <p class="pt-4 text-center text-xs leading-relaxed text-slate-500">
                Akun program studi dibuat oleh <strong>administrator</strong>. Hubungi admin institusi untuk mendapatkan akses.
            </p>
        @endunless
    </form>
</x-guest-layout>
