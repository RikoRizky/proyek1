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
            Akun <strong>program studi</strong> dan <strong>asesor</strong> dibuat oleh admin melalui menu <a href="{{ route('admin.users.index') }}" class="font-bold underline">Akun pengguna</a>. Prodi tidak mendaftar sendiri kecuali fitur pendaftaran publik diaktifkan di konfigurasi server.
        </div>
        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Total pengguna" :value="$stats['usersCount']" accent="violet" />
            <x-stat-card label="Program studi" :value="$stats['unitCount']" accent="emerald" />
            <x-stat-card label="Asesor" :value="$stats['asesorCount']" accent="sky" />
            <x-stat-card label="Penilaian tercatat" :value="$stats['assessmentsCount']" accent="amber" />
        </div>

        <div class="mb-8 grid gap-4 sm:grid-cols-2">
            <x-stat-card label="Total persyaratan" :value="$stats['totalRequirements']" accent="violet" />
            <x-stat-card label="Dokumen selesai dinilai (versi terbaru)" :value="$stats['completedLatest']" accent="emerald" />
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
                            <p class="text-sm text-slate-500">Bobot {{ number_format($module->weight, 2) }}%</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200/80">{{ $module->requirements_count }} syarat</span>
                    </li>
                @empty
                    <li class="ui-empty px-6 text-sm">Belum ada modul.</li>
                @endforelse
            </ul>
        </div>
    @elseif ($stats['role'] === UserRole::Asesor)
        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Perlu dinilai" :value="$stats['pendingCount']" accent="amber" />
            <x-stat-card label="Sudah selesai dinilai" :value="$stats['completedCount']" accent="emerald" />
            <x-stat-card label="Total dokumen (versi terbaru)" :value="$stats['totalTracked']" accent="sky" />
            <x-stat-card label="Penilaian oleh Anda" :value="$stats['assessedCount']" accent="violet" />
        </div>

        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('asesor.queue.index') }}" class="ui-btn-primary">Antrian perlu dinilai</a>
            <a href="{{ route('asesor.completed.index') }}" class="ui-btn-secondary">Sudah dinilai</a>
            <a href="{{ route('asesor.documents.index') }}" class="ui-btn-secondary">Semua dokumen prodi</a>
        </div>

        <div class="ui-card overflow-hidden">
            <div class="ui-section-header">
                <h2 class="text-lg font-bold text-slate-900">Prioritas penilaian</h2>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($stats['queue'] as $sub)
                    <li class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 transition hover:bg-violet-50/30">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900">{{ $sub->requirement->title }}</p>
                            <p class="text-xs text-slate-500">{{ $sub->requirement->module->name }} — {{ $sub->user->name }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="ui-badge {{ $sub->status->badgeClass() }}">{{ $sub->status->label() }}</span>
                            <a href="{{ route('asesor.submissions.show', $sub) }}" class="rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-violet-500">Buka</a>
                        </div>
                    </li>
                @empty
                    <li class="ui-empty px-6 text-sm">Tidak ada dokumen yang menunggu penilaian.</li>
                @endforelse
            </ul>
        </div>
    @else
        <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat-card label="Sudah dinilai (syarat)" :value="$stats['completedCount'].' / '.$stats['totalRequirements']" accent="emerald" />
            <x-stat-card label="Menunggu penilaian asesor" :value="$stats['awaitingAssessment']" accent="amber" />
            <x-stat-card label="Belum diunggah" :value="$stats['notUploadedCount']" accent="sky" />
            <x-stat-card label="Progress dinilai" :value="$stats['progressPercent'].'%'" accent="violet" />
        </div>

        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('unit.submissions.index') }}" class="ui-btn-primary">Kelola unggahan</a>
            <a href="{{ route('unit.reports.pdf') }}" class="ui-btn-secondary">Laporan PDF</a>
        </div>

        <p class="mb-6 text-sm text-slate-600">
            <strong>Sudah dinilai</strong> = asesor sudah memberi skor pada versi dokumen terbaru untuk persyaratan tersebut.
            <strong>Menunggu penilaian</strong> = dokumen sudah terunggah tetapi belum selesai dinilai.
        </p>

        @foreach ($stats['modules'] as $module)
            <div class="ui-card mb-8 overflow-hidden">
                <div class="ui-section-header">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $module->name }}</h2>
                        @if ($module->description)
                            <p class="mt-1 text-sm text-slate-600">{{ $module->description }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-800 ring-1 ring-violet-200/80">Bobot {{ number_format($module->weight, 2) }}%</span>
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
                                    @if ($latest->assessment)
                                        <span class="rounded-lg bg-slate-900 px-2.5 py-1 text-xs font-bold text-white">Skor {{ $latest->assessment->score }}</span>
                                    @endif
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
