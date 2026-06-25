@php
    $maxUploadMb = \App\Support\AccreditationUpload::maxUploadMb();
    $maxUploadBytes = \App\Support\AccreditationUpload::maxUploadBytes();
    $moduleErrors = collect($module->requirements)
        ->filter(fn ($req) => $errors->has('files.'.$req->id))
        ->map(fn ($req) => $errors->first('files.'.$req->id))
        ->values();
@endphp

@if ($moduleErrors->isNotEmpty())
    <div class="border-b border-red-100 bg-red-50/70 px-6 py-3 text-sm text-red-900">
        <p class="font-semibold">Perlu diunggah ulang pada modul ini:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($moduleErrors as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form
    action="{{ route('unit.modules.submissions.batch', $module) }}"
    method="post"
    enctype="multipart/form-data"
    class="divide-y divide-slate-100"
    data-upload-form
    data-max-bytes="{{ $maxUploadBytes }}"
    data-max-mb="{{ $maxUploadMb }}"
>
    @csrf
    <input type="hidden" name="expected_file_count" value="0" data-expected-file-count>

    @foreach ($module->requirements as $req)
        @php
            $latest = $req->submissions->first();
            $fieldError = $errors->first('files.'.$req->id);
            $hasError = $fieldError !== null;
        @endphp
        <div class="grid gap-6 px-6 py-6 lg:grid-cols-3 lg:items-end {{ $hasError ? 'bg-red-50/40' : '' }}">
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
            <div @class([
                'rounded-2xl border p-4 ring-1',
                'border-red-300 bg-red-50/80 ring-red-200' => $hasError,
                'border-slate-100 bg-gradient-to-b from-slate-50/80 to-white ring-slate-100' => ! $hasError,
            ])>
                <label class="block text-xs font-bold uppercase tracking-wider {{ $hasError ? 'text-red-700' : 'text-slate-500' }}">Berkas (opsional)</label>
                <input type="file" name="files[{{ $req->id }}]" accept=".pdf,.xlsx,.xls"
                       data-requirement-title="{{ $req->title }}"
                       class="mt-2 block w-full cursor-pointer text-sm text-slate-600 file:mr-3 file:cursor-pointer file:rounded-xl file:border-0 file:bg-violet-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-violet-800 hover:file:bg-violet-200">
                @error('files.'.$req->id)
                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
                <p class="js-client-upload-error mt-2 hidden text-sm font-medium text-red-600"></p>
            </div>
        </div>
    @endforeach

    <div class="flex flex-col gap-3 bg-slate-50/60 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs leading-relaxed text-slate-500">
            Pilih satu atau semua berkas di atas, lalu simpan sekaligus.
            {{ \App\Support\AccreditationUpload::uploadLimitHint() }}
        </p>
        <button type="submit" class="ui-btn-primary shrink-0 sm:min-w-[11rem]">Simpan semua berkas</button>
    </div>
</form>

<script>
    document.querySelectorAll('[data-upload-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const maxBytes = Number(form.dataset.maxBytes);
            const maxMb = form.dataset.maxMb;
            const moduleName = form.closest('.ui-card')?.querySelector('[data-module-title]')?.textContent?.trim() ?? 'modul ini';
            const oversized = [];
            let selectedCount = 0;

            form.querySelectorAll('input[type="file"]').forEach((input) => {
                const file = input.files?.[0];
                const clientError = input.parentElement?.querySelector('.js-client-upload-error');

                if (clientError) {
                    clientError.classList.add('hidden');
                    clientError.textContent = '';
                }

                if (!file) {
                    return;
                }

                selectedCount++;

                if (file.size <= maxBytes) {
                    return;
                }

                const title = input.dataset.requirementTitle ?? 'Berkas';
                const sizeMb = (file.size / 1024 / 1024).toFixed(1).replace('.', ',');
                const message = `«${title}» pada modul «${moduleName}» terlalu besar (${sizeMb} MB, maks. ${maxMb} MB). Pilih berkas lebih kecil.`;

                oversized.push({ input, message, clientError });
            });

            form.querySelector('[data-expected-file-count]').value = String(selectedCount);

            if (oversized.length === 0) {
                return;
            }

            event.preventDefault();

            oversized.forEach(({ input, message, clientError }) => {
                const box = input.closest('.rounded-2xl');
                box?.classList.add('border-red-300', 'bg-red-50/80', 'ring-red-200');

                if (clientError) {
                    clientError.textContent = message;
                    clientError.classList.remove('hidden');
                }
            });

            oversized[0].input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
</script>
