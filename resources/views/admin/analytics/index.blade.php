@php
    $units = $progress['units'];
    $summary = $progress['summary'];
    $unitLabels = collect($units)->pluck('name')->values()->all();
    $unitPercents = collect($units)->pluck('percent')->values()->all();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Admin</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Analitik progress prodi</h1>
                <p class="mt-1 text-sm text-slate-600">Perbandingan kelengkapan unggahan dokumen antar program studi.</p>
            </div>
            <a href="{{ route('admin.home') }}" class="ui-btn-secondary text-sm">← Panel admin</a>
        </div>
    </x-slot>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Program studi" :value="$summary['unit_count']" accent="violet" />
        <x-stat-card label="Rata-rata progress" :value="$summary['average_percent'].'%'" accent="indigo" />
        <x-stat-card label="Lengkap (100%)" :value="$summary['complete_count']" accent="emerald" />
        <x-stat-card label="Belum mulai" :value="$summary['empty_count']" accent="sky" />
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <x-chart-card title="Perbandingan progress prodi" subtitle="Diurutkan berdasarkan persentase kelengkapan" canvas-id="adminUnitsBar" />
        <x-chart-card title="Status kelengkapan" subtitle="Lengkap vs berjalan vs belum mulai" canvas-id="adminStatusPie" height="280px" />
    </div>

    @foreach ($units as $unit)
        <div class="ui-card mb-6 overflow-hidden">
            <div class="ui-section-header">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $unit['name'] }}</h2>
                    <p class="text-sm text-slate-600">{{ $unit['uploaded'] }}/{{ $unit['total'] }} dokumen · {{ $unit['percent'] }}%</p>
                </div>
                @if ($unit['percent'] >= 100)
                    <span class="ui-badge bg-emerald-50 text-emerald-900 ring-emerald-500/20">Lengkap</span>
                @elseif ($unit['percent'] > 0)
                    <span class="ui-badge bg-amber-50 text-amber-900 ring-amber-500/25">Berjalan</span>
                @else
                    <span class="ui-badge bg-slate-100 text-slate-700 ring-slate-500/15">Belum mulai</span>
                @endif
            </div>
            <div class="grid gap-3 px-6 py-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($unit['modules'] as $module)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-violet-700">{{ $module['short_label'] }}</p>
                        <p class="mt-1 line-clamp-2 text-sm font-semibold text-slate-900">{{ $module['name'] }}</p>
                        <div class="mt-3 flex items-center justify-between text-xs font-semibold text-slate-600">
                            <span>{{ $module['uploaded'] }}/{{ $module['total'] }}</span>
                            <span>{{ $module['percent'] }}%</span>
                        </div>
                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-200">
                            <div class="h-full rounded-full bg-violet-500" style="width: {{ $module['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels = @json($unitLabels);
            const percents = @json($unitPercents);
            const summary = @json($summary);

            new Chart(document.getElementById('adminUnitsBar'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Progress (%)',
                        data: percents,
                        backgroundColor: 'rgba(139,92,246,0.85)',
                        borderRadius: 8,
                    }],
                },
                options: {
                    indexAxis: labels.length > 4 ? 'y' : 'x',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, max: labels.length > 4 ? 100 : undefined, ticks: labels.length > 4 ? { callback: v => v + '%' } : {} },
                        y: { beginAtZero: true, max: labels.length > 4 ? undefined : 100, ticks: labels.length > 4 ? {} : { callback: v => v + '%' } },
                    },
                },
            });

            new Chart(document.getElementById('adminStatusPie'), {
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
</x-app-layout>
