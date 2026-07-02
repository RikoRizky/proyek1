<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Pengguna</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Akun program studi</h1>
                <p class="mt-1 text-sm text-slate-600">Prodi meminta akun kepada admin; tidak ada pendaftaran mandiri.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="ui-btn-primary shrink-0">+ Buat akun</a>
        </div>
    </x-slot>

    @error('delete')
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ $message }}</div>
    @enderror

    <div class="ui-table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td class="font-semibold text-slate-900">
                            {{ $u->name }}
                            @if ($u->perti)
                                <span class="block text-xs font-normal text-slate-500">Induk: {{ $u->perti->name }}</span>
                            @endif
                        </td>
                        <td class="text-slate-600">{{ $u->email }}</td>
                        <td>
                            <span class="ui-badge
                                @if($u->role === \App\Enums\UserRole::Admin) bg-violet-100 text-violet-900 ring-violet-500/20
                                @elseif($u->role === \App\Enums\UserRole::Perti) bg-sky-100 text-sky-900 ring-sky-500/20
                                @else bg-emerald-100 text-emerald-900 ring-emerald-500/20 @endif">{{ $u->role->label() }}</span>
                        </td>
                        <td class="text-right text-sm font-semibold">
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-violet-600 hover:text-violet-500">Edit</a>
                            @if ($u->id !== auth()->id())
                                <span class="mx-2 text-slate-300">|</span>
                                <form action="{{ route('admin.users.destroy', $u) }}" method="post" class="inline" onsubmit="return confirm('Hapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-500">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="border-t border-slate-100 bg-slate-50/50 px-4 py-3">{{ $users->links() }}</div>
    </div>
</x-app-layout>
