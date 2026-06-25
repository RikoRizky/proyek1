@php
    use App\Enums\UserRole;
    use App\Models\Module;
    $role = auth()->user()->role;

    $navLink = function (bool $active) {
        return $active
            ? 'bg-white/10 text-white shadow-inner ring-1 ring-white/10'
            : 'text-slate-300 hover:bg-white/5 hover:text-white';
    };

    $subNavLink = function (bool $active) {
        return $active
            ? 'bg-white/10 text-white ring-1 ring-white/10'
            : 'text-slate-400 hover:bg-white/5 hover:text-slate-100';
    };

    $uploadModules = $role === UserRole::UnitKerja
        ? Module::query()->orderBy('sort_order')->get()
        : collect();

    $uploadMenuActive = request()->routeIs('unit.submissions.module');
@endphp

{{-- Mobile top bar (in document flow) --}}
<header class="sticky top-0 z-30 flex h-14 shrink-0 items-center justify-between gap-3 border-b border-slate-200/80 bg-white/90 px-4 shadow-sm backdrop-blur-md lg:hidden">
    <button type="button" @click="sidebarOpen = true" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm" aria-label="Buka menu">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
    </button>
    <span class="truncate text-sm font-semibold text-slate-800">Akreditasi</span>
    <div class="h-9 w-9 shrink-0 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 shadow-md ring-2 ring-white"></div>
</header>

{{-- Mobile backdrop --}}
<div
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
    style="display: none;"
    @click="sidebarOpen = false"
></div>

{{-- Sidebar: fixed on all breakpoints; main area uses lg:pl-64 --}}
<aside
    class="fixed inset-y-0 left-0 z-50 flex w-[min(18rem,88vw)] flex-col border-r border-white/10 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 shadow-2xl transition-transform duration-300 ease-out lg:z-40 lg:w-64 lg:max-w-none lg:translate-x-0 lg:shadow-xl"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 text-xs font-bold text-white shadow-lg shadow-violet-500/30">
            SP
        </div>
        <a href="{{ route('dashboard') }}" class="min-w-0 leading-tight" @click="sidebarOpen = false">
            <span class="block truncate text-sm font-semibold tracking-tight text-white">Akreditasi</span>
            <span class="block truncate text-[11px] font-medium text-slate-400">Penguploadan data</span>
        </a>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4 text-sm">
        <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition {{ $navLink(request()->routeIs('dashboard')) }}">
            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            Ringkasan
        </a>

        @if ($role === UserRole::Admin)
            <p class="px-3 pt-5 pb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Admin</p>
            <a href="{{ route('admin.home') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition {{ $navLink(request()->routeIs('admin.home')) }}">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                Panel admin
            </a>
            <a href="{{ route('admin.users.index') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition {{ $navLink(request()->routeIs('admin.users.*')) }}">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                Akun program studi
            </a>
            <a href="{{ route('admin.modules.index') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition {{ $navLink(request()->routeIs('admin.modules.*') || request()->routeIs('admin.modules.requirements.*')) }}">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.051.2-2.966.55M12 6.042A8.967 8.967 0 0118 3.75c1.052 0 2.051.2 2.966.55M12 6.042v8.458m0 0a8.967 8.967 0 01-6 3.292m6-3.292a8.967 8.967 0 006 3.292"/></svg>
                Modul &amp; syarat
            </a>
            <a href="{{ route('admin.submissions.index') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition {{ $navLink(request()->routeIs('admin.submissions.*')) }}">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Semua dokumen
            </a>
            <p class="px-3 pt-5 pb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Laporan</p>
            <a href="{{ route('admin.reports.pdf') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium text-slate-300 transition hover:bg-white/5 hover:text-white">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Ekspor PDF
            </a>
            <a href="{{ route('admin.reports.excel') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium text-slate-300 transition hover:bg-white/5 hover:text-white">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Ekspor Excel
            </a>
        @endif

        @if ($role === UserRole::UnitKerja)
            <p class="px-3 pt-5 pb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Unit kerja</p>

            <div x-data="{ uploadOpen: {{ $uploadMenuActive ? 'true' : 'false' }} }">
                <button type="button"
                        @click="uploadOpen = !uploadOpen"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 font-medium transition {{ $uploadMenuActive ? 'bg-white/10 text-white shadow-inner ring-1 ring-white/10' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75v-2.25M7.5 7.5h9M7.5 10.5h5.25M7.5 4.5v9"/></svg>
                    <span class="flex-1 text-left">Unggah dokumen</span>
                    <svg class="h-4 w-4 shrink-0 transition-transform" :class="uploadOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>

                <div x-show="uploadOpen" x-cloak class="mt-1 space-y-0.5 pl-3">
                    @foreach ($uploadModules as $uploadModule)
                        @php
                            $moduleActive = request()->routeIs('unit.submissions.module') && request()->route('module')?->is($uploadModule);
                        @endphp
                        <a href="{{ route('unit.submissions.module', $uploadModule) }}" @click="sidebarOpen = false"
                           class="block rounded-lg px-3 py-2 text-[13px] font-medium transition {{ $subNavLink($moduleActive) }}"
                           title="{{ $uploadModule->name }}">
                            <span class="block truncate">{{ $uploadModule->shortLabel() }}</span>
                            <span class="mt-0.5 block truncate text-[11px] opacity-70">{{ \Illuminate\Support\Str::after($uploadModule->name, ': ') ?: $uploadModule->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('unit.reports.pdf') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium text-slate-300 transition hover:bg-white/5 hover:text-white">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Laporan PDF
            </a>
            <a href="{{ route('unit.reports.excel') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-medium text-slate-300 transition hover:bg-white/5 hover:text-white">
                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Laporan Excel
            </a>
        @endif
    </nav>

    <div class="mt-auto border-t border-white/10 p-4">
        <div class="rounded-xl bg-white/5 p-3 ring-1 ring-white/10">
            <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
            <p class="mt-0.5 truncate text-xs text-violet-300">{{ $role->label() }}</p>
            <div class="mt-3 flex gap-2 lg:hidden">
                <a href="{{ route('profile.edit') }}" class="ui-btn-secondary flex-1 py-2 text-xs" @click="sidebarOpen = false">Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="ui-btn-secondary w-full py-2 text-xs">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</aside>
