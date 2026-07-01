<x-viewer-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Pratinjau dokumen</p>
                <h1 class="mt-0.5 truncate text-base font-bold text-slate-900 sm:text-lg">{{ $title }}</h1>
                <p class="mt-0.5 truncate text-xs text-slate-500" id="header-filename">{{ $filename }}</p>
            </div>
            <div class="flex shrink-0 flex-wrap gap-2">
                @if ($submission->file_path || (!empty($submission->files)))
                    <a href="{{ $inlineUrl }}" id="btn-tab-baru" target="_blank" rel="noopener" class="ui-btn-secondary py-2 text-xs">Tab baru</a>
                    <a href="{{ $downloadUrl }}" id="btn-download" class="ui-btn-secondary py-2 text-xs">Unduh</a>
                @endif
                <a href="{{ $backUrl }}" class="ui-btn-primary py-2 text-xs">Kembali</a>
            </div>
        </div>
    </x-slot>

    @php
        $googleDriveLinks = $submission->google_drive_links ?? [];
        $files = $submission->files ?? [];
        $hasMultiple = !empty($googleDriveLinks) || count($files) > 1;
        
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $isPdf = $ext === 'pdf';
    @endphp

    @if ($hasMultiple)
        <div class="flex flex-1 overflow-hidden min-h-0">
            <!-- Left panel: Sidebar listing files and links -->
            <div class="w-80 shrink-0 border-r border-slate-200 bg-slate-50 flex flex-col overflow-y-auto p-4 select-none">
                <!-- Google Drive Links Section -->
                @if (!empty($googleDriveLinks))
                    <div class="mb-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Link Google Drive</h3>
                        <div class="space-y-2">
                            @foreach ($googleDriveLinks as $link)
                                <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="flex items-start gap-2.5 rounded-xl border border-slate-200/60 bg-white p-3 hover:border-violet-300 hover:shadow-sm transition">
                                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800 break-words">{{ $link['name'] }}</p>
                                        <span class="mt-0.5 inline-flex items-center gap-0.5 text-[10px] font-medium text-violet-600">
                                            Buka Link
                                            <svg style="width:10px;height:10px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Files Section -->
                @if (!empty($files))
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Berkas Dokumen</h3>
                        <div class="space-y-2" id="file-list">
                            @foreach ($files as $index => $file)
                                @php
                                    $fileInlineUrl = route($routePrefix . '.submissions.inline', [$submission, 'file' => $index]);
                                    $fileDownloadUrl = route($routePrefix . '.submissions.download', [$submission, 'file' => $index]);
                                    $isActive = (int)$activeFileIndex === $index;
                                @endphp
                                <button
                                    type="button"
                                    data-file-index="{{ $index }}"
                                    data-inline-url="{{ $fileInlineUrl }}"
                                    data-download-url="{{ $fileDownloadUrl }}"
                                    data-filename="{{ $file['original_filename'] }}"
                                    onclick="selectFile({{ $index }}, '{{ $fileInlineUrl }}', '{{ $fileDownloadUrl }}', '{{ addslashes($file['original_filename']) }}')"
                                    class="w-full text-left flex items-start gap-2.5 rounded-xl border p-3 transition file-item-btn
                                        {{ $isActive 
                                            ? 'border-violet-500 bg-violet-50/50 text-violet-900 font-medium' 
                                            : 'border-slate-200/60 bg-white hover:border-slate-300' }}"
                                >
                                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800 truncate" title="{{ $file['original_filename'] }}">{{ $file['original_filename'] }}</p>
                                        <p class="mt-0.5 text-[10px] text-slate-500">{{ number_format($file['file_size'] / 1024, 1) }} KB</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right panel: iframe or empty state -->
            <div class="flex-1 flex flex-col min-h-0 bg-slate-100 relative">
                @if (!empty($files))
                    <div id="excel-warning" class="{{ $isPdf ? 'hidden' : '' }} shrink-0 border-b border-amber-200/60 bg-amber-50/95 px-4 py-2.5 text-xs text-amber-950 sm:px-6">
                        Berkas <strong>Excel</strong> tidak selalu dapat ditampilkan di dalam peramban. Gunakan <strong>Tab baru</strong> atau <strong>Unduh</strong> jika tampilan kosong.
                    </div>
                    <iframe
                        id="viewer-iframe"
                        src="{{ route($routePrefix . '.submissions.inline', [$submission, 'file' => $activeFileIndex]) }}"
                        title="Pratinjau Dokumen"
                        class="min-h-0 w-full flex-1 border-0 bg-white"
                    ></iframe>
                @else
                    <!-- No files, links only dashboard -->
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center" style="background: linear-gradient(145deg, #f8f7ff 0%, #ffffff 60%, #f0f4ff 100%);">
                        <div style="max-width: 360px; width: 100%;">
                            <!-- Icon container - strictly sized with inline style -->
                            <div style="width: 72px; height: 72px; border-radius: 20px; background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 16px rgba(139,92,246,0.18);">
                                <svg style="width: 32px; height: 32px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#7c3aed">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                                </svg>
                            </div>

                            <!-- Decorative badge -->
                            <div style="display: inline-flex; align-items: center; gap: 6px; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 99px; padding: 4px 12px; margin-bottom: 16px;">
                                <svg style="width: 10px; height: 10px; flex-shrink: 0;" fill="#8b5cf6" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #7c3aed;">Google Drive</span>
                            </div>

                            <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0 0 10px; line-height: 1.3;">Dokumen Berupa Link</h2>
                            <p style="font-size: 13px; color: #64748b; line-height: 1.65; margin: 0 0 24px;">
                                Berkas fisik tidak diunggah untuk persyaratan ini.<br>
                                Gunakan tautan di panel sebelah kiri untuk mengakses dokumen.
                            </p>

                            <!-- Arrow hint -->
                            <div style="display: inline-flex; align-items: center; gap: 8px; color: #a78bfa; font-size: 12px; font-weight: 600;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                                </svg>
                                Pilih link di panel kiri
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            function selectFile(index, inlineUrl, downloadUrl, filename) {
                // Update iframe source
                const iframe = document.getElementById('viewer-iframe');
                if (iframe) {
                    iframe.src = inlineUrl;
                }

                // Update headers & links
                document.getElementById('header-filename').textContent = filename;
                document.getElementById('btn-tab-baru').href = inlineUrl;
                document.getElementById('btn-download').href = downloadUrl;

                // Handle warning bar visibility
                const ext = filename.split('.').pop().toLowerCase();
                const warningBar = document.getElementById('excel-warning');
                if (warningBar) {
                    if (ext === 'pdf') {
                        warningBar.classList.add('hidden');
                    } else {
                        warningBar.classList.remove('hidden');
                    }
                }

                // Update active state in sidebar
                document.querySelectorAll('.file-item-btn').forEach(btn => {
                    const btnIdx = parseInt(btn.dataset.fileIndex);
                    if (btnIdx === index) {
                        btn.className = "w-full text-left flex items-start gap-2.5 rounded-xl border p-3 transition file-item-btn border-violet-500 bg-violet-50/50 text-violet-900 font-medium";
                    } else {
                        btn.className = "w-full text-left flex items-start gap-2.5 rounded-xl border p-3 transition file-item-btn border-slate-200/60 bg-white hover:border-slate-300";
                    }
                });
            }
        </script>
    @else
        <!-- Legacy / single file layout -->
        <div class="flex min-h-0 flex-1 flex-col">
            @unless ($isPdf)
                <div class="shrink-0 border-b border-amber-200/60 bg-amber-50/95 px-4 py-2.5 text-xs text-amber-950 sm:px-6">
                    Berkas <strong>Excel</strong> tidak selalu dapat ditampilkan di dalam peramban. Gunakan <strong>Tab baru</strong> atau <strong>Unduh</strong> jika tampilan kosong.
                </div>
            @endunless

            <iframe
                src="{{ $inlineUrl }}"
                title="Pratinjau {{ $filename }}"
                class="min-h-0 w-full flex-1 border-0 bg-white"
            ></iframe>
        </div>
    @endif
</x-viewer-layout>
