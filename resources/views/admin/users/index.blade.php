<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Manajemen Akun</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Daftar Pengguna</h1>
                <p class="mt-1 text-sm text-slate-600">Kelola semua akun: Administrator, Perguruan Tinggi, dan Program Studi.</p>
            </div>
        </div>
    </x-slot>



    @error('delete')
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ $message }}</div>
    @enderror

    {{-- ── SECTION 1: ADMINISTRATOR ── --}}
    <div class="mb-10">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Administrator</h2>
                <p class="text-xs text-slate-500">Akun dengan akses penuh ke seluruh sistem.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="ui-btn-primary shrink-0">+ Tambah Admin</a>
        </div>
        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $u)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $u->name }}</td>
                            <td class="text-slate-600">{{ $u->email }}</td>
                            <td class="text-right text-sm font-semibold">
                                <a href="{{ route('admin.users.edit', $u) }}" class="text-violet-600 hover:text-violet-500">Edit</a>
                                @if ($u->id !== auth()->id())
                                    <span class="mx-2 text-slate-300">|</span>
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="post" class="inline" onsubmit="return confirm('Hapus akun administrator ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-500">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-sm text-slate-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── SECTION 2: PERGURUAN TINGGI ── --}}
    <div class="mb-10">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Perguruan Tinggi</h2>
                <p class="text-xs text-slate-500">Akun institusi; setiap perti dapat membuat akun program studi sendiri.</p>
            </div>
            <a href="{{ route('admin.pertis.create') }}" class="ui-btn-primary shrink-0">+ Tambah Perti</a>
        </div>
        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama Institusi</th>
                        <th>Email</th>
                        <th>Kode PT</th>
                        <th>Jml. Prodi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pertis as $u)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $u->name }}</td>
                            <td class="text-slate-600">{{ $u->email }}</td>
                            <td class="text-slate-500 text-sm">{{ $u->pertiProfile?->kode_pt ?? '-' }}</td>
                            <td class="text-slate-700 font-medium">{{ $u->pertiProfile?->prodis->count() ?? 0 }}</td>
                            <td class="text-right text-sm font-semibold">
                                <a href="{{ route('admin.pertis.edit', $u) }}" class="text-violet-600 hover:text-violet-500">Edit</a>
                                <span class="mx-2 text-slate-300">|</span>
                                <form action="{{ route('admin.pertis.destroy', $u) }}" method="post" class="inline" onsubmit="return confirm('Hapus akun perti ini? Semua program studi di bawahnya juga akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-500">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-sm text-slate-500">Belum ada akun Perguruan Tinggi. Buat dengan tombol di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($pertis->hasPages())
                <div class="border-t border-slate-100 bg-slate-50/50 px-4 py-3">{{ $pertis->links() }}</div>
            @endif
        </div>
    </div>

    {{-- ── SECTION 3: PROGRAM STUDI ── --}}
    <div>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Program Studi</h2>
                <p class="text-xs text-slate-500">Akun program studi dibuat oleh universitas atau oleh administrator.</p>
            </div>
            <a href="{{ route('admin.prodis.create') }}" class="ui-btn-primary shrink-0">+ Tambah Prodi</a>
        </div>
        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama Program Studi</th>
                        <th>Email</th>
                        <th>Perguruan Tinggi</th>
                        <th>Kode Prodi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prodis as $u)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $u->name }}</td>
                            <td class="text-slate-600">{{ $u->email }}</td>
                            <td class="text-slate-700 text-sm">
                                {{ $u->prodiProfile?->perti?->user?->name ?? '-' }}
                            </td>
                            <td class="text-slate-500 text-sm">{{ $u->prodiProfile?->kode_prodi ?? '-' }}</td>
                            <td class="text-right text-sm font-semibold">
                                <a href="{{ route('admin.prodis.edit', $u) }}" class="text-violet-600 hover:text-violet-500">Edit</a>
                                <span class="mx-2 text-slate-300">|</span>
                                <form action="{{ route('admin.prodis.destroy', $u) }}" method="post" class="inline" onsubmit="return confirm('Hapus akun program studi ini? Semua dokumen unggahan mereka juga akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-500">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-sm text-slate-500">Belum ada akun Program Studi. Perti yang login dapat membuat akun prodi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($prodis->hasPages())
                <div class="border-t border-slate-100 bg-slate-50/50 px-4 py-3">{{ $prodis->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
