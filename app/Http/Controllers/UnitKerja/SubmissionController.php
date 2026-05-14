<?php

namespace App\Http\Controllers\UnitKerja;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Concerns\SendsSubmissionFile;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Requirement;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
                            ->latestForUnit()
                            ->with('assessment');
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

        $requirementIds = $module->requirements()->pluck('id')->all();
        if ($requirementIds === []) {
            return redirect()->route('unit.submissions.index')->with('status', 'Modul ini belum memiliki persyaratan.');
        }

        $rules = [];
        foreach ($requirementIds as $id) {
            $rules['files.'.$id] = ['nullable', 'file', 'mimes:pdf,xlsx,xls', 'max:20480'];
        }

        $validated = $request->validate($rules);

        $files = $validated['files'] ?? [];
        $saved = 0;

        DB::transaction(function () use ($files, $request, $module, &$saved) {
            $user = $request->user();
            foreach ($files as $requirementId => $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                $requirementId = (int) $requirementId;
                $requirement = Requirement::query()
                    ->where('id', $requirementId)
                    ->where('module_id', $module->id)
                    ->first();
                if (! $requirement) {
                    continue;
                }

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

                Submission::query()->create([
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

                $saved++;
            }
        });

        if ($saved === 0) {
            return redirect()->route('unit.submissions.index')->withErrors(['files' => 'Pilih minimal satu berkas untuk diunggah pada modul ini.'])->withInput();
        }

        return redirect()->route('unit.submissions.index')->with('status', $saved.' berkas modul «'.$module->name.'» berhasil disimpan sekaligus.');
    }

    public function store(Request $request, Requirement $requirement): RedirectResponse
    {
        $this->authorizeUnitKerja();

        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,xlsx,xls', 'max:20480'],
        ]);

        $user = $request->user();

        $latest = Submission::query()
            ->where('requirement_id', $requirement->id)
            ->where('user_id', $user->id)
            ->orderByDesc('version')
            ->first();

        $nextVersion = $latest ? $latest->version + 1 : 1;

        if ($latest) {
            $latest->update(['is_latest' => false]);
        }

        $file = $request->file('document');
        $path = $file->store("accreditation/{$user->id}/{$requirement->id}", 'local');

        Submission::query()->create([
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

        return redirect()->route('unit.submissions.index')->with('status', 'Dokumen berhasil diunggah (versi '.$nextVersion.').');
    }

    public function show(Submission $submission): View
    {
        $this->authorizeSubmission($submission);

        $history = Submission::query()
            ->where('requirement_id', $submission->requirement_id)
            ->where('user_id', $submission->user_id)
            ->orderByDesc('version')
            ->with('assessment.asesor')
            ->get();

        $submission->load(['requirement.module', 'assessment.asesor']);

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
}
