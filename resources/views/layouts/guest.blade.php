<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-100 via-violet-50/50 to-indigo-50/40">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-violet-200/30 via-transparent to-transparent"></div>
            <div class="pointer-events-none absolute -left-32 top-1/4 h-96 w-96 rounded-full bg-violet-400/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-indigo-400/10 blur-3xl"></div>

            <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-10 sm:px-6">
                <a href="{{ url('/') }}" class="mb-8 flex flex-col items-center gap-3 transition hover:opacity-90">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 text-lg font-bold text-white shadow-xl shadow-violet-500/30 ring-4 ring-white/50">
                        SP
                    </div>
                    <span class="text-center text-sm font-semibold text-slate-700">Sistem Penguploadan Data Akreditasi</span>
                </a>

                <div class="w-full max-w-md">
                    <div class="rounded-3xl border border-white/60 bg-white/80 p-8 shadow-2xl shadow-slate-300/40 ring-1 ring-slate-200/60 backdrop-blur-xl sm:p-10">
                        {{ $slot }}
                    </div>
                    <p class="mt-6 text-center text-xs text-slate-500">© {{ date('Y') }} — Lingkungan terlindungi</p>
                </div>
            </div>
        </div>

    </body>
</html>
