<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Asesor</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Semua dokumen prodi</h1>
                <p class="mt-1 text-sm text-slate-600">Dikelompokkan per akun program studi.</p>
            </div>
            <form method="get" class="flex w-full max-w-xl flex-col gap-2 sm:flex-row sm:items-center">
                <select name="status" class="ui-input sm:w-48" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="pending" @selected(($status ?? '') === 'pending')>Menunggu / dalam tinjauan</option>
                    <option value="completed" @selected(($status ?? '') === 'completed')>Selesai dinilai</option>
                </select>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari prodi…" class="ui-input flex-1">
                <button type="submit" class="ui-btn-secondary shrink-0">Terapkan</button>
            </form>
        </div>
    </x-slot>

    @forelse ($units as $unit)
        <div class="ui-card mb-8 overflow-hidden">
            <div class="ui-section-header bg-gradient-to-r from-sky-50/90 to-white">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $unit->name }}</h2>
                    <p class="text-xs text-slate-500">{{ $unit->email }}</p>
                </div>
            </div>
            @if ($unit->submissions->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-500">Tidak ada dokumen pada filter ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Persyaratan</th>
                                <th>Versi</th>
                                <th>Status</th>
                                <th>Skor</th>
                                <th class="text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unit->submissions as $sub)
                                <tr>
                                    <td>
                                        <div class="text-xs text-slate-500">{{ $sub->requirement->module->name }}</div>
                                        <div class="max-w-md font-medium text-slate-900">{{ $sub->requirement->title }}</div>
                                    </td>
                                    <td class="tabular-nums">{{ $sub->version }}</td>
                                    <td><span class="ui-badge {{ $sub->status->badgeClass() }}">{{ $sub->status->label() }}</span></td>
                                    <td class="font-semibold tabular-nums">{{ $sub->assessment?->score ?? '—' }}</td>
                                    <td class="text-right space-x-2 text-sm font-semibold">
                                        <a href="{{ route('asesor.submissions.view', $sub) }}" class="text-violet-600 hover:text-violet-500">Lihat</a>
                                        <a href="{{ route('asesor.submissions.download', $sub) }}" class="text-slate-600 hover:text-slate-900">Unduh</a>
                                        @if(in_array($sub->status, [\App\Enums\SubmissionStatus::Uploaded, \App\Enums\SubmissionStatus::UnderReview], true))
                                            <a href="{{ route('asesor.submissions.show', $sub) }}" class="text-violet-800 hover:text-violet-600">Nilai</a>
                                        @else
                                            <a href="{{ route('asesor.submissions.show', $sub) }}" class="text-slate-600">Detail</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @empty
        <div class="ui-empty ui-card py-16 text-sm">Tidak ada program studi yang cocok.</div>
    @endforelse

    <div class="mt-6">{{ $units->links() }}</div>
</x-app-layout>
