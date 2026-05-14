<x-viewer-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Pratinjau dokumen</p>
                <h1 class="mt-0.5 truncate text-base font-bold text-slate-900 sm:text-lg">{{ $title }}</h1>
                <p class="mt-0.5 truncate text-xs text-slate-500">{{ $filename }}</p>
            </div>
            <div class="flex shrink-0 flex-wrap gap-2">
                <a href="{{ $inlineUrl }}" target="_blank" rel="noopener" class="ui-btn-secondary py-2 text-xs">Tab baru</a>
                <a href="{{ $downloadUrl }}" class="ui-btn-secondary py-2 text-xs">Unduh</a>
                <a href="{{ $backUrl }}" class="ui-btn-primary py-2 text-xs">Kembali</a>
            </div>
        </div>
    </x-slot>

    @php
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $isPdf = $ext === 'pdf';
    @endphp

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
</x-viewer-layout>
