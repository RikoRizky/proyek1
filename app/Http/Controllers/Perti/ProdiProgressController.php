<?php

namespace App\Http\Controllers\Perti;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Support\UploadProgress;
use App\Models\User;
use Illuminate\View\View;

class ProdiProgressController extends Controller
{
    /**
     * Menampilkan overview progress per modul dari sebuah prodi milik perti yang sedang login.
     */
    public function index(string $prodi): View
    {
        /** @var User $perti */
        $perti = auth()->user();

        // Pastikan prodi ini benar milik perti yang sedang login
        $prodiUser = $perti->prodis()->findOrFail($prodi);

        $progress = UploadProgress::forUnit($prodiUser);

        return view('perti.progress.index', [
            'prodi'    => $prodiUser,
            'progress' => $progress,
        ]);
    }

    /**
     * Menampilkan detail syarat-syarat dalam satu modul untuk sebuah prodi milik perti.
     */
    public function module(string $prodi, Module $module): View
    {
        /** @var User $perti */
        $perti = auth()->user();

        // Pastikan prodi ini benar milik perti yang sedang login
        $prodiUser = $perti->prodis()->findOrFail($prodi);

        // Ambil semua requirements dari modul ini beserta submission terbaru dari prodi ini
        $requirements = $module->requirements()
            ->with(['submissions' => function ($q) use ($prodiUser) {
                $q->where('user_id', $prodiUser->id)
                  ->where('is_latest', true)
                  ->orderBy('version', 'desc');
            }])
            ->get();

        $allModules = Module::query()->orderBy('sort_order')->get();

        return view('perti.progress.module', [
            'prodi'        => $prodiUser,
            'module'       => $module,
            'requirements' => $requirements,
            'allModules'   => $allModules,
        ]);
    }
}
