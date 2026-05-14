<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Dokumen</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Unggahan per program studi</h1>
                <p class="mt-1 text-sm text-slate-600">Data dikelompokkan per akun prodi.</p>
            </div>
            <form method="get" class="flex w-full max-w-md gap-2 sm:w-auto">
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama prodi, syarat, berkas…" class="ui-input flex-1">
                <button type="submit" class="ui-btn-secondary shrink-0">Cari</button>
            </form>
        </div>
    </x-slot>

    @forelse ($units as $unit)
        <div class="ui-card mb-8 overflow-hidden">
            <div class="ui-section-header bg-gradient-to-r from-violet-50/90 to-white">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $unit->name }}</h2>
                    <p class="text-xs text-slate-500">{{ $unit->email }}</p>
                </div>
                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-violet-800 ring-1 ring-violet-200/80">{{ $unit->submissions->count() }} dokumen (versi terbaru)</span>
            </div>
            @if ($unit->submissions->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-500">Belum ada unggahan.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Modul / syarat</th>
                                <th>Status</th>
                                <th>Skor</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unit->submissions as $sub)
                                <tr>
                                    <td>
                                        <div class="text-xs text-slate-500">{{ $sub->requirement->module->name }}</div>
                                        <div class="max-w-md font-medium text-slate-900">{{ $sub->requirement->title }}</div>
                                    </td>
                                    <td><span class="ui-badge {{ $sub->status->badgeClass() }}">{{ $sub->status->label() }}</span></td>
                                    <td class="font-semibold tabular-nums">{{ $sub->assessment?->score ?? '—' }}</td>
                                    <td class="text-right space-x-2 text-sm font-semibold">
                                        <a href="{{ route('admin.submissions.view', $sub) }}" class="text-violet-600 hover:text-violet-500">Lihat</a>
                                        <a href="{{ route('admin.submissions.download', $sub) }}" class="text-slate-600 hover:text-slate-900">Unduh</a>
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
