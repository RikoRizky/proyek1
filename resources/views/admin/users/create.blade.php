<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-600">Pengguna</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Buat akun baru</h1>
            <p class="mt-1 text-sm text-slate-600">Buat akun untuk administrator, perguruan tinggi, atau program studi.</p>
        </div>
    </x-slot>

    <div class="ui-card max-w-xl mx-auto p-6 sm:p-8">
        <form method="post" action="{{ route('admin.users.store') }}" class="space-y-5" id="userForm">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700">Nama Lengkap / Instansi</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="ui-input mt-2" placeholder="Contoh: ITB atau Prodi Teknik Informatika">
                @error('name')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700">Email login</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="ui-input mt-2" placeholder="nama@domain.com">
                @error('email')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700">Peran (Role)</label>
                <select name="role" id="roleSelect" class="ui-input mt-2" required>
                    <option value="" disabled selected>-- Pilih Peran --</option>
                    @foreach ([\App\Enums\UserRole::Admin, \App\Enums\UserRole::Perti, \App\Enums\UserRole::UnitKerja] as $r)
                        <option value="{{ $r->value }}" @selected(old('role') === $r->value)>{{ $r->label() }}</option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div id="pertiGroup" class="hidden">
                <label class="block text-sm font-semibold text-slate-700">Perguruan Tinggi Induk</label>
                <select name="perti_id" id="pertiSelect" class="ui-input mt-2">
                    <option value="" disabled selected>-- Pilih Perguruan Tinggi --</option>
                    @foreach ($pertis as $p)
                        <option value="{{ $p->id }}" @selected(old('perti_id') == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('perti_id')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const pertiGroup = document.getElementById('pertiGroup');
            const pertiSelect = document.getElementById('pertiSelect');

            function togglePertiGroup() {
                if (roleSelect.value === '{{ \App\Enums\UserRole::UnitKerja->value }}') {
                    pertiGroup.classList.remove('hidden');
                    pertiSelect.setAttribute('required', 'required');
                } else {
                    pertiGroup.classList.add('hidden');
                    pertiSelect.removeAttribute('required');
                }
            }

            roleSelect.addEventListener('change', togglePertiGroup);
            togglePertiGroup(); // run on page load for old values
        });
    </script>
</x-app-layout>
