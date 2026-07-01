<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Admin</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Panel admin</h1>
            <p class="mt-1 text-sm text-slate-600">Akun dan dokumen seluruh sistem</p>
        </div>
    </x-slot>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total Universitas" :value="$pertiCount" accent="violet" />
        <x-stat-card label="Program studi" :value="$unitCount" accent="emerald" />
        <x-stat-card label="Persyaratan aktif" :value="$requirementsCount" accent="sky" />
        <x-stat-card label="Total semua berkas terkumpul" :value="$submissionsCount" accent="amber" />
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <div class="ui-card overflow-hidden">
            <div class="ui-section-header">
                <h2 class="text-lg font-bold text-slate-900">Pengguna terbaru</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-500">Kelola</a>
            </div>
            <ul class="divide-y divide-slate-100 text-sm">
                @foreach ($recentUsers as $u)
                    <li class="flex justify-between gap-2 px-4 py-3">
                        <span class="truncate font-medium text-slate-900">{{ $u->name }}</span>
                        <span class="shrink-0 text-xs text-slate-500">{{ $u->role->label() }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="ui-card overflow-hidden">
            <div class="ui-section-header">
                <h2 class="text-lg font-bold text-slate-900">Unggahan terbaru</h2>
                <a href="{{ route('admin.submissions.index') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-500">Semua</a>
            </div>
            <ul class="divide-y divide-slate-100 text-sm">
                @foreach ($recentSubmissions as $s)
                    <li class="px-4 py-3">
                        <div class="truncate font-medium text-slate-900">{{ $s->user->name }}</div>
                        <div class="truncate text-xs text-slate-500">{{ $s->requirement->title }}</div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="ui-card overflow-hidden">
        <div class="ui-section-header">
            <h2 class="text-lg font-bold text-slate-900">Modul akreditasi</h2>
            <a href="{{ route('admin.modules.index') }}" class="text-sm font-semibold text-violet-600 hover:text-violet-500">Kelola →</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @foreach ($modules as $m)
                <li class="flex items-center justify-between gap-4 px-6 py-3.5 text-sm transition hover:bg-violet-50/30">
                    <span class="font-semibold text-slate-900">{{ $m->name }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200/80">{{ $m->requirements_count }} persyaratan</span>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
