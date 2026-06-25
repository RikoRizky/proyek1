<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard Akreditasi' }} — {{ config('app.name', 'Akreditasi') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body class="font-sans text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-100 via-violet-50/40 to-indigo-50/30">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-violet-200/25 via-transparent to-transparent"></div>

        <header class="relative border-b border-white/60 bg-white/70 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 text-sm font-bold text-white shadow-lg shadow-violet-500/30">SP</div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Sistem Penguploadan Akreditasi</p>
                        <p class="text-xs text-slate-500">Dashboard progress program studi</p>
                    </div>
                </a>
                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="ui-btn-primary text-sm">Masuk aplikasi</a>
                    @else
                        <a href="{{ route('login') }}" class="ui-btn-primary text-sm">Masuk</a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="relative mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        <footer class="relative border-t border-slate-200/80 bg-white/50 py-6 text-center text-xs text-slate-500">
            © {{ date('Y') }} — Dashboard luaran akreditasi program studi
        </footer>
    </div>
</body>
</html>
