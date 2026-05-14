<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Asesor</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Perlu dinilai</h1>
            <p class="mt-1 text-sm text-slate-600">Dikelompokkan per akun program studi — dokumen yang menunggu atau sedang ditinjau.</p>
        </div>
    </x-slot>

    @forelse ($units as $unit)
        <div class="ui-card mb-8 overflow-hidden">
            <div class="ui-section-header bg-gradient-to-r from-amber-50/90 to-white">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $unit->name }}</h2>
                    <p class="text-xs text-slate-500">{{ $unit->email }}</p>
                </div>
            </div>
            @if ($unit->submissions->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-500">Tidak ada dokumen menunggu penilaian untuk prodi ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Modul</th>
                                <th>Persyaratan</th>
                                <th>Versi</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unit->submissions as $sub)
                                <tr>
                                    <td class="text-slate-600">{{ $sub->requirement->module->name }}</td>
                                    <td class="max-w-xs font-medium text-slate-900">{{ $sub->requirement->title }}</td>
                                    <td class="tabular-nums font-medium">{{ $sub->version }}</td>
                                    <td><span class="ui-badge {{ $sub->status->badgeClass() }}">{{ $sub->status->label() }}</span></td>
                                    <td class="text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('asesor.submissions.view', $sub) }}" class="text-xs font-semibold text-violet-600 hover:text-violet-500">Lihat</a>
                                        <a href="{{ route('asesor.submissions.show', $sub) }}" class="inline-flex rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-violet-500">Nilai</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @empty
        <div class="ui-empty ui-card py-16 text-sm">Tidak ada dokumen menunggu penilaian.</div>
    @endforelse

    <div class="mt-6">{{ $units->links() }}</div>
</x-app-layout>
