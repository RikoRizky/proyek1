<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'asesor' THEN 2 WHEN 'unit_kerja' THEN 3 END")
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => UserRole::from($request->validated('role')),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Akun berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($user->isAdmin() && User::query()->where('role', UserRole::Admin)->count() === 1) {
            if (UserRole::from($request->validated('role')) !== UserRole::Admin) {
                return redirect()->back()->withErrors(['role' => 'Harus ada minimal satu akun administrator.'])->withInput();
            }
        }

        $data = $request->safe()->only(['name', 'email', 'role']);
        $data['role'] = UserRole::from($data['role']);

        $user->fill($data);

        if (! empty($request->validated('password'))) {
            $user->password = $request->validated('password');
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'Data pengguna diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->withErrors(['delete' => 'Anda tidak dapat menghapus akun sendiri.']);
        }

        if ($user->isAdmin() && User::query()->where('role', UserRole::Admin)->count() === 1) {
            return redirect()->route('admin.users.index')->withErrors(['delete' => 'Tidak dapat menghapus administrator terakhir.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Akun dihapus.');
    }
}
