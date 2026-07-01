<?php

namespace App\Http\Controllers\Perti;

use App\Enums\UserRole;
use App\Http\Controllers\Concerns\SendsSubmissionFile;
use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    use SendsSubmissionFile;

    public function show(Submission $submission): View
    {
        $submission->load(['user', 'requirement.module']);
        $this->authorizeSubmission($submission);

        $history = Submission::query()
            ->where('requirement_id', $submission->requirement_id)
            ->where('user_id', $submission->user_id)
            ->orderByDesc('version')
            ->get();

        return view('perti.submissions.show', compact('submission', 'history'));
    }

    public function viewer(Submission $submission): View
    {
        $submission->load(['user', 'requirement.module']);
        $this->authorizeSubmission($submission);

        $fileIndex = request()->query('file');
        $activeFileIndex = 0;

        if ($fileIndex !== null) {
            $activeFileIndex = (int) $fileIndex;
        }

        $files = $submission->files ?? [];
        $activeFile = $files[$activeFileIndex] ?? null;

        $filename = $activeFile ? $activeFile['original_filename'] : $submission->original_filename;

        return view('submissions.viewer', [
            'submission' => $submission,
            'title' => $submission->requirement->title,
            'filename' => $filename,
            'activeFileIndex' => $activeFileIndex,
            'routePrefix' => 'perti',
            'inlineUrl' => route('perti.submissions.inline', [$submission, 'file' => $activeFileIndex]),
            'downloadUrl' => route('perti.submissions.download', [$submission, 'file' => $activeFileIndex]),
            'backUrl' => route('perti.submissions.show', $submission),
        ]);
    }

    public function inline(Submission $submission): StreamedResponse
    {
        $submission->load('user');
        $this->authorizeSubmission($submission);

        return $this->submissionInlineResponse($submission);
    }

    public function download(Submission $submission): StreamedResponse
    {
        $submission->load('user');
        $this->authorizeSubmission($submission);

        return $this->submissionDownloadResponse($submission);
    }

    private function authorizeSubmission(Submission $submission): void
    {
        $user = auth()->user();
        abort_unless(
            $user && 
            $user->role === UserRole::Perti && 
            $submission->user && 
            $submission->user->role === UserRole::UnitKerja && 
            $submission->user->perti_id === $user->id, 
            403
        );
    }
}
