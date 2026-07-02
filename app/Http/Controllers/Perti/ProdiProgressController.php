<?php

namespace App\Http\Controllers\Perti;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Prodi;
use App\Support\UploadProgress;
use Illuminate\View\View;

class ProdiProgressController extends Controller
{
    /**
     * Menampilkan overview progress per modul dari sebuah prodi milik perti yang sedang login.
     */
    public function index(string $prodi): View
    {
        $pertiProfile = auth()->user()->pertiProfile;
        abort_if(is_null($pertiProfile), 403);

        // Pastikan prodi ini benar milik perti yang sedang login
        $prodiRecord = Prodi::query()
            ->where('id', $prodi)
            ->where('perti_id', $pertiProfile->id)
            ->with('user')
            ->firstOrFail();

        $prodiUser = $prodiRecord->user;

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
        $pertiProfile = auth()->user()->pertiProfile;
        abort_if(is_null($pertiProfile), 403);

        // Pastikan prodi ini benar milik perti yang sedang login
        $prodiRecord = Prodi::query()
            ->where('id', $prodi)
            ->where('perti_id', $pertiProfile->id)
            ->with('user')
            ->firstOrFail();

        $prodiUser = $prodiRecord->user;

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
