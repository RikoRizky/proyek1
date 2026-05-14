<?php

namespace App\Http\Controllers\Asesor;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AllDocumentsController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $q = trim((string) $request->get('q', ''));

        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->whereHas('submissions', function ($query) use ($status) {
                $query->where('is_latest', true);
                if ($status === 'pending') {
                    $query->whereIn('status', [SubmissionStatus::Uploaded, SubmissionStatus::UnderReview]);
                } elseif ($status === 'completed') {
                    $query->where('status', SubmissionStatus::Completed);
                }
            })
            ->with(['submissions' => function ($query) use ($status) {
                $query->latestForUnit()
                    ->with(['requirement.module', 'assessment.asesor'])
                    ->when($status === 'pending', fn ($q) => $q->whereIn('status', [SubmissionStatus::Uploaded, SubmissionStatus::UnderReview]))
                    ->when($status === 'completed', fn ($q) => $q->where('status', SubmissionStatus::Completed))
                    ->orderByDesc('updated_at');
            }])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhereHas('submissions', function ($s) use ($q) {
                            $s->where('is_latest', true)
                                ->where(function ($inner) use ($q) {
                                    $inner->whereHas('requirement', fn ($r) => $r->where('title', 'like', "%{$q}%"))
                                        ->orWhere('original_filename', 'like', "%{$q}%");
                                });
                        });
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('asesor.documents.index', compact('units', 'status', 'q'));
    }
}
