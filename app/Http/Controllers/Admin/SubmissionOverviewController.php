<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Concerns\SendsSubmissionFile;
use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionOverviewController extends Controller
{
    use SendsSubmissionFile;

    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));

        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->with(['submissions' => function ($query) {
                $query->latestForUnit()
                    ->with(['requirement.module'])
                    ->orderBy('requirement_id');
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

        return view('admin.submissions.index', compact('units', 'q'));
    }

    public function viewer(Submission $submission): View
    {
        $this->authorizeAdminSubmission($submission);

        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        $submission->load(['requirement.module', 'user']);

        return view('submissions.viewer', [
            'title' => $submission->requirement->title.' — '.$submission->user->name,
            'filename' => $submission->original_filename,
            'inlineUrl' => route('admin.submissions.inline', $submission),
            'downloadUrl' => route('admin.submissions.download', $submission),
            'backUrl' => route('admin.submissions.index'),
        ]);
    }

    public function inline(Submission $submission): StreamedResponse
    {
        $this->authorizeAdminSubmission($submission);

        return $this->submissionInlineResponse($submission);
    }

    public function download(Submission $submission): StreamedResponse
    {
        $this->authorizeAdminSubmission($submission);

        return $this->submissionDownloadResponse($submission);
    }

    private function authorizeAdminSubmission(Submission $submission): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        abort_unless($submission->user?->role === UserRole::UnitKerja, 403);
    }
}
