<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">{{ $module->name }}</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Persyaratan</h1>
            </div>
            <a href="{{ route('admin.modules.requirements.create', $module) }}" class="ui-btn-primary shrink-0 text-sm">+ Tambah</a>
        </div>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.modules.show', $module) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">← Kembali ke modul</a>
    </div>

    <div class="ui-card divide-y divide-slate-100 overflow-hidden">
        @forelse ($requirements as $req)
            <div class="flex flex-wrap items-start justify-between gap-4 px-6 py-5 transition hover:bg-violet-50/25">
                <div class="min-w-0">
                    <p class="font-semibold text-slate-900">{{ $req->title }}</p>
                    @if ($req->description)
                        <p class="mt-1 text-sm text-slate-600">{{ $req->description }}</p>
                    @endif
                </div>
                <div class="flex shrink-0 gap-3 text-sm font-semibold">
                    <a href="{{ route('admin.modules.requirements.show', [$module, $req]) }}" class="text-slate-600 hover:text-slate-900">Detail</a>
                    <a href="{{ route('admin.modules.requirements.edit', [$module, $req]) }}" class="text-violet-600 hover:text-violet-500">Edit</a>
                </div>
            </div>
        @empty
            <div class="ui-empty text-sm">Belum ada persyaratan.</div>
        @endforelse
        <div class="border-t border-slate-100 bg-slate-50/50 px-4 py-3">{{ $requirements->links() }}</div>
    </div>
</x-app-layout>
