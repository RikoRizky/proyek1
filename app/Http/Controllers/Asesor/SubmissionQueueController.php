<?php

namespace App\Http\Controllers\Asesor;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class SubmissionQueueController extends Controller
{
    public function index(): View
    {
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->whereHas('submissions', function ($query) {
                $query->where('is_latest', true)
                    ->whereIn('status', [SubmissionStatus::Uploaded, SubmissionStatus::UnderReview]);
            })
            ->with(['submissions' => function ($query) {
                $query->latestForUnit()
                    ->with(['requirement.module'])
                    ->whereIn('status', [SubmissionStatus::Uploaded, SubmissionStatus::UnderReview])
                    ->orderByDesc('updated_at');
            }])
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('asesor.queue', compact('units'));
    }

    public function completed(): View
    {
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->whereHas('submissions', function ($query) {
                $query->where('is_latest', true)
                    ->where('status', SubmissionStatus::Completed);
            })
            ->with(['submissions' => function ($query) {
                $query->latestForUnit()
                    ->with(['requirement.module', 'assessment.asesor'])
                    ->where('status', SubmissionStatus::Completed)
                    ->orderByDesc('updated_at');
            }])
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('asesor.completed', compact('units'));
    }
}
