<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Modul</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $module->name }}</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.modules.requirements.index', $module) }}" class="ui-btn-primary text-sm">Persyaratan</a>
                <a href="{{ route('admin.modules.edit', $module) }}" class="ui-btn-secondary text-sm">Edit modul</a>
            </div>
        </div>
    </x-slot>

    <!-- Tautan Kembali ke modul -->
    <div class="mb-4">
        <a href="{{ route('admin.modules.index') }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500 flex items-center">
            &larr; Kembali ke modul
        </a>
    </div>

    @if ($module->description)
        <p class="mb-6 text-slate-600">{{ $module->description }}</p>
    @endif

    <div class="ui-card overflow-hidden">
        <div class="ui-section-header">
            <h2 class="text-lg font-bold text-slate-900">Daftar persyaratan</h2>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($module->requirements as $req)
                <li class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-violet-50/25">
                    <span class="font-medium text-slate-900">{{ $req->title }}</span>
                    <a href="{{ route('admin.modules.requirements.edit', [$module, $req]) }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Edit</a>
                </li>
            @empty
                <li class="ui-empty text-sm">Belum ada persyaratan.</li>
            @endforelse
        </ul>
    </div>

    <form method="post" action="{{ route('admin.modules.destroy', $module) }}" class="mt-8" onsubmit="return confirm('Hapus modul ini beserta persyaratannya?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Hapus modul</button>
    </form>
</x-app-layout>