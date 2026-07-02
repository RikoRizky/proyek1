@php use App\Enums\SubmissionStatus; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Perguruan Tinggi</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $module->name }}</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Prodi: <span class="font-semibold text-slate-800">{{ $prodi->name }}</span>
                    @if ($module->description)
                        · {{ $module->description }}
                    @endif
                </p>
            </div>
            <a href="{{ route('perti.prodis.progress', $prodi->id) }}" class="ui-btn-secondary text-sm">← Progress {{ $prodi->name }}</a>
        </div>
    </x-slot>

    {{-- Module quick nav --}}
    <div class="mb-6 flex flex-wrap gap-2">
        @foreach ($allModules as $m)
            <a
                href="{{ route('perti.prodis.modul', [$prodi->id, $m->id]) }}"
                class="rounded-xl px-3 py-1.5 text-xs font-semibold transition
                    {{ $m->id === $module->id
                        ? 'bg-violet-600 text-white shadow-sm'
                        : 'bg-slate-100 text-slate-600 hover:bg-violet-50 hover:text-violet-700' }}"
                title="{{ $m->name }}">
                {{ $m->shortLabel() }}
            </a>
        @endforeach
    </div>

    @php
        $uploadedCount = $requirements->filter(fn($r) => $r->submissions->first()?->status === SubmissionStatus::Uploaded)->count();
        $totalCount = $requirements->count();
        $progressPercent = $totalCount > 0 ? round(($uploadedCount / $totalCount) * 100) : 0;
    @endphp

    <div class="mb-6 flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm ring-1 ring-slate-200/50">
        <div class="flex-1">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-sm font-semibold text-slate-700">Progress modul ini</p>
                <span class="text-sm font-bold tabular-nums text-slate-900">{{ $uploadedCount }}/{{ $totalCount }} ({{ $progressPercent }}%)</span>
            </div>
            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full {{ $progressPercent >= 100 ? 'bg-emerald-500' : 'bg-violet-500' }} transition-all"
                     style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
    </div>

    <div class="ui-card overflow-hidden">
        <div class="ui-section-header flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Daftar persyaratan</h2>
            <span class="text-xs text-slate-500 font-semibold">{{ $uploadedCount }}/{{ $totalCount }} terunggah</span>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($requirements as $req)
                @php
                    $submission = $req->submissions->first();
                    $uploaded = $submission?->status === SubmissionStatus::Uploaded;
                @endphp
                <li class="flex items-start justify-between gap-4 px-6 py-4 transition hover:bg-violet-50/30">
                    <div class="flex items-start gap-3 min-w-0 flex-1">
                        {{-- Status icon --}}
                        @if ($uploaded)
                            <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600" title="Terunggah">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        @else
                            <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400" title="Belum diunggah">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </span>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-slate-900 leading-snug">{{ $req->title }}</p>
                            @if ($req->description)
                                <p class="mt-1 text-xs text-slate-500">{{ $req->description }}</p>
                            @endif
                            <div class="mt-2.5 flex flex-wrap items-center gap-2">
                                @if ($uploaded)
                                    <span class="ui-badge {{ $submission->status->badgeClass() }}">{{ $submission->status->label() }}</span>
                                    <span class="text-xs font-medium text-slate-500">Versi {{ $submission->version }}</span>
                                    <a href="{{ route('perti.submissions.view', $submission) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Lihat dokumen</a>
                                    <span class="text-slate-300">·</span>
                                    <a href="{{ route('perti.submissions.show', $submission) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-500">Riwayat</a>
                                    <span class="text-[10px] text-slate-400">({{ $submission->updated_at->diffForHumans() }})</span>
                                @else
                                    <span class="ui-badge bg-slate-100 text-slate-700 ring-slate-500/15">Menunggu unggah</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="ui-empty px-6">Belum ada persyaratan di modul ini.</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>
