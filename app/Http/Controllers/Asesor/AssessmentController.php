<?php

namespace App\Http\Controllers\Asesor;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Concerns\SendsSubmissionFile;
use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssessmentController extends Controller
{
    use SendsSubmissionFile;

    /**
     * Halaman penilaian (skor & komentar) untuk asesor.
     */
    public function show(Submission $submission): View
    {
        $this->authorizeAsesorSubmission($submission);

        $submission->load(['requirement.module', 'user']);

        // Ambil assessment jika sudah ada.
        $assessment = $submission->assessment;


        return view('asesor.assessments.show', [
            'submission' => $submission,
            'assessment' => $assessment,
        ]);

    }

    /**
     * Simpan hasil penilaian (skor & komentar).
     */
    public function store(Request $request, Submission $submission)
    {

        $this->authorizeAsesorSubmission($submission);

        $user = auth()->user();

        $validated = $request->validate([

            'score' => ['required', 'numeric', 'min:0'],
            'comments' => ['nullable', 'string', 'max:5000'],
        ]);

        $submission->load('assessment');

        // Pastikan key tersedia untuk update assessment.
        $submission->assessment()->updateOrCreate(
            ['submission_id' => $submission->getKey()],
            [
                'asesor_id' => $user->getKey(),
                'score' => $validated['score'],
                'comments' => $validated['comments'] ?? null,
            ]
        );


        // Tandai submission selesai dinilai.
        $submission->update([
            'status' => SubmissionStatus::Completed,
        ]);

        return redirect()->route('asesor.queue.index')->with('status', 'Penilaian berhasil disimpan.');
    }


    public function viewer(Submission $submission): View
    {

        $this->authorizeAsesorSubmission($submission);

        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        $submission->load('requirement.module', 'user');

        return view('submissions.viewer', [
            'title' => $submission->requirement->title.' — '.$submission->user->name,
            'filename' => $submission->original_filename,
            'inlineUrl' => route('asesor.submissions.inline', $submission),
            'downloadUrl' => route('asesor.submissions.download', $submission),
            'backUrl' => route('asesor.queue.index'),
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
        abort_unless($submission->requirement !== null, 403);



        abort_unless($submission->is_latest, 403);
    }
}

