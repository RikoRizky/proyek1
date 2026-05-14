<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Unit kerja</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Unggah dokumen</h1>
            <p class="mt-1 text-sm text-slate-600">PDF atau Excel — maks. 20 MB per berkas. Satu tombol menyimpan semua berkas yang Anda pilih dalam modul yang sama.</p>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50/90 px-4 py-3 text-sm font-medium text-emerald-900 ring-1 ring-emerald-500/10">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->has('files'))
        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50/90 px-4 py-3 text-sm font-medium text-red-900 ring-1 ring-red-500/10">
            {{ $errors->first('files') }}
        </div>
    @endif

    @foreach ($modules as $module)
        <div class="ui-card mb-10 overflow-hidden">
            <div class="ui-section-header">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $module->name }}</h2>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">Bobot modul {{ number_format($module->weight, 2) }}%</p>
                </div>
            </div>
            <form action="{{ route('unit.modules.submissions.batch', $module) }}" method="post" enctype="multipart/form-data" class="divide-y divide-slate-100">
                @csrf
                @foreach ($module->requirements as $req)
                    @php $latest = $req->submissions->first(); @endphp
                    <div class="grid gap-6 px-6 py-6 lg:grid-cols-3 lg:items-end">
                        <div class="min-w-0 lg:col-span-2">
                            <p class="text-lg font-semibold text-slate-900">{{ $req->title }}</p>
                            @if ($req->description)
                                <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $req->description }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                @if ($latest)
                                    <span class="ui-badge {{ $latest->status->badgeClass() }}">{{ $latest->status->label() }}</span>
                                    <span class="text-xs font-medium text-slate-500">Versi {{ $latest->version }}</span>
                                    @if ($latest->assessment)
                                        <span class="rounded-lg bg-slate-900 px-2 py-0.5 text-xs font-bold text-white">Skor {{ $latest->assessment->score }}</span>
                                    @endif
                                    <a href="{{ route('unit.submissions.view', $latest) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Lihat dokumen</a>
                                    <span class="text-slate-300">·</span>
                                    <a href="{{ route('unit.submissions.show', $latest) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-500">Riwayat</a>
                                @else
                                    <span class="ui-badge bg-slate-100 text-slate-700 ring-slate-500/15">Menunggu unggah</span>
                                @endif
                            </div>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-gradient-to-b from-slate-50/80 to-white p-4 ring-1 ring-slate-100">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Berkas (opsional)</label>
                            <input type="file" name="files[{{ $req->id }}]" accept=".pdf,.xlsx,.xls"
                                   class="mt-2 block w-full cursor-pointer text-sm text-slate-600 file:mr-3 file:cursor-pointer file:rounded-xl file:border-0 file:bg-violet-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-violet-800 hover:file:bg-violet-200">
                            @error('files.'.$req->id)
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
                <div class="flex flex-col gap-2 bg-slate-50/60 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-500">Pilih satu atau beberapa berkas di atas, lalu simpan sekaligus untuk modul ini.</p>
                    <button type="submit" class="ui-btn-primary shrink-0 sm:min-w-[11rem]">Simpan unggahan modul</button>
                </div>
            </form>
        </div>
    @endforeach
</x-app-layout>
