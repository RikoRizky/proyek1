@php
    use App\Enums\UserRole;
    use App\Enums\SubmissionStatus;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Beranda</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Ringkasan</h1>
            <p class="mt-1 text-sm text-slate-600">Sistem penguploadan data akreditasi</p>
        </div>
    </x-slot>

    @if ($stats['role'] === UserRole::Admin)
        @php
            $summary = $progress['summary'];
            $unitLabels = collect($progress['units'])->pluck('name')->values()->all();
            $unitPercents = collect($progress['units'])->pluck('percent')->values()->all();
        @endphp

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Total pengguna" :value="$stats['usersCount']" accent="violet" />
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
                        <p class="mt-4 text-sm font-semibold text-violet-600 group-hover:text-violet-500">Unggah dokumen →</p>
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
