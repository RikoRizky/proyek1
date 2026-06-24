<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Dokumen</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $submission->requirement->title }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ $submission->requirement->module->name }}</p>
        </div>
    </x-slot>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="ui-card overflow-hidden">
            <div class="ui-section-header bg-gradient-to-r from-violet-50/90 to-white">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Informasi dokumen</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Detail berkas yang diunggah</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                        <dt class="font-medium text-slate-500">Unit kerja</dt>
                        <dd class="font-semibold text-slate-900">{{ $submission->user->name }}</dd>
                    </div>

                    <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                        <dt class="font-medium text-slate-500">Berkas</dt>
                        <dd class="max-w-[60%] truncate text-right font-medium text-slate-800">{{ $submission->original_filename }}</dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="font-medium text-slate-500">Versi</dt>
                        <dd class="font-bold tabular-nums text-violet-700">{{ $submission->version }}</dd>
                    </div>
                </dl>

                <div class="mt-6 flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('asesor.submissions.view', $submission) }}" class="ui-btn-primary flex-1 justify-center">Lihat di aplikasi</a>
                    <a href="{{ route('asesor.submissions.download', $submission) }}" class="ui-btn-secondary flex-1 justify-center">Unduh</a>
                </div>
            </div>
        </div>

        <div class="ui-card overflow-hidden">
            <div class="ui-section-header bg-gradient-to-r from-indigo-50/90 to-white">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Penilaian</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Isi skor dan komentar untuk persyaratan ini.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form method="POST" action="{{ route('asesor.submissions.store', $submission) }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="score" :value="'Skor'" />
                        <input
                            id="score"
                            name="score"
                            type="number"
                            step="0.01"
                            min="0"
                            class="mt-1 block w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                            value="{{ old('score', $assessment?->score) }}"
                            required
                        />
                        @error('score')
                            <div class="mt-1 text-sm text-rose-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="comments" :value="'Komentar'" />
                        <textarea
                            id="comments"
                            name="comments"
                            rows="6"
                            class="mt-1 block w-full rounded-lg border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                            placeholder="Tulis alasan/pertimbangan penilaian..."
                        >{{ old('comments', $assessment?->comments) }}</textarea>
                        @error('comments')
                            <div class="mt-1 text-sm text-rose-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-6 flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                        <a href="{{ route('asesor.queue.index') }}" class="ui-btn-secondary">Batal</a>
                        <button type="submit" class="ui-btn-primary">Simpan penilaian</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

