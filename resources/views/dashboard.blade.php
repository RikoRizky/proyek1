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

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <x-stat-card label="Program studi" :value="$stats['prodiCount']" accent="emerald" />
            <x-stat-card label="Rata-rata progress" :value="$summary['average_percent'].'%'" accent="sky" />
            <x-stat-card label="Prodi lengkap" :value="$summary['complete_count']" accent="amber" />
            <x-stat-card label="Perlu divalidasi" :value="$stats['pendingValidationCount']" accent="rose" />
            <x-stat-card label="Total terunggah" :value="$stats['uploadedLatest']" accent="violet" />
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

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Progress Keseluruhan -->
            <div class="ui-card relative overflow-hidden p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Progress Keseluruhan</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-xs font-extrabold text-emerald-600">
                        %
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">{{ $stats['progressPercent'] }}%</p>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $stats['progressPercent'] }}%"></div>
                </div>
                <p class="mt-2 text-xs font-medium text-slate-500">{{ $uploadedTotal }} dari {{ $stats['totalRequirements'] }} persyaratan</p>
            </div>

            <!-- Sudah Terunggah -->
            <div class="ui-card relative overflow-hidden p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Sudah Terunggah</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">{{ $uploadedTotal }}</p>
                <p class="mt-4 text-xs font-medium text-slate-500">Dokumen telah diunggah ke sistem</p>
            </div>

            <!-- Perlu Revisi -->
            <div class="ui-card relative overflow-hidden p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg {{ $stats['revisionCount'] > 0 ? 'ring-2 ring-rose-500/20 bg-rose-50/30' : '' }}">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider {{ $stats['revisionCount'] > 0 ? 'text-rose-600' : 'text-slate-500' }}">Perlu Revisi</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl {{ $stats['revisionCount'] > 0 ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-400' }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight {{ $stats['revisionCount'] > 0 ? 'text-rose-600' : 'text-slate-900' }}">{{ $stats['revisionCount'] }}</p>
                <p class="mt-4 text-xs {{ $stats['revisionCount'] > 0 ? 'font-semibold text-rose-600' : 'font-medium text-slate-500' }}">
                    {{ $stats['revisionCount'] > 0 ? 'Dokumen memerlukan perbaikan' : 'Tidak ada catatan revisi' }}
                </p>
            </div>

            <!-- Belum Diunggah -->
            <div class="ui-card relative overflow-hidden p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Belum Diunggah</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">{{ $stats['notUploadedCount'] }}</p>
                <p class="mt-4 text-xs font-medium text-slate-500">Dari {{ $stats['totalRequirements'] }} total persyaratan</p>
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
                    $latestSubs = $module->requirements->map(fn ($req) => $req->submissions->first());
                    $uploaded = $latestSubs->filter(fn ($sub) => $sub && $sub->status !== SubmissionStatus::Pending)->count();
                    $revisionCount = $latestSubs->filter(fn ($sub) => $sub && $sub->status === SubmissionStatus::Revision)->count();
                    $approvedCount = $latestSubs->filter(fn ($sub) => $sub && $sub->status === SubmissionStatus::Approved)->count();
                    $total = $module->requirements->count();
                    $moduleProgress = $total > 0 ? round(($uploaded / $total) * 100) : 0;
                    $hasRevision = $revisionCount > 0;
                    $isAllApproved = $approvedCount === $total && $total > 0;
                @endphp
                <a href="{{ route('unit.submissions.module', $module) }}" class="ui-card group overflow-hidden transition duration-200 hover:-translate-y-0.5 hover:shadow-lg {{ $hasRevision ? 'ring-2 ring-rose-500/30 border-rose-200 bg-rose-50/20 hover:shadow-rose-500/10' : 'hover:shadow-violet-500/10' }}">
                    <div class="border-b px-5 py-4 {{ $hasRevision ? 'border-rose-100 bg-gradient-to-r from-rose-50 via-rose-50/60 to-white' : 'border-slate-100 bg-gradient-to-r from-violet-50/80 to-white' }}">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-bold uppercase tracking-wider {{ $hasRevision ? 'text-rose-700' : 'text-violet-700' }}">{{ $module->shortLabel() }}</p>
                            @if ($hasRevision)
                                <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-100/90 px-2 py-0.5 text-[11px] font-extrabold text-rose-700 shadow-sm animate-pulse">
                                    <svg class="h-3 w-3 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                    </svg>
                                    {{ $revisionCount }} Perlu Revisi
                                </span>
                            @elseif ($isAllApproved)
                                <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-100/90 px-2 py-0.5 text-[11px] font-extrabold text-emerald-700">
                                    ✓ Sesuai
                                </span>
                            @endif
                        </div>
                        <h3 class="mt-1.5 line-clamp-2 text-base font-bold text-slate-900 {{ $hasRevision ? 'group-hover:text-rose-700' : 'group-hover:text-violet-700' }}">{{ $module->name }}</h3>
                    </div>
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <span class="font-semibold text-slate-700">{{ $uploaded }}/{{ $total }} terunggah</span>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $hasRevision ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">{{ $moduleProgress }}%</span>
                        </div>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full transition-all duration-500 {{ $hasRevision ? 'bg-rose-500' : 'bg-violet-500' }}" style="width: {{ $moduleProgress }}%"></div>
                        </div>
                        
                        @if ($hasRevision)
                            <div class="mt-4 flex items-center justify-between text-xs font-bold text-rose-600 group-hover:text-rose-700">
                                <span>Perbaiki dokumen revisi →</span>
                                <span class="rounded-full bg-rose-100 px-2 py-0.5 font-extrabold text-rose-700">{{ $revisionCount }} revisi</span>
                            </div>
                        @else
                            <p class="mt-4 text-sm font-semibold text-violet-600 group-hover:text-violet-500">Lihat lebih detail →</p>
                        @endif
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
