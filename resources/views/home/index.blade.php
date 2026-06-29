@php
    /** @var array<string, mixed> $progress */
    $summary = $progress['summary'];
    $units = $progress['units'];
    $unitLabels = collect($units)->pluck('name')->values()->all();
    $unitPercents = collect($units)->pluck('percent')->values()->all();
    $unitUploaded = collect($units)->pluck('uploaded')->values()->all();
@endphp

<x-public-layout title="Dashboard Akreditasi">
    <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Dashboard</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Progress unggahan akreditasi</h1>
        <p class="mt-2 max-w-3xl text-sm text-slate-600">Ringkasan publik kelengkapan dokumen seluruh program studi. Login diperlukan untuk mengunggah atau mengelola berkas.</p>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Program studi" :value="$summary['unit_count']" accent="violet" />
        <x-stat-card label="Rata-rata progress" :value="$summary['average_percent'].'%'" accent="indigo" />
        <x-stat-card label="Lengkap (100%)" :value="$summary['complete_count']" accent="emerald" />
        <x-stat-card label="Total persyaratan" :value="$progress['total_requirements']" accent="sky" />
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-2">
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
</x-public-layout>
