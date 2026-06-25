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

<div id="upload-progress-panel" class="hidden border-b border-violet-100 bg-violet-50/80 px-6 py-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-violet-900">Mengunggah berkas…</p>
            <p id="upload-progress-label" class="mt-0.5 text-xs text-violet-700">0 / 0</p>
        </div>
        <p id="upload-progress-percent" class="text-lg font-bold tabular-nums text-violet-800">0%</p>
    </div>
    <div class="mt-3 h-2 overflow-hidden rounded-full bg-violet-100">
        <div id="upload-progress-bar" class="h-full rounded-full bg-violet-600 transition-all duration-300" style="width: 0%"></div>
    </div>
</div>

<form
    id="module-upload-form"
    action="{{ route('unit.modules.submissions.batch', $module) }}"
    method="post"
    enctype="multipart/form-data"
    class="divide-y divide-slate-100"
    data-upload-form
    data-max-bytes="{{ $maxUploadBytes }}"
    data-max-mb="{{ $maxUploadMb }}"
    data-csrf="{{ csrf_token() }}"
>
    @csrf

    @foreach ($module->requirements as $req)
        @php
            $latest = $req->submissions->first();
            $fieldError = $errors->first('files.'.$req->id);
            $hasError = $fieldError !== null;
        @endphp
        <div class="grid gap-6 px-6 py-6 lg:grid-cols-3 lg:items-end {{ $hasError ? 'bg-red-50/40' : '' }}" data-requirement-row="{{ $req->id }}">
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
                <input
                    type="file"
                    name="files[{{ $req->id }}]"
                    accept=".pdf,.xlsx,.xls"
                    data-requirement-id="{{ $req->id }}"
                    data-requirement-title="{{ $req->title }}"
                    data-upload-url="{{ route('unit.submissions.store', $req) }}"
                    class="mt-2 block w-full cursor-pointer text-sm text-slate-600 file:mr-3 file:cursor-pointer file:rounded-xl file:border-0 file:bg-violet-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-violet-800 hover:file:bg-violet-200"
                >
                @error('files.'.$req->id)
                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
                <p class="js-client-upload-error mt-2 hidden text-sm font-medium text-red-600"></p>
            </div>
        </div>
    @endforeach

    <div class="flex flex-col gap-3 bg-slate-50/60 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs leading-relaxed text-slate-500">
            Pilih satu atau semua berkas, lalu simpan sekaligus. Unggahan berjalan satu per satu agar tidak kena batas server PHP.
            Maks. {{ $maxUploadMb }} MB per berkas (PDF/Excel).
        </p>
        <button type="submit" id="module-upload-submit" class="ui-btn-primary shrink-0 sm:min-w-[11rem]">Simpan semua berkas</button>
    </div>
</form>

<script>
    (() => {
        const form = document.getElementById('module-upload-form');
        if (!form) return;

        const maxBytes = Number(form.dataset.maxBytes);
        const maxMb = form.dataset.maxMb;
        const csrf = form.dataset.csrf;
        const progressPanel = document.getElementById('upload-progress-panel');
        const progressBar = document.getElementById('upload-progress-bar');
        const progressLabel = document.getElementById('upload-progress-label');
        const progressPercent = document.getElementById('upload-progress-percent');
        const submitBtn = document.getElementById('module-upload-submit');

        const showClientError = (input, message) => {
            const box = input.closest('.rounded-2xl');
            const clientError = input.parentElement?.querySelector('.js-client-upload-error');
            box?.classList.add('border-red-300', 'bg-red-50/80', 'ring-red-200');
            if (clientError) {
                clientError.textContent = message;
                clientError.classList.remove('hidden');
            }
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const inputs = [...form.querySelectorAll('input[type="file"]')].filter((input) => input.files?.[0]);
            if (inputs.length === 0) {
                alert('Pilih minimal satu berkas untuk diunggah.');
                return;
            }

            form.querySelectorAll('.js-client-upload-error').forEach((el) => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            const oversized = [];
            inputs.forEach((input) => {
                const file = input.files[0];
                if (file.size <= maxBytes) return;
                const title = input.dataset.requirementTitle ?? 'Berkas';
                const sizeMb = (file.size / 1024 / 1024).toFixed(1).replace('.', ',');
                oversized.push({ input, message: `«${title}» terlalu besar (${sizeMb} MB, maks. ${maxMb} MB).` });
            });

            if (oversized.length > 0) {
                oversized.forEach(({ input, message }) => showClientError(input, message));
                oversized[0].input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            progressPanel.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengunggah…';

            let completed = 0;
            let saved = 0;
            const errors = [];

            const updateProgress = () => {
                const percent = Math.round((completed / inputs.length) * 100);
                progressBar.style.width = percent + '%';
                progressLabel.textContent = completed + ' / ' + inputs.length + ' berkas diproses';
                progressPercent.textContent = percent + '%';
            };

            for (const input of inputs) {
                const file = input.files[0];
                const url = input.dataset.uploadUrl;
                const title = input.dataset.requirementTitle ?? 'Berkas';

                progressLabel.textContent = `Mengunggah «${title}» (${completed + 1}/${inputs.length})…`;

                const body = new FormData();
                body.append('document', file);
                body.append('_token', csrf);

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        errors.push({ input, message: data.message ?? `Gagal mengunggah «${title}».` });
                        showClientError(input, data.message ?? `Gagal mengunggah «${title}».`);
                    } else {
                        saved++;
                    }
                } catch {
                    const message = `Gagal mengunggah «${title}» (koneksi terputus).`;
                    errors.push({ input, message });
                    showClientError(input, message);
                }

                completed++;
                updateProgress();
            }

            if (saved > 0) {
                sessionStorage.setItem('upload_status', saved + ' berkas berhasil disimpan.');
            }

            if (errors.length > 0 && saved === 0) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan semua berkas';
                progressPanel.classList.add('hidden');
                errors[0].input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            window.location.reload();
        });
    })();
</script>
