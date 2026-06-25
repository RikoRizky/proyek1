<?php

namespace App\Http\Controllers\UnitKerja;

use App\Http\Controllers\Controller;
use App\Support\UploadProgress;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        return view('unit.progress.index', [
            'progress' => UploadProgress::forUnit($user),
        ]);
    }
}
