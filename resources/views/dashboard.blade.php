@php
    use App\Enums\UserRole;
    use App\Enums\SubmissionStatus;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Beranda</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Ringkasan</h1>
                <p class="mt-1 text-sm text-slate-600">Sistem penguploadan data akreditasi</p>
            </div>
            @if (auth()->user()->role === UserRole::Perti)
                <div class="flex gap-2">
                    <a href="{{ route('perti.reports.pdf') }}" class="ui-btn-primary shrink-0 text-sm">Laporan PDF</a>
                </div>
            @endif
        </div>
    </x-slot>

    @if ($stats['role'] === UserRole::Admin)
        @php
            $summary = $progress['summary'];
            $unitLabels = collect($progress['units'])->pluck('name')->values()->all();
            $unitPercents = collect($progress['units'])->pluck('percent')->values()->all();
        @endphp

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Total Universitas" :value="$stats['pertiCount']" accent="violet" />
            <x-stat-card label="Program studi" :value="$stats['unitCount']" accent="emerald" />
            <x-stat-card label="Rata-rata progress" :value="$summary['average_percent'].'%'" accent="sky" />
            <x-stat-card label="Prodi lengkap" :value="$summary['complete_count']" accent="amber" />
        </div>

        <div class="mb-8 grid gap-6 lg:grid-cols-2">
            <x-chart-card title="Progress per program studi" subtitle="Snapshot kelengkapan unggahan" canvas-id="dashAdminBar" height="280px" />
            <div class="ui-card flex flex-col justify-center p-6 sm:p-8">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Analitik</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Perbandingan antar prodi</h2>
                <p class="mt-2 text-sm text-slate-600">Lihat grafik lengkap, breakdown per kriteria, dan status kelengkapan setiap program studi.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.analytics') }}" class="ui-btn-primary text-sm">Buka grafik lengkap</a>
                    <a href="{{ route('home') }}" class="ui-btn-secondary text-sm">Dashboard publik</a>
                </div>
            </div>
        </div>

        <div class="ui-card overflow-hidden">
            <div class="ui-section-header">
                <h2 class="text-lg font-bold text-slate-900">Modul akreditasi</h2>
                <a href="{{ route('admin.modules.index') }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Kelola modul →</a>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($stats['modules'] as $module)
                    <li class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-violet-50/30">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900">{{ $module->name }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200/80">{{ $module->requirements_count }} syarat</span>
                    </li>
                @empty
                    <li class="ui-empty px-6 text-sm">Belum ada modul.</li>
                @endforelse
            </ul>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new Chart(document.getElementById('dashAdminBar'), {
                    type: 'bar',
                    data: {
                        labels: @json($unitLabels),
                        datasets: [{
                            label: 'Progress (%)',
                            data: @json($unitPercents),
                            backgroundColor: 'rgba(139,92,246,0.85)',
                            borderRadius: 8,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                        },
                    },
                });
            });
        </script>
    @elseif ($stats['role'] === UserRole::Perti)
        @php
            $summary = $progress['summary'];
            $units = $progress['units'];
            $unitLabels = collect($units)->pluck('name')->values()->all();
            $unitPercents = collect($units)->pluck('percent')->values()->all();
            $unitUploaded = collect($units)->pluck('uploaded')->values()->all();
        @endphp

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Program studi" :value="$stats['prodiCount']" accent="emerald" />
            <x-stat-card label="Rata-rata progress" :value="$summary['average_percent'].'%'" accent="sky" />
            <x-stat-card label="Prodi lengkap" :value="$summary['complete_count']" accent="amber" />
            <x-stat-card label="Total dokumen terunggah" :value="$stats['uploadedLatest']" accent="violet" />
        </div>

        <div class="mb-8 grid gap-6 lg:grid-cols-2">
            <x-chart-card title="Perbandingan progress prodi" subtitle="Snapshot kelengkapan unggahan prodi Anda" canvas-id="dashPertiBar" height="280px" />
            <x-chart-card title="Status kelengkapan" subtitle="Distribusi prodi Anda berdasarkan progress" canvas-id="dashPertiStatusDoughnut" height="280px" />
        </div>

        <div class="mb-8 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 ui-card overflow-hidden">
                <div class="ui-section-header flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900">Detail per program studi</h2>
                    <span class="text-xs font-semibold text-slate-500">Total: {{ count($units) }} Program Studi</span>
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
                                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $unit['percent'] }}%"></div>
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
                                    <td colspan="4" class="ui-empty text-sm py-6">Belum ada program studi terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="ui-card flex flex-col justify-center p-6 sm:p-8">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Manajemen</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Kelola akun program studi</h2>
                <p class="mt-2 text-sm text-slate-600">Buat, edit, dan kelola akun program studi di bawah perguruan tinggi Anda.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('perti.prodis.index') }}" class="ui-btn-primary text-sm">Kelola program studi</a>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const labels = @json($unitLabels);
                const percents = @json($unitPercents);
                const uploaded = @json($unitUploaded);
                const summary = @json($summary);

                new Chart(document.getElementById('dashPertiBar'), {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Progress (%)',
                            data: percents,
                            backgroundColor: percents.map(p => p >= 100 ? 'rgba(16,185,129,0.85)' : p > 0 ? 'rgba(59,130,246,0.85)' : 'rgba(148,163,184,0.7)'),
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

                new Chart(document.getElementById('dashPertiStatusDoughnut'), {
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
    @else
        @php
            $uploadedTotal = $stats['totalRequirements'] - $stats['notUploadedCount'];
            $moduleLabels = collect($progress['modules'])->pluck('short_label')->values()->all();
            $modulePercents = collect($progress['modules'])->pluck('percent')->values()->all();
        @endphp

        <div class="mb-8 grid gap-4 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
            <div class="ui-card overflow-hidden p-6 sm:p-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Progress keseluruhan</p>
                        <p class="mt-2 text-4xl font-bold tabular-nums text-slate-900">{{ $stats['progressPercent'] }}%</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $uploadedTotal }} dari {{ $stats['totalRequirements'] }} persyaratan sudah terunggah</p>
                    </div>
                    <div class="flex h-28 w-28 shrink-0 items-center justify-center bg-gradient-to-br from-violet-500 to-indigo-600 text-center text-white shadow-lg shadow-violet-500/25">
                    <div>
                        <p class="text-2xl font-bold">{{ $uploadedTotal }}</p>
                        <p class="text-[11px] font-semibold uppercase tracking-wider opacity-90">Terunggah</p>
                    </div>
                </div>
                </div>
                <div class="mt-6 h-3 overflow-hidden rounded-full bg-slate-100 ring-1 ring-slate-200/80">
                    <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-indigo-500 transition-all" style="width: {{ $stats['progressPercent'] }}%"></div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('unit.progress') }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Lihat grafik lengkap →</a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <x-stat-card label="Belum diunggah" :value="$stats['notUploadedCount']" accent="sky" />
                <x-stat-card label="Total persyaratan" :value="$stats['totalRequirements']" accent="violet" />
            </div>
        </div>

        <div class="mb-8">
            <x-chart-card title="Progress per kriteria" subtitle="Kelengkapan dokumen tiap modul" canvas-id="dashUnitBar" height="260px" />
        </div>

        <div class="mb-4 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Kriteria akreditasi</h2>
                <p class="mt-1 text-sm text-slate-600">Pilih kriteria di sidebar atau kartu di bawah untuk mengunggah dokumen.</p>
            </div>
            <a href="{{ route('unit.reports.pdf') }}" class="ui-btn-secondary shrink-0 text-sm">Laporan PDF</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats['modules'] as $module)
                @php
                    $uploaded = $module->requirements->filter(fn ($req) => $req->submissions->first()?->status === SubmissionStatus::Uploaded)->count();
                    $total = $module->requirements->count();
                    $moduleProgress = $total > 0 ? round(($uploaded / $total) * 100) : 0;
                @endphp
                <a href="{{ route('unit.submissions.module', $module) }}" class="ui-card group overflow-hidden transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-violet-500/10">
                    <div class="border-b border-slate-100 bg-gradient-to-r from-violet-50/80 to-white px-5 py-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-violet-700">{{ $module->shortLabel() }}</p>
                        <h3 class="mt-1 line-clamp-2 text-base font-bold text-slate-900 group-hover:text-violet-700">{{ $module->name }}</h3>
                    </div>
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span class="font-semibold text-slate-700">{{ $uploaded }}/{{ $total }} terunggah</span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-700">{{ $moduleProgress }}%</span>
                        </div>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-violet-500 transition-all" style="width: {{ $moduleProgress }}%"></div>
                        </div>
                        <p class="mt-4 text-sm font-semibold text-violet-600 group-hover:text-violet-500">Lihat lebih detail →</p>
                    </div>
                </a>
            @endforeach
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new Chart(document.getElementById('dashUnitBar'), {
                    type: 'bar',
                    data: {
                        labels: @json($moduleLabels),
                        datasets: [{
                            label: 'Progress (%)',
                            data: @json($modulePercents),
                            backgroundColor: 'rgba(99,102,241,0.85)',
                            borderRadius: 8,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                        },
                    },
                });
            });
        </script>
    @endif
</x-app-layout>
