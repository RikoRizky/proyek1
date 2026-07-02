<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Dokumen</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Unggahan per perguruan tinggi</h1>
                <p class="mt-1 text-sm text-slate-600">Data dikelompokkan berdasarkan Perguruan Tinggi induk.</p>
            </div>
            <form method="get" class="flex w-full max-w-md gap-2 sm:w-auto">
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama prodi, syarat, berkas…" class="ui-input flex-1">
                <button type="submit" class="ui-btn-secondary shrink-0">Cari</button>
            </form>
        </div>
    </x-slot>

    @forelse ($pertis as $perti)
        <div class="mb-10">
            <!-- University Header -->
            <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-2">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-850 flex items-center gap-2">
                        <svg class="h-6 w-6 text-violet-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.33l-7.5-5-7.5 5V21m16.5 0H3"/></svg>
                        {{ $perti->name }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Kode PT: {{ $perti->pertiProfile?->kode_pt ?? '-' }} | Alamat: {{ $perti->pertiProfile?->alamat ?? '-' }}</p>
                </div>
            </div>

            <!-- List of Prodis under this university -->
            <div class="grid gap-6">
                @php
                    $prodis = $perti->pertiProfile?->prodis ?? collect();
                @endphp
                @forelse ($prodis as $prodiRecord)
                    @php
                        $unit = $prodiRecord->user;
                    @endphp
                    @if ($unit)
                        <div class="ui-card overflow-hidden">
                            <div class="ui-section-header bg-gradient-to-r from-violet-50/90 to-white">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">{{ $unit->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $unit->email }} | Kode Prodi: {{ $prodiRecord->kode_prodi ?? '-' }}</p>
                                </div>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-violet-800 ring-1 ring-violet-200/80">{{ $unit->submissions->count() }} dokumen (versi terbaru)</span>
                            </div>
                            @if ($unit->submissions->isEmpty())
                                <p class="px-6 py-6 text-center text-sm text-slate-500">Belum ada unggahan.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="ui-table">
                                        <thead>
                                            <tr>
                                                <th>Modul / syarat</th>
                                                <th>Status</th>
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
                    @endif
                @empty
                    <div class="ui-card p-6 text-center text-sm text-slate-500 bg-slate-50/50">Belum ada program studi terdaftar.</div>
                @endforelse
            </div>
        </div>
    @empty
        <div class="ui-empty ui-card py-16 text-sm">Tidak ada perguruan tinggi/program studi yang cocok.</div>
    @endforelse

    <div class="mt-6">{{ $pertis->links() }}</div>
</x-app-layout>
