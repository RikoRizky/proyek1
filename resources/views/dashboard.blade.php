@php
    use App\Enums\UserRole;
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
        <div class="mb-6 rounded-2xl border border-violet-200/80 bg-violet-50/80 px-4 py-3 text-sm text-violet-900">
            Akun <strong>program studi</strong> dibuat oleh admin melalui menu <a href="{{ route('admin.users.index') }}" class="font-bold underline">Akun program studi</a>. Prodi tidak mendaftar sendiri kecuali fitur pendaftaran publik diaktifkan di konfigurasi server.
        </div>
        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Total pengguna" :value="$stats['usersCount']" accent="violet" />
            <x-stat-card label="Program studi" :value="$stats['unitCount']" accent="emerald" />
            <x-stat-card label="Total persyaratan" :value="$stats['totalRequirements']" accent="sky" />
            <x-stat-card label="Dokumen terunggah (versi terbaru)" :value="$stats['uploadedLatest']" accent="amber" />
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
    @else
        <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Belum diunggah" :value="$stats['notUploadedCount']" accent="sky" />
            <x-stat-card label="Progress upload" :value="$stats['progressPercent'].'%'" accent="violet" />
        </div>

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
            <a href="{{ route('unit.submissions.index') }}" class="ui-btn-primary">Kelola unggahan</a>
            <a href="{{ route('unit.reports.pdf') }}" class="ui-btn-secondary">Laporan PDF</a>
        </div>

        @foreach ($stats['modules'] as $module)
            <div class="ui-card mb-8 overflow-hidden">
                <div class="ui-section-header">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $module->name }}</h2>
                        @if ($module->description)
                            <p class="mt-1 text-sm text-slate-600">{{ $module->description }}</p>
                        @endif
                    </div>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach ($module->requirements as $req)
                        @php
                            $latest = $req->submissions->first();
                        @endphp
                        <li class="flex flex-wrap items-start justify-between gap-4 px-6 py-4 transition hover:bg-violet-50/25">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-slate-900">{{ $req->title }}</p>
                                @if ($req->description)
                                    <p class="mt-0.5 text-sm text-slate-600">{{ $req->description }}</p>
                                @endif
                            </div>
                            <div class="flex shrink-0 flex-wrap items-center gap-2">
                                @if ($latest)
                                    <span class="ui-badge {{ $latest->status->badgeClass() }}">{{ $latest->status->label() }}</span>
                                    <a href="{{ route('unit.submissions.show', $latest) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Riwayat</a>
                                @else
                                    <span class="ui-badge bg-slate-100 text-slate-700 ring-slate-500/15">Menunggu unggah</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endif
</x-app-layout>
