<?php

namespace App\Http\Controllers\UnitKerja;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Concerns\SendsSubmissionFile;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Requirement;
use App\Models\Submission;
use App\Models\User;
use App\Support\AccreditationUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    use SendsSubmissionFile;

    public function index(): View
    {
        $modules = Module::query()
            ->with(['requirements' => function ($q) {
                $q->orderBy('sort_order')
                    ->with(['submissions' => function ($s) {
                        $s->where('user_id', auth()->id())
                            ->latestForUnit();
                    }]);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('unit.submissions.index', [
            'modules' => $modules,
        ]);
    }

    public function batchStore(Request $request, Module $module): RedirectResponse
    {
        $this->authorizeUnitKerja();

        $module->load(['requirements' => fn ($q) => $q->orderBy('sort_order')]);
        $requirements = $module->requirements->keyBy('id');

        if ($requirements->isEmpty()) {
            return redirect()->route('unit.submissions.index')->with('status', 'Modul ini belum memiliki persyaratan.');
        }

        $files = $request->file('files') ?? [];
        $errors = [];
        $saved = 0;

        DB::transaction(function () use ($files, $requirements, $module, $request, &$errors, &$saved) {
            $user = $request->user();

            foreach ($files as $requirementId => $file) {
                if (! $file instanceof UploadedFile) {
                    continue;
                }

                $requirementId = (int) $requirementId;
                $requirement = $requirements->get($requirementId);

                if (! $requirement) {
                    continue;
                }

                $field = 'files.'.$requirementId;

                if (! $file->isValid()) {
                    $errors[$field] = AccreditationUpload::fieldErrorMessage(
                        $module,
                        $requirement,
                        $file,
                        'Berkas tidak valid atau gagal diunggah.'
                    );

                    continue;
                }

                $validationMessage = AccreditationUpload::validateFile($file);

                if ($validationMessage !== null) {
                    $errors[$field] = AccreditationUpload::fieldErrorMessage(
                        $module,
                        $requirement,
                        $file,
                        $validationMessage
                    );

                    continue;
                }

                $this->persistSubmission($user, $requirement, $file);
                $saved++;
            }
        });

        if ($saved === 0 && $errors === []) {
            return redirect()->route('unit.submissions.index')
                ->withErrors(['files' => 'Pilih minimal satu berkas untuk diunggah pada modul ini.']);
        }

        $redirect = redirect()->route('unit.submissions.index');

        if ($saved > 0) {
            $redirect->with('status', $saved.' berkas modul «'.$module->name.'» berhasil disimpan.');
        }

        if ($errors !== []) {
            $redirect
                ->withErrors($errors)
                ->with('upload_partial_failure', true);
        }

        return $redirect;
    }

    public function store(Request $request, Requirement $requirement): RedirectResponse
    {
        $this->authorizeUnitKerja();

        $file = $request->file('document');

        if (! $file instanceof UploadedFile) {
            return redirect()->route('unit.submissions.index')
                ->withErrors(['document' => 'Pilih berkas untuk diunggah.']);
        }

        $requirement->load('module');
        $validationMessage = AccreditationUpload::validateFile($file);

        if ($validationMessage !== null) {
            return redirect()->route('unit.submissions.index')
                ->withErrors([
                    'document' => AccreditationUpload::fieldErrorMessage(
                        $requirement->module,
                        $requirement,
                        $file,
                        $validationMessage
                    ),
                ]);
        }

        $user = $request->user();
        $submission = $this->persistSubmission($user, $requirement, $file);

        return redirect()->route('unit.submissions.index')
            ->with('status', 'Dokumen berhasil diunggah (versi '.$submission->version.').');
    }

    public function show(Submission $submission): View
    {
        $this->authorizeSubmission($submission);

        $history = Submission::query()
            ->where('requirement_id', $submission->requirement_id)
            ->where('user_id', $submission->user_id)
            ->orderByDesc('version')
            ->get();

        $submission->load(['requirement.module']);

        return view('unit.submissions.show', compact('submission', 'history'));
    }

    public function viewer(Submission $submission): View
    {
        $this->authorizeSubmission($submission);

        abort_unless(Storage::disk('local')->exists($submission->file_path), 404);

        $submission->load('requirement.module');

        return view('submissions.viewer', [
            'title' => $submission->requirement->title,
            'filename' => $submission->original_filename,
            'inlineUrl' => route('unit.submissions.inline', $submission),
            'downloadUrl' => route('unit.submissions.download', $submission),
            'backUrl' => route('unit.submissions.show', $submission),
        ]);
    }

    public function inline(Submission $submission): StreamedResponse
    {
        $this->authorizeSubmission($submission);

        return $this->submissionInlineResponse($submission);
    }

    public function download(Submission $submission): StreamedResponse
    {
        $this->authorizeSubmission($submission);

        return $this->submissionDownloadResponse($submission);
    }

    private function authorizeUnitKerja(): void
    {
        abort_unless(auth()->user()?->role === UserRole::UnitKerja, 403);
    }

    private function authorizeSubmission(Submission $submission): void
    {
        $user = auth()->user();
        abort_unless($user && $submission->user_id === $user->id, 403);
    }

    private function persistSubmission(User $user, Requirement $requirement, UploadedFile $file): Submission
    {
        $latest = Submission::query()
            ->where('requirement_id', $requirement->id)
            ->where('user_id', $user->id)
            ->orderByDesc('version')
            ->first();

        $nextVersion = $latest ? $latest->version + 1 : 1;

        if ($latest) {
            $latest->update(['is_latest' => false]);
        }

        $path = $file->store("accreditation/{$user->id}/{$requirement->id}", 'local');

        return Submission::query()->create([
            'requirement_id' => $requirement->id,
            'user_id' => $user->id,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'status' => SubmissionStatus::Uploaded,
            'version' => $nextVersion,
            'is_latest' => true,
        ]);
    }
}
