<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Pengguna</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Buat akun program studi</h1>
            <p class="mt-1 text-sm text-slate-600">Akun untuk program studi yang mengunggah dokumen akreditasi.</p>
        </div>
    </x-slot>

    <div class="ui-card max-w-xl p-6 sm:p-8">
        <form method="post" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="role" value="{{ \App\Enums\UserRole::UnitKerja->value }}">
            <div>
                <label class="block text-sm font-semibold text-slate-700">Nama (mis. Program Studi Manajemen)</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="ui-input mt-2">
                @error('name')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700">Email login</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="ui-input mt-2">
                @error('email')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700">Kata sandi awal</label>
                <input type="password" name="password" required class="ui-input mt-2" autocomplete="new-password">
                @error('password')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700">Ulangi kata sandi</label>
                <input type="password" name="password_confirmation" required class="ui-input mt-2" autocomplete="new-password">
            </div>
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="ui-btn-primary">Simpan akun</button>
                <a href="{{ route('admin.users.index') }}" class="ui-btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
