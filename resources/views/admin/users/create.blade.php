<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Pengguna</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Buat akun baru</h1>
            <p class="mt-1 text-sm text-slate-600">Untuk program studi (unggah dokumen) atau asesor (penilaian).</p>
        </div>
    </x-slot>

    <div class="ui-card max-w-xl p-6 sm:p-8">
        <form method="post" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf
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
                <label class="block text-sm font-semibold text-slate-700">Peran</label>
                <select name="role" class="ui-input mt-2" required>
                    <option value="{{ \App\Enums\UserRole::UnitKerja->value }}" @selected(old('role', \App\Enums\UserRole::UnitKerja->value) === \App\Enums\UserRole::UnitKerja->value)>Program studi (unit kerja)</option>
                    <option value="{{ \App\Enums\UserRole::Asesor->value }}" @selected(old('role', \App\Enums\UserRole::UnitKerja->value) === \App\Enums\UserRole::Asesor->value)>Asesor</option>
                </select>
                @error('role')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
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
