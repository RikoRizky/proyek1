<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Penilaian</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Hasil penilaian per program studi</h1>
            <p class="mt-1 text-sm text-slate-600">Hanya persyaratan yang sudah memiliki skor.</p>
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
            <div class="overflow-x-auto">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Asesor</th>
                            <th>Persyaratan</th>
                            <th>Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unit->submissions as $s)
                            @php $a = $s->assessment; @endphp
                            <tr>
                                <td class="whitespace-nowrap text-xs text-slate-500">{{ $a->created_at->format('d M Y H:i') }}</td>
                                <td class="font-medium text-slate-800">{{ $a->asesor->name }}</td>
                                <td class="max-w-md text-sm text-slate-700">{{ $s->requirement->title }}</td>
                                <td class="font-bold tabular-nums text-violet-700">{{ $a->score }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="ui-empty ui-card py-16 text-sm">Belum ada penilaian.</div>
    @endforelse

    <div class="mt-6">{{ $units->links() }}</div>
</x-app-layout>
