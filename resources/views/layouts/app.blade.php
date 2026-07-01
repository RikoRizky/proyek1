<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900">
        <div
            x-data="{ sidebarOpen: false }"
            @keydown.window.escape="sidebarOpen = false"
            class="ui-page-shell flex min-h-screen flex-col lg:h-screen lg:flex-row lg:overflow-hidden"
        >
            @include('layouts.sidebar')

            {{-- In-flow gutter: fixed sidebar does not reserve width in flex layout; this matches lg:w-64 aside --}}
            <div class="hidden shrink-0 select-none lg:block lg:w-64" aria-hidden="true"></div>

            <div class="flex min-h-0 min-w-0 flex-1 flex-col">
                @isset($header)
                    <header class="sticky top-0 z-20 shrink-0 border-b border-white/60 bg-white/80 shadow-sm backdrop-blur-md">
                        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                            <div class="min-w-0 flex-1">
                                {{ $header }}
                            </div>
                            <div class="hidden shrink-0 items-center gap-2 sm:flex">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-xl px-3 py-1.5 ui-btn-ghost text-xs font-medium text-slate-600 transition hover:bg-slate-50">
                                    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="h-6 w-6 rounded-full object-cover ring-1 ring-slate-200 bg-slate-100">
                                    <span>Profil</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="rounded-xl px-3 py-2 text-xs font-medium text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>
                @endisset

                <main class="mx-auto min-h-0 w-full max-w-7xl flex-1 overflow-y-auto px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
                    @if (session('status'))
                        <div class="ui-alert-success" role="status">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
