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
    <body class="min-h-screen h-full font-sans text-slate-900 antialiased">
        <div
            x-data="{ sidebarOpen: false }"
            @keydown.window.escape="sidebarOpen = false"
            class="flex h-full min-h-screen w-full flex-col overflow-hidden bg-gradient-to-br from-slate-100 via-violet-50/40 to-slate-100 lg:h-screen lg:min-h-0 lg:flex-row"
        >
            @include('layouts.sidebar')

            <div class="hidden shrink-0 select-none lg:block lg:w-64" aria-hidden="true"></div>

            <div class="flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden">
                @isset($header)
                    <header class="z-20 shrink-0 border-b border-white/60 bg-white/90 shadow-sm backdrop-blur-md">
                        <div class="flex w-full items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                            <div class="min-w-0 flex-1">
                                {{ $header }}
                            </div>
                            <div class="hidden shrink-0 items-center gap-2 sm:flex">
                                <a href="{{ route('profile.edit') }}" class="ui-btn-ghost text-xs font-medium text-slate-600">Profil</a>
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

                <div class="flex min-h-0 flex-1 flex-col overflow-hidden bg-slate-200/80">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
