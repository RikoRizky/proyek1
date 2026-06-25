<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Riwayat</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $submission->requirement->title }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ $submission->requirement->module->name }}</p>
        </div>
    </x-slot>

    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('unit.submissions.view', $submission) }}" class="ui-btn-primary">Lihat di aplikasi</a>
        <a href="{{ route('unit.submissions.download', $submission) }}" class="ui-btn-secondary">Unduh versi ini</a>
        <a href="{{ route('unit.submissions.index') }}" class="ui-btn-secondary">Kembali</a>
    </div>

    <div class="ui-table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Versi</th>
                    <th>Status</th>
                    <th>Berkas</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $row)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $row->version }}</td>
                        <td><span class="ui-badge {{ $row->status->badgeClass() }}">{{ $row->status->label() }}</span></td>
                        <td class="max-w-xs truncate text-slate-600">{{ $row->original_filename }}</td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('unit.submissions.view', $row) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Lihat</a>
                            <a href="{{ route('unit.submissions.download', $row) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-500">Unduh</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
