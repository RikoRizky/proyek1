<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait SendsSubmissionFile
{
    protected function submissionDownloadResponse(Submission $submission): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        return Storage::disk('local')->download($submission->file_path, $submission->original_filename);
    }

    protected function submissionInlineResponse(Submission $submission): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        return Storage::disk('local')->response(
            $submission->file_path,
            $submission->original_filename,
            ['Content-Disposition' => 'inline; filename="'.$this->asciiFilename($submission->original_filename).'"']
        );
    }

    private function asciiFilename(string $name): string
    {
        return preg_replace('/[^\x20-\x7E]/', '_', $name) ?: 'document';
    }
}
