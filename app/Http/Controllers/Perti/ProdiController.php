<?php

namespace App\Http\Controllers\Perti;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProdiController extends Controller
{
    public function index(): View
    {
        $prodis = auth()->user()->prodis()
            ->orderBy('name')
            ->paginate(20);

        return view('perti.prodis.index', compact('prodis'));
    }

    public function create(): View
    {
        return view('perti.prodis.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->prodis()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => UserRole::UnitKerja,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('perti.prodis.index')->with('status', 'Akun program studi berhasil dibuat.');
    }

    public function edit(string $id): View
    {
        $prodi = auth()->user()->prodis()->findOrFail($id);

        return view('perti.prodis.edit', compact('prodi'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $prodi = auth()->user()->prodis()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $prodi->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $prodi->name = $validated['name'];
        $prodi->email = $validated['email'];

        if (! empty($validated['password'])) {
            $prodi->password = $validated['password'];
        }

        $prodi->save();

        return redirect()->route('perti.prodis.index')->with('status', 'Data program studi berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $prodi = auth()->user()->prodis()->findOrFail($id);
        $prodi->delete();

        return redirect()->route('perti.prodis.index')->with('status', 'Akun program studi berhasil dihapus.');
    }
}
