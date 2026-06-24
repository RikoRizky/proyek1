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
        <div class="mb-8 rounded-2xl border border-slate-200/80 bg-white/70 px-4 py-4 shadow-sm">
            <div class="flex flex-col gap-1">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Asesor</span>
                <h2 class="text-lg font-bold text-slate-900">9 Kriteria</h2>
                <p class="text-sm text-slate-600">Tampilan kriteria sebagai referensi (tanpa panel penilaian).</p>
            </div>

            <ul class="mt-4 space-y-3">
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">1</span>
                    <span class="text-sm font-semibold text-slate-900">Visi, Misi, Tujuan, dan Strategi</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">2</span>
                    <span class="text-sm font-semibold text-slate-900">Tata Pamong, Tata Kelola, dan Kerjasama</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">3</span>
                    <span class="text-sm font-semibold text-slate-900">Mahasiswa</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">4</span>
                    <span class="text-sm font-semibold text-slate-900">Sumber Daya Manusia (SDM)</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">5</span>
                    <span class="text-sm font-semibold text-slate-900">Keuangan, Sarana, dan Prasarana</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">6</span>
                    <span class="text-sm font-semibold text-slate-900">Pendidikan</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">7</span>
                    <span class="text-sm font-semibold text-slate-900">Penelitian</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">8</span>
                    <span class="text-sm font-semibold text-slate-900">Pengabdian kepada Masyarakat</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-violet-800">9</span>
                    <span class="text-sm font-semibold text-slate-900">Luaran dan Capaian</span>
                </li>
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
