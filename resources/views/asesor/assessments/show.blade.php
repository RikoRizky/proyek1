<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Penilaian</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $submission->requirement->title }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ $submission->requirement->module->name }}</p>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="ui-card p-6 sm:p-8">
            <h3 class="text-lg font-bold text-slate-900">Informasi dokumen</h3>
            <dl class="mt-4 space-y-3 text-sm">
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

        <div class="ui-card p-6 sm:p-8">
            <h3 class="text-lg font-bold text-slate-900">Form penilaian</h3>
            <p class="mt-1 text-sm text-slate-500">Skor pada skala 1 hingga 4</p>
            <form method="post" action="{{ route('asesor.submissions.assessments.store', $submission) }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Skor</label>
                    <select name="score" class="ui-input mt-2" required>
                        @php
                            $labels = [1 => 'Sangat kurang', 2 => 'Kurang', 3 => 'Baik', 4 => 'Sangat baik'];
                        @endphp
                        @for ($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" @selected(old('score', $submission->assessment?->score) == $i)>{{ $i }} — {{ $labels[$i] }}</option>
                        @endfor
                    </select>
                    @error('score')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Umpan balik</label>
                    <textarea name="comments" rows="6" class="ui-input mt-2" placeholder="Tulis catatan untuk unit kerja...">{{ old('comments', $submission->assessment?->comments) }}</textarea>
                </div>
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="ui-btn-primary">Simpan penilaian</button>
                    <a href="{{ route('asesor.queue.index') }}" class="ui-btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
