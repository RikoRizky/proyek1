<?php

namespace App\Http\Controllers\Asesor;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Concerns\SendsSubmissionFile;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssessmentController extends Controller
{
    use SendsSubmissionFile;

    public function show(Submission $submission): View
    {
        $this->authorizeAsesorSubmission($submission);

        if ($submission->status === SubmissionStatus::Uploaded) {
            $submission->update(['status' => SubmissionStatus::UnderReview]);
        }

        $submission->load(['requirement.module', 'user', 'assessment']);

        return view('asesor.assessments.show', [
            'submission' => $submission,
        ]);
    }

    public function store(Request $request, Submission $submission): RedirectResponse
    {
        $this->authorizeAsesorSubmission($submission);

        $validated = $request->validate([
            'score' => ['required', 'integer', 'min:1', 'max:4'],
            'comments' => ['nullable', 'string', 'max:5000'],
        ]);

        Assessment::query()->updateOrCreate(
            ['submission_id' => $submission->id],
            [
                'asesor_id' => $request->user()->id,
                'score' => $validated['score'],
                'comments' => $validated['comments'] ?? null,
            ]
        );

        $submission->update(['status' => SubmissionStatus::Completed]);

        return redirect()->route('asesor.queue.index')->with('status', 'Penilaian disimpan.');
    }

    public function viewer(Submission $submission): View
    {
        $this->authorizeAsesorSubmission($submission);

        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        $submission->load('requirement.module');

        return view('submissions.viewer', [
            'title' => $submission->requirement->title.' — '.$submission->user->name,
            'filename' => $submission->original_filename,
            'inlineUrl' => route('asesor.submissions.inline', $submission),
            'downloadUrl' => route('asesor.submissions.download', $submission),
            'backUrl' => route('asesor.submissions.show', $submission),
        ]);
    }

    public function inline(Submission $submission): StreamedResponse
    {
        $this->authorizeAsesorSubmission($submission);

        return $this->submissionInlineResponse($submission);
    }

    public function download(Submission $submission): StreamedResponse
    {
        $this->authorizeAsesorSubmission($submission);

        return $this->submissionDownloadResponse($submission);
    }

    private function authorizeAsesorSubmission(Submission $submission): void
    {
        $user = auth()->user();
        abort_unless($user && $user->role === UserRole::Asesor, 403);
        abort_unless($submission->user?->role === UserRole::UnitKerja, 403);
        abort_unless($submission->is_latest, 403);
    }
}
