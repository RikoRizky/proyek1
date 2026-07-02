<x-app-layout>
    @php
        $modules = $progress['modules'];
        $moduleLabels = collect($modules)->pluck('short_label')->values()->all();
        $modulePercents = collect($modules)->pluck('percent')->values()->all();
        $moduleUploaded = collect($modules)->pluck('uploaded')->values()->all();
        $moduleTotals = collect($modules)->pluck('total')->values()->all();
    @endphp

    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Perguruan Tinggi</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Progress: {{ $prodi->name }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $progress['uploaded'] }}/{{ $progress['total'] }} dokumen terunggah ({{ $progress['percent'] }}%)</p>
            </div>
            <a href="{{ route('dashboard') }}" class="ui-btn-secondary text-sm">← Ringkasan</a>
        </div>
    </x-slot>

    <div class="mb-8 grid gap-4 sm:grid-cols-3">
        <x-stat-card label="Progress keseluruhan" :value="$progress['percent'].'%'" accent="emerald" />
        <x-stat-card label="Sudah terunggah" :value="$progress['uploaded']" accent="violet" />
        <x-stat-card label="Belum terunggah" :value="$progress['total'] - $progress['uploaded']" accent="sky" />
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <x-chart-card title="Progress per kriteria" subtitle="Persentase kelengkapan tiap modul akreditasi {{ $prodi->name }}" canvas-id="pertiModulesBar" />
        <x-chart-card title="Ringkasan keseluruhan" subtitle="Dokumen terunggah vs belum" canvas-id="pertiOverallDoughnut" height="280px" />
    </div>

    <div class="ui-card overflow-hidden">
        <div class="ui-section-header flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Detail per kriteria</h2>
            <span class="text-xs font-semibold text-slate-500">{{ $prodi->name }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Terunggah</th>
                        <th>Progress</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)
                        <tr>
                            <td>
                                <p class="font-semibold text-slate-900">{{ $module['short_label'] }}</p>
                                <p class="text-xs text-slate-500">{{ $module['name'] }}</p>
                            </td>
                            <td class="tabular-nums">{{ $module['uploaded'] }}/{{ $module['total'] }}</td>
                            <td class="min-w-[10rem]">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 flex-1 rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $module['percent'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold tabular-nums">{{ $module['percent'] }}%</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('perti.prodis.modul', [$prodi->id, $module['module_id']]) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">
                                    Lihat detail →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels = @json($moduleLabels);
            const percents = @json($modulePercents);
            const uploaded = @json($moduleUploaded);
            const totals = @json($moduleTotals);
            const overall = @json($progress);

            new Chart(document.getElementById('pertiModulesBar'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Progress (%)',
                        data: percents,
                        backgroundColor: percents.map(p => p >= 100 ? 'rgba(16,185,129,0.85)' : p > 0 ? 'rgba(99,102,241,0.85)' : 'rgba(148,163,184,0.7)'),
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
                                afterLabel: (ctx) => uploaded[ctx.dataIndex] + '/' + totals[ctx.dataIndex] + ' dokumen',
                            },
                        },
                    },
                    scales: {
                        y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                    },
                },
            });

            new Chart(document.getElementById('pertiOverallDoughnut'), {
                type: 'doughnut',
                data: {
                    labels: ['Terunggah', 'Belum'],
                    datasets: [{
                        data: [overall.uploaded, overall.total - overall.uploaded],
                        backgroundColor: ['#10b981', '#e2e8f0'],
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
