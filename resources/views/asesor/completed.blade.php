<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Asesor</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Sudah dinilai</h1>
            <p class="mt-1 text-sm text-slate-600">Dikelompokkan per akun program studi — dokumen yang telah memiliki penilaian.</p>
        </div>
    </x-slot>

    @forelse ($units as $unit)
        <div class="ui-card mb-8 overflow-hidden">
            <div class="ui-section-header bg-gradient-to-r from-emerald-50/90 to-white">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $unit->name }}</h2>
                    <p class="text-xs text-slate-500">{{ $unit->email }}</p>
                </div>
            </div>
            @if ($unit->submissions->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-500">Belum ada dokumen selesai dinilai untuk prodi ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Modul / syarat</th>
                                <th>Skor</th>
                                <th>Asesor</th>
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
                                    <td class="font-bold tabular-nums text-violet-700">{{ $sub->assessment?->score ?? '—' }}</td>
                                    <td class="text-sm text-slate-600">{{ $sub->assessment?->asesor?->name ?? '—' }}</td>
                                    <td class="text-right space-x-3 whitespace-nowrap text-sm font-semibold">
                                        <a href="{{ route('asesor.submissions.view', $sub) }}" class="text-violet-600 hover:text-violet-500">Lihat</a>
                                        <a href="{{ route('asesor.submissions.show', $sub) }}" class="text-slate-600 hover:text-slate-900">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @empty
        <div class="ui-empty ui-card py-16 text-sm">Belum ada dokumen yang selesai dinilai.</div>
    @endforelse

    <div class="mt-6">{{ $units->links() }}</div>
</x-app-layout>
