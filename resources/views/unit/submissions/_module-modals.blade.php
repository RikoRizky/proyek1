@php
    $maxUploadMb = \App\Support\AccreditationUpload::maxUploadMb();
@endphp

@foreach ($module->requirements as $req)
    <!-- Floating Modal for each Requirement -->
    @php
        $isFailed = session('failed_requirement_id') == $req->id;
        $links = $isFailed ? old('google_drive_links', []) : [['name' => '', 'url' => '']];
        if (empty($links)) {
            $links = [['name' => '', 'url' => '']];
        }
        $latest = $req->submissions->first();
    @endphp
    <div id="upload-modal-{{ $req->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-title-{{ $req->id }}">
        <!-- Backdrop -->
        <div class="fixed inset-0 transition-opacity" style="background: rgba(10,10,20,0.82)" onclick="closeUploadModal('{{ $req->id }}')"></div>

        <!-- Modal wrapper -->
        <div class="flex min-h-screen items-center justify-center p-4 sm:p-6">
            <div class="relative overflow-hidden transform transition-all duration-300 scale-95 opacity-0" style="width: 480px; max-width: calc(100vw - 2rem); margin: 0 auto" id="modal-box-{{ $req->id }}">

                <!-- Modal Card -->
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10 w-full" style="background:#fff">

                    <!-- Header: dark navy gradient -->
                    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #1e1b4b 100%); padding: 1.25rem 1.5rem 1.5rem;">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <!-- Breadcrumb label -->
                                <div class="flex items-center gap-2 mb-2.5">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-md" style="background:rgba(139,92,246,0.3)">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#a78bfa"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                    </span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest" style="color:#a78bfa">{{ $module->name }}</span>
                                </div>
                                <!-- Title -->
                                <h3 id="modal-title-{{ $req->id }}" class="text-base font-bold leading-snug" style="color:#f8fafc" title="{{ $req->title }}">{{ $req->title }}</h3>
                                @if ($req->description)
                                    <p class="mt-1 text-xs leading-relaxed line-clamp-2" style="color:#94a3b8">{{ $req->description }}</p>
                                @endif
                            </div>
                            <!-- Close button -->
                            <button type="button" onclick="closeUploadModal('{{ $req->id }}')"
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg transition"
                                style="background:rgba(255,255,255,0.08); color:#94a3b8"
                                onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.color='#f1f5f9'"
                                onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#94a3b8'">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <form id="modal-upload-form-{{ $req->id }}" action="{{ route('unit.submissions.store', $req) }}" method="POST" enctype="multipart/form-data" onsubmit="disableSubmitButton('{{ $req->id }}')">
                        @csrf
                        <div class="px-6 pt-5 pb-3 space-y-5">

                            <!-- Google Drive Links -->
                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Link Google Drive</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Minimal isi 1 link jika tidak mengunggah berkas</p>
                                    </div>
                                    <button type="button" onclick="addDriveLinkRow('{{ $req->id }}')"
                                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition"
                                        style="background:#f5f3ff; color:#7c3aed"
                                        onmouseover="this.style.background='#ede9fe'"
                                        onmouseout="this.style.background='#f5f3ff'">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        Tambah Link
                                    </button>
                                </div>

                                <div id="drive-links-container-{{ $req->id }}" class="space-y-2">
                                    @foreach ($links as $index => $link)
                                        <div class="flex items-start gap-2" id="drive-row-{{ $req->id }}-{{ $index }}">
                                            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Dokumen</label>
                                                    <input type="text" name="google_drive_links[{{ $index }}][name]" value="{{ $link['name'] ?? '' }}" placeholder="Contoh: SK Rektor..."
                                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Link Google Drive</label>
                                                    <input type="url" name="google_drive_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="https://drive.google.com/..."
                                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition">
                                                </div>
                                            </div>
                                            @if ($index > 0)
                                                <button type="button" onclick="removeDriveLinkRow('{{ $req->id }}', {{ $index }})"
                                                    class="mt-5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-300 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                                </button>
                                            @else
                                                <div class="mt-5 h-8 w-8 shrink-0"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="relative flex items-center">
                                <div class="flex-1 border-t border-slate-100"></div>
                                <span class="mx-3 text-[10px] font-bold uppercase tracking-widest text-slate-300">Atau unggah berkas</span>
                                <div class="flex-1 border-t border-slate-100"></div>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <p class="text-sm font-semibold text-slate-800 mb-0.5">
                                    Berkas Dokumen
                                    <span class="ml-1 text-xs font-normal text-slate-400">(Opsional)</span>
                                </p>
                                <p class="text-xs text-slate-400 mb-3">PDF atau Excel · Maks. {{ $maxUploadMb }} MB · Bisa pilih lebih dari 1</p>

                                <!-- Dropzone -->
                                <label for="file-input-{{ $req->id }}" id="dropzone-{{ $req->id }}"
                                    class="relative flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/80 px-4 py-7 text-center cursor-pointer transition-all duration-200 hover:border-violet-400 hover:bg-violet-50/40 group">
                                    <!-- Icon -->
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white shadow-sm text-slate-400 group-hover:border-violet-300 group-hover:text-violet-500 transition">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p id="dropzone-label-{{ $req->id }}" class="text-sm font-semibold text-slate-600 group-hover:text-violet-700 transition">Klik atau seret berkas ke sini</p>
                                        <p class="mt-0.5 text-xs text-slate-400">PDF, XLSX, XLS</p>
                                    </div>
                                    <input id="file-input-{{ $req->id }}" type="file" name="documents[]" multiple accept=".pdf,.xlsx,.xls"
                                        class="sr-only" onchange="updateFilePreview('{{ $req->id }}', this)">
                                </label>

                                <!-- File preview list -->
                                <div id="file-preview-{{ $req->id }}" class="mt-2 space-y-1.5 hidden"></div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-3.5">
                            <p class="text-[11px] text-slate-400 leading-tight hidden sm:block">Data yang sudah ada tetap tersimpan jika dikosongkan</p>
                            <div class="flex items-center gap-2 ml-auto">
                                <button type="button" onclick="closeUploadModal('{{ $req->id }}')"
                                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition">
                                    Batal
                                </button>
                                <button type="submit" id="btn-submit-{{ $req->id }}"
                                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2 text-sm font-bold text-white transition"
                                    style="background: linear-gradient(135deg, #7c3aed, #6d28d9)"
                                    onmouseover="this.style.background='linear-gradient(135deg,#6d28d9,#5b21b6)'"
                                    onmouseout="this.style.background='linear-gradient(135deg,#7c3aed,#6d28d9)'">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div><!-- /Modal Card -->
            </div>
        </div>
    </div>
@endforeach

<script>
    (() => {
        let linkIndices = {};

        const getLinkIndex = (reqId, initialCount) => {
            if (linkIndices[reqId] === undefined) linkIndices[reqId] = initialCount;
            return linkIndices[reqId]++;
        };

        const buildDriveLinkRow = (reqId, idx, nameVal, urlVal, showRemove) => {
            const row = document.createElement('div');
            row.className = 'flex items-start gap-2';
            row.id = `drive-row-${reqId}-${idx}`;

            const removeBtnHtml = showRemove
                ? `<button type="button" onclick="removeDriveLinkRow('${reqId}', ${idx})"
                        class="mt-5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-300 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                   </button>`
                : `<div class="mt-5 h-8 w-8 shrink-0"></div>`;

            row.innerHTML = `
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Dokumen</label>
                        <input type="text" name="google_drive_links[${idx}][name]" value="${nameVal}" placeholder="Contoh: SK Rektor..."
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Link Google Drive</label>
                        <input type="url" name="google_drive_links[${idx}][url]" value="${urlVal}" placeholder="https://drive.google.com/..."
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition">
                    </div>
                </div>
                ${removeBtnHtml}
            `;
            return row;
        };

        window.openUploadModal = (reqId, existingLinks) => {
            const modal = document.getElementById(`upload-modal-${reqId}`);
            const modalBox = document.getElementById(`modal-box-${reqId}`);

            // Pre-fill existing Google Drive links (Perbarui Berkas mode)
            if (existingLinks && existingLinks.length > 0) {
                const container = document.getElementById(`drive-links-container-${reqId}`);
                if (container) {
                    container.innerHTML = '';
                    existingLinks.forEach((link, idx) => {
                        const row = buildDriveLinkRow(reqId, idx, link.name || '', link.url || '', idx > 0);
                        container.appendChild(row);
                    });
                    linkIndices[reqId] = existingLinks.length + 10;
                }
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalBox.classList.remove('scale-95', 'opacity-0');
                modalBox.classList.add('scale-100', 'opacity-100');
            }, 50);
        };

        window.closeUploadModal = (reqId) => {
            const modal = document.getElementById(`upload-modal-${reqId}`);
            const modalBox = document.getElementById(`modal-box-${reqId}`);
            modalBox.classList.remove('scale-100', 'opacity-100');
            modalBox.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        };

        window.addDriveLinkRow = (reqId) => {
            const container = document.getElementById(`drive-links-container-${reqId}`);
            const rows = container.querySelectorAll('[id^="drive-row-"]');
            const idx = getLinkIndex(reqId, rows.length + 10);
            const row = buildDriveLinkRow(reqId, idx, '', '', true);
            container.appendChild(row);
        };

        window.removeDriveLinkRow = (reqId, idx) => {
            const row = document.getElementById(`drive-row-${reqId}-${idx}`);
            if (row) row.remove();
        };

        window.disableSubmitButton = (reqId) => {
            const btn = document.getElementById(`btn-submit-${reqId}`);
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg> Menyimpan...`;
                btn.style.opacity = '0.7';
                btn.style.cursor = 'not-allowed';
            }
        };

        window.updateFilePreview = (reqId, input) => {
            const preview = document.getElementById(`file-preview-${reqId}`);
            const label = document.getElementById(`dropzone-label-${reqId}`);
            if (!preview) return;
            const files = Array.from(input.files);
            if (!files.length) { preview.classList.add('hidden'); preview.innerHTML = ''; return; }

            preview.innerHTML = '';
            preview.classList.remove('hidden');
            files.forEach(file => {
                const sizeKb = (file.size / 1024).toFixed(1);
                const ext = file.name.split('.').pop().toLowerCase();
                const bg = ext === 'pdf' ? '#fef2f2' : '#f0fdf4';
                const fg = ext === 'pdf' ? '#b91c1c' : '#15803d';
                const item = document.createElement('div');
                item.className = 'flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5';
                item.innerHTML = `
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-[10px] font-bold" style="background:${bg};color:${fg}">${ext.toUpperCase()}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-semibold text-slate-700">${file.name}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">${sizeKb} KB</p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                `;
                preview.appendChild(item);
            });
            if (label) label.textContent = `${files.length} berkas dipilih`;
        };

        // Drag and drop
        document.querySelectorAll('[id^="dropzone-"]').forEach(zone => {
            zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-violet-400', 'bg-violet-50'); });
            zone.addEventListener('dragleave', () => zone.classList.remove('border-violet-400', 'bg-violet-50'));
            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('border-violet-400', 'bg-violet-50');
                const reqId = zone.id.replace('dropzone-', '');
                const input = document.getElementById(`file-input-${reqId}`);
                if (input && e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    updateFilePreview(reqId, input);
                }
            });
        });

        @if (session('failed_requirement_id'))
            const failedReqId = "{{ session('failed_requirement_id') }}";
            setTimeout(() => openUploadModal(failedReqId), 300);
        @endif
    })();
</script>
