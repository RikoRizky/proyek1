@php
    $units = $progress['units'];
    $summary = $progress['summary'];
    $unitLabels = collect($units)->pluck('name')->values()->all();
    $unitPercents = collect($units)->pluck('percent')->values()->all();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Admin</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Analitik progress prodi</h1>
            <p class="mt-1 text-sm text-slate-600">Perbandingan kelengkapan unggahan dokumen antar program studi.</p>
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

    @foreach (collect($units)->groupBy('university_name') as $uniName => $uniUnits)
        <div class="ui-card mb-6 overflow-hidden">
            <div class="ui-section-header bg-gradient-to-r from-violet-50/60 to-white">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-violet-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.33l-7.5-5-7.5 5V21m16.5 0H3" />
                    </svg>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $uniName }}</h2>
                        <p class="text-xs text-slate-500 font-semibold">{{ $uniUnits->count() }} Program Studi</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Program Studi</th>
                            <th>Terunggah</th>
                            <th class="w-1/2">Progress</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uniUnits as $unit)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $unit['name'] }}</div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-slate-655 tabular-nums">
                                    {{ $unit['uploaded'] }}/{{ $unit['total'] }} dokumen
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="h-2 flex-1 rounded-full bg-slate-100 overflow-hidden min-w-[6rem]">
                                            <div class="h-full rounded-full bg-violet-600 transition-all duration-300" style="width: {{ $unit['percent'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-750 tabular-nums">{{ $unit['percent'] }}%</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    @if ($unit['percent'] >= 100)
                                        <span class="ui-badge bg-emerald-50 text-emerald-900 ring-emerald-500/20">Lengkap</span>
                                    @elseif ($unit['percent'] > 0)
                                        <span class="ui-badge bg-amber-50 text-amber-900 ring-amber-500/25">Berjalan</span>
                                    @else
                                        <span class="ui-badge bg-slate-100 text-slate-700 ring-slate-500/15">Belum mulai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
