@php
    /** @var array<string, mixed> $progress */
    $summary = $progress['summary'];
    $units = $progress['units'];
    $unitLabels = collect($units)->pluck('name')->values()->all();
    $unitPercents = collect($units)->pluck('percent')->values()->all();
    $unitUploaded = collect($units)->pluck('uploaded')->values()->all();
@endphp

<x-public-layout title="Dashboard Akreditasi">
    <!-- <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Dashboard</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Progress unggahan akreditasi</h1>
        <p class="mt-2 max-w-3xl text-sm text-slate-600">Ringkasan publik kelengkapan dokumen seluruh program studi. Login diperlukan untuk mengunggah atau mengelola berkas.</p>
    </div> -->

    <!-- <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Program studi" :value="$summary['unit_count']" accent="violet" />
        <x-stat-card label="Rata-rata progress" :value="$summary['average_percent'].'%'" accent="indigo" />
        <x-stat-card label="Lengkap (100%)" :value="$summary['complete_count']" accent="emerald" />
        <x-stat-card label="Total persyaratan" :value="$progress['total_requirements']" accent="sky" />
    </div> -->

    {{-- Tentang SILADATA (Full Width) --}}
    <div class="relative bg-white/70 border-b border-slate-200/60 py-16 backdrop-blur-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2 items-center">
                <div>
                    <span class="inline-flex rounded-full bg-violet-100 px-4 py-1 text-sm font-semibold text-violet-700">
                        Tentang SILADATA
                    </span>

                    <h2 class="mt-4 text-3xl font-bold text-slate-900 leading-tight">
                        Sistem Layanan Dokumen Akreditasi Perguruan Tinggi
                    </h2>

                    <p class="mt-4 text-slate-600 leading-relaxed">
                        SILADATA (Sistem Layanan Dokumen Akreditasi) merupakan platform
                        terintegrasi yang membantu perguruan tinggi dalam mengelola dan
                        mempersiapkan dokumen akreditasi sesuai kebutuhan
                        <strong>Lembaga Akreditasi Mandiri (LAM)</strong>.
                        Dengan sistem penyimpanan dan pengunggahan dokumen yang
                        terstruktur, SILADATA mempermudah proses pengumpulan,
                        pengelolaan, serta pemantauan kelengkapan dokumen sehingga
                        institusi dapat lebih siap menghadapi proses akreditasi secara
                        efektif, efisien, dan terdokumentasi dengan baik.
                    </p>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-500">✓</span>
                            <span class="text-slate-600">Terintegrasi dengan kebutuhan LAM</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-emerald-500">✓</span>
                            <span class="text-slate-600">Penyimpanan dokumen terpusat</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-emerald-500">✓</span>
                            <span class="text-slate-600">Monitoring progres akreditasi</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-emerald-500">✓</span>
                            <span class="text-slate-600">Keamanan dokumen terjamin</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-violet-50 p-6 shadow-sm border border-violet-100/50">
                        <div class="text-4xl">📁</div>
                        <h3 class="mt-3 font-bold text-slate-800">Dokumen Akreditasi</h3>
                    </div>

                    <div class="rounded-2xl bg-sky-50 p-6 shadow-sm border border-sky-100/50">
                        <div class="text-4xl">☁️</div>
                        <h3 class="mt-3 font-bold text-slate-800">Cloud Storage</h3>
                    </div>

                    <div class="rounded-2xl bg-emerald-50 p-6 shadow-sm border border-emerald-100/50">
                        <div class="text-4xl">📊</div>
                        <h3 class="mt-3 font-bold text-slate-800">Monitoring Progress</h3>
                    </div>

                    <div class="rounded-2xl bg-amber-50 p-6 shadow-sm border border-amber-100/50">
                        <div class="text-4xl">🔒</div>
                        <h3 class="mt-3 font-bold text-slate-800">Keamanan Data</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Banner Call-To-Action (Sesuai Gambar) --}}
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[32px] bg-violet-700 px-8 py-16 text-center text-white sm:px-12 sm:py-20 shadow-xl">
            <!-- Background Watermark/Deco pattern di sisi kanan -->
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 hidden md:block select-none pointer-events-none">
                <svg class="h-full w-full" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 15 L80 30 L80 70 L50 85 L20 70 L20 30 Z" stroke="currentColor" stroke-width="4" />
                    <path d="M35 40 H65 M35 50 H65 M35 60 H55" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                </svg>
            </div>

            <div class="relative z-10 mx-auto max-w-3xl">
                <h2 class="text-3xl font-bold text-white sm:text-4xl leading-tight">
                    Siap Bertransformasi Bersama SILADATA?
                </h2>

                <p class="mt-4 text-base text-violet-100 sm:text-lg">
                    Lebih dari 1.300 kampus sudah bergabung. Sudah waktunya kampus Anda menjadi bagian dari ekosistem digital akreditasi Indonesia
                </p>

                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('discussion') }}"
                       class="inline-flex items-center justify-center rounded-xl border-2 border-white px-8 py-3.5 text-sm font-bold text-white outline outline-2 outline-offset-2 outline-white/50 transition-all duration-200 hover:bg-white hover:text-slate-900 hover:outline-white hover:shadow-lg hover:scale-[1.03] active:scale-[0.97] w-full sm:w-auto">
                        Jadwalkan Diskusi
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Gelap (Sesuai Gambar) --}}

    {{-- Tombol WhatsApp Melayang (Floating Button) --}}
    

    <!-- <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <x-chart-card title="Perbandingan progress prodi" subtitle="Persentase kelengkapan dokumen per program studi" canvas-id="publicUnitsBar" />
        <x-chart-card title="Status kelengkapan" subtitle="Distribusi prodi berdasarkan progress" canvas-id="publicStatusDoughnut" height="280px" />
    </div>

    <div class="ui-card overflow-hidden">
        <div class="ui-section-header">
            <h2 class="text-lg font-bold text-slate-900">Detail per program studi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Program studi</th>
                        <th>Terunggah</th>
                        <th>Progress</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $unit['name'] }}</td>
                            <td class="tabular-nums text-slate-600">{{ $unit['uploaded'] }}/{{ $unit['total'] }}</td>
                            <td class="min-w-[12rem]">
                                <div class="flex items-center gap-3">
                                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-violet-500" style="width: {{ $unit['percent'] }}%"></div>
                                    </div>
                                    <span class="w-10 text-right text-sm font-bold tabular-nums text-slate-700">{{ $unit['percent'] }}%</span>
                                </div>
                            </td>
                            <td>
                                @if ($unit['percent'] >= 100)
                                    <span class="ui-badge bg-emerald-50 text-emerald-900 ring-emerald-500/20">Lengkap</span>
                                @elseif ($unit['percent'] > 0)
                                    <span class="ui-badge bg-amber-50 text-amber-900 ring-amber-500/25">Berjalan</span>
                                @else
                                    <span class="ui-badge bg-slate-100 text-slate-700 ring-slate-500/15">Belum mulai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="ui-empty text-sm">Belum ada program studi terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels = @json($unitLabels);
            const percents = @json($unitPercents);
            const uploaded = @json($unitUploaded);
            const summary = @json($summary);

            new Chart(document.getElementById('publicUnitsBar'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Progress (%)',
                        data: percents,
                        backgroundColor: percents.map(p => p >= 100 ? 'rgba(16,185,129,0.85)' : p > 0 ? 'rgba(139,92,246,0.85)' : 'rgba(148,163,184,0.7)'),
                        borderRadius: 8,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                afterLabel: (ctx) => {
                                    const i = ctx.dataIndex;
                                    return uploaded[i] + ' dokumen terunggah';
                                },
                            },
                        },
                    },
                    scales: {
                        y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                        x: { ticks: { maxRotation: 45, minRotation: 0 } },
                    },
                },
            });

            new Chart(document.getElementById('publicStatusDoughnut'), {
                type: 'doughnut',
                data: {
                    labels: ['Lengkap', 'Berjalan', 'Belum mulai'],
                    datasets: [{
                        data: [summary.complete_count, summary.in_progress_count, summary.empty_count],
                        backgroundColor: ['#10b981', '#8b5cf6', '#cbd5e1'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        });
    </script>
    -->
</x-public-layout>