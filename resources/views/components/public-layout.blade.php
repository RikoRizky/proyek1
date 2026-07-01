<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' - SILADATA (Sistem Layanan Dokumen Akreditasi)' : 'SILADATA (Sistem Layanan Dokumen Akreditasi)' }}</title>

    <!-- Favicons & Apple Touch Icons -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logoname.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logoname.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logoname.png') }}?v=2">

    <!-- Meta SEO & Keywords -->
    <meta name="description" content="SILADATA (Sistem Layanan Dokumen Akreditasi) adalah sistem layanan dokumen akreditasi perguruan tinggi untuk Lembaga Akreditasi Mandiri (LAM) yang menilai mutu pendidikan tinggi di Indonesia. Memudahkan pengunggahan data, manajemen, dan monitoring kelengkapan dokumen akreditasi secara terstruktur.">
    <meta name="keywords" content="SILADATA, Sistem Layanan Dokumen Akreditasi, akreditasi perguruan tinggi, upload data akreditasi, LAM, Lembaga Akreditasi Mandiri, mutu pendidikan tinggi Indonesia, akreditasi LAM, dokumen akreditasi prodi, monitoring akreditasi, unggah data, perguruan tinggi">
    <meta name="author" content="SILADATA">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="SILADATA (Sistem Layanan Dokumen Akreditasi)">
    <meta property="og:description" content="SILADATA membantu perguruan tinggi mengelola dan mempersiapkan dokumen akreditasi sesuai kebutuhan Lembaga Akreditasi Mandiri (LAM).">
    <meta property="og:image" content="{{ asset('images/logoname.png') }}?v=2">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body class="font-sans text-slate-900 antialiased">
    <div class="relative min-h-screen flex flex-col bg-gradient-to-br from-slate-100 via-violet-50/40 to-indigo-50/30">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-violet-200/25 via-transparent to-transparent"></div>

        <header class="relative border-b border-white/60 bg-white/70 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img
                        class="flex h-11 w-11 items-center justify-center rounded-2xl shadow-lg shadow-violet-500/30 object-contain bg-white"
                        src="{{ asset('images/logoname.png') }}"
                        alt="SILADATA"
                    />
                    <div>
                        <p class="text-sm font-bold text-slate-900">SILADATA (Sistem Layanan Dokumen Akreditasi)</p>
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

        <main class="relative flex-grow">
            {{ $slot }}
        </main>

        {{-- Footer Gelap --}}
        <footer class="bg-[#101f3c] text-slate-300">
            <div class="mx-auto max-w-7xl px-6 py-16">
                <div class="grid gap-12 md:grid-cols-12">
                    {{-- Logo & Deskripsi --}}
                    <div class="md:col-span-4 flex flex-col justify-between">
                        <div>
                            <span class="text-3xl font-extrabold tracking-wider text-white">SILADATA</span>
                            <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-400">
                                SILADATA (Sistem Layanan Dokumen Akreditasi) merupakan platform
                                digital yang membantu perguruan tinggi dalam mengelola,
                                menyimpan, dan mempersiapkan dokumen akreditasi sesuai
                                kebutuhan Lembaga Akreditasi Mandiri (LAM).
                            </p>
                        </div>

                        {{-- Hubungi Kami --}}
                        <div class="mt-8">
                            <p class="text-sm font-semibold uppercase tracking-wider text-white">Hubungi Kami</p>
                            <a href="https://wa.me/62881023300457" target="_blank" rel="noopener noreferrer" 
                               class="mt-3 inline-flex items-center gap-2 text-slate-300 hover:text-white transition group">
                                <!-- WhatsApp Icon Container -->
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#25d366]/20 text-[#25d366] group-hover:bg-[#25d366]/30 transition">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 2c-5.524 0-10 4.48-10 10 0 2.16.69 4.19 1.94 5.86L2.9 22l4.3-1.13c1.57.87 3.15 1.34 4.83 1.34 5.52 0 10-4.48 10-10s-4.48-10-10-10zm.07 17.5c-1.63 0-3.15-.45-4.48-1.24l-.32-.19-2.58.68.69-2.52-.21-.34c-.87-1.39-1.34-3.01-1.34-4.7 0-4.85 3.95-8.8 8.8-8.8s8.8 3.95 8.8 8.8-3.95 8.8-8.8 8.82z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium">+62 881-0233-00457 (Chat WA)</span>
                            </a>
                        </div>
                    </div>

                    {{-- Navigasi Grid --}}
                    <div class="grid grid-cols-2 gap-8 sm:grid-cols-4 md:col-span-8">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">
                                Seputar SILADATA
                            </h3>
                            <ul class="mt-4 space-y-2.5 text-sm">
                                <li><a href="#about" class="text-slate-400 hover:text-white transition">Tentang Kami</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">SILADATA Community</a></li>
                                <li>
                                    <a href="#" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white transition">
                                        Karier
                                        <span class="rounded bg-indigo-500/20 px-1.5 py-0.5 text-[10px] font-medium text-indigo-300">We are hiring!</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">
                                Produk
                            </h3>
                            <ul class="mt-4 space-y-2.5 text-sm">
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Dashboard Akreditasi</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Cloud Storage</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Monitoring Progress</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Dokumen LAM</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">
                                Dukungan Layanan
                            </h3>
                            <ul class="mt-4 space-y-2.5 text-sm">
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Service Level Agreement</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Kebijakan Data Pribadi</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Pedoman Media Siber</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">
                                Informasi
                            </h3>
                            <ul class="mt-4 space-y-2.5 text-sm">
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Artikel</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Acara</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Ebook</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition">Panduan Akreditasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Bottom Footer --}}
                <div class="mt-12 border-t border-slate-800 pt-8">
                    <div class="flex flex-col items-center justify-between gap-4 text-center md:flex-row">
                        <p class="text-sm text-slate-500">
                            © {{ date('Y') }} SILADATA. Seluruh hak cipta dilindungi.
                        </p>
                        <div class="flex items-center gap-6 text-sm">
                            <a href="#" class="text-slate-500 hover:text-slate-400 transition">
                                Kebijakan Privasi
                            </a>
                            <a href="#" class="text-slate-500 hover:text-slate-400 transition">
                                Syarat & Ketentuan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

