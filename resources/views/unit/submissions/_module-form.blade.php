@php
    $maxUploadMb = \App\Support\AccreditationUpload::maxUploadMb();
    $maxUploadBytes = \App\Support\AccreditationUpload::maxUploadBytes();
@endphp

@if ($errors->any())
    <div class="border-b border-red-100 bg-red-50/70 px-6 py-4 text-sm text-red-900">
        <p class="font-semibold flex items-center gap-1.5">
            <svg class="h-4.5 w-4.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
            Gagal menyimpan berkas. Periksa kembali isian Anda:
        </p>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-xs text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="divide-y divide-slate-100">
    @foreach ($module->requirements as $req)
        @php
            $latest = $req->submissions->first();
            $hasError = session('failed_requirement_id') == $req->id;
        @endphp
        <div class="grid gap-6 px-6 py-6 lg:grid-cols-3 lg:items-center" data-requirement-row="{{ $req->id }}">
            <div class="min-w-0 lg:col-span-2">
                <p class="text-lg font-semibold text-slate-900">{{ $req->title }}</p>
                @if ($req->description)
                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $req->description }}</p>
                @endif
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    @if ($latest)
                        <span class="ui-badge {{ $latest->status->badgeClass() }}">{{ $latest->status->label() }}</span>
                        <span class="text-xs font-medium text-slate-500">Versi {{ $latest->version }}</span>
                        <a href="{{ route('unit.submissions.view', $latest) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Lihat dokumen</a>
                        <span class="text-slate-300">·</span>
                        <a href="{{ route('unit.submissions.show', $latest) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-500">Riwayat</a>
                    @else
                        <span class="ui-badge bg-slate-100 text-slate-700 ring-slate-500/15">Menunggu unggah</span>
                    @endif
                </div>
            </div>
            <div class="flex justify-end">
                @if ($latest)
                    @php
                        $existingLinks = $latest->google_drive_links ?? [];
                        $existingLinksJson = json_encode($existingLinks);
                    @endphp
                    <button
                        type="button"
                        data-upload-btn="{{ $req->id }}"
                        onclick="openUploadModal('{{ $req->id }}', {{ $existingLinksJson }})"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 transition-all duration-150"
                    >
                        <svg style="width:14px;height:14px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        Perbarui Berkas
                    </button>
                @else
                    <button
                        type="button"
                        data-upload-btn="{{ $req->id }}"
                        onclick="openUploadModal('{{ $req->id }}')"
                        class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-bold text-white shadow-sm transition-all duration-150"
                        style="background: linear-gradient(135deg, #7c3aed, #6d28d9)"
                        onmouseover="this.style.background='linear-gradient(135deg,#6d28d9,#5b21b6)';this.style.boxShadow='0 4px 14px rgba(109,40,217,0.4)'"
                        onmouseout="this.style.background='linear-gradient(135deg,#7c3aed,#6d28d9)';this.style.boxShadow=''"
                    >
                        <svg style="width:14px;height:14px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                        Unggah Berkas
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>
