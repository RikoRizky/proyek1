<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Perguruan Tinggi</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Kelola Program Studi</h1>
                <p class="mt-1 text-sm text-slate-600">Daftar program studi di bawah naungan {{ auth()->user()->name }}.</p>
            </div>
            <a href="{{ route('perti.prodis.create') }}" class="ui-btn-primary shrink-0">+ Buat akun prodi</a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mb-6 ui-alert-success" role="status">
            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white text-xs font-bold">✓</span>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="ui-table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Nama Program Studi</th>
                    <th>Email Login</th>
                    <th>Peran</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prodis as $p)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $p->name }}</td>
                        <td class="text-slate-600">{{ $p->email }}</td>
                        <td>
                            <span class="ui-badge bg-emerald-100 text-emerald-900 ring-emerald-500/20">{{ $p->role->label() }}</span>
                        </td>
                        <td class="text-right text-sm font-semibold">
                            <a href="{{ route('perti.prodis.edit', $p) }}" class="text-violet-600 hover:text-violet-500">Edit</a>
                            <span class="mx-2 text-slate-300">|</span>
                            <form action="{{ route('perti.prodis.destroy', $p) }}" method="post" class="inline" onsubmit="return confirm('Hapus akun program studi ini? Semua dokumen unggahan mereka juga akan terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-500">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="ui-empty text-center py-8 text-slate-500">
                            Belum ada program studi yang dibuat. Klik tombol "+ Buat akun prodi" untuk menambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($prodis->hasPages())
            <div class="border-t border-slate-100 bg-slate-50/50 px-4 py-3">{{ $prodis->links() }}</div>
        @endif
    </div>
</x-app-layout>
