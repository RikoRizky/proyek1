<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\View\View;

class AssessmentOverviewController extends Controller
{
    public function index(): View
    {
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->whereHas('submissions', function ($q) {
                $q->where('is_latest', true)->whereHas('assessment');
            })
            ->with(['submissions' => function ($q) {
                $q->where('is_latest', true)
                    ->whereHas('assessment')
                    ->with(['assessment.asesor', 'requirement.module'])
                    ->orderByDesc('updated_at');
            }])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.assessments.index', compact('units'));
    }
}
