<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Submission;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Exports\AccreditationReportExport;

class ReportController extends Controller
{
    public function pdf(Request $request)
    {
        $unit = $this->resolveTargetUnit($request);
        $summaries = $this->summariesForExport($unit);

        return Pdf::loadView('reports.accreditation-summary', [
            'summaries' => $summaries,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait')->download('laporan-ringkasan-akreditasi.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $unit = $this->resolveTargetUnit($request);
        $rows = $this->detailedRows($unit);

        return Excel::download(
            new AccreditationReportExport($rows),
            'laporan-detail-akreditasi.xlsx'
        );
    }

    private function resolveTargetUnit(Request $request): ?User
    {
        $user = $request->user();

        if ($user->role === UserRole::Admin) {
            if ($request->filled('user_id')) {
                return User::query()
                    ->where('id', $request->integer('user_id'))
                    ->where('role', UserRole::UnitKerja)
                    ->firstOrFail();
            }

            return null;
        }

        abort_unless($user->role === UserRole::UnitKerja, 403);

        return $user;
    }

    /**
     * @return list<array{user: User, modules: Collection, uploadedCount: int, totalRequirements: int, progressPercent: int}>
     */
    private function summariesForExport(?User $onlyUnit): array
    {
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->when($onlyUnit, fn ($q) => $q->where('id', $onlyUnit->id))
            ->orderBy('name')
            ->get();

        $modules = Module::query()->with('requirements')->orderBy('sort_order')->get();
        $totalRequirements = $modules->sum(fn (Module $m) => $m->requirements->count());

        return $units->map(function (User $unit) use ($modules, $totalRequirements) {
            $moduleRows = $modules->map(function (Module $module) use ($unit) {
                $uploaded = 0;
                $total = $module->requirements->count();

                foreach ($module->requirements as $requirement) {
                    $submission = Submission::query()
                        ->where('requirement_id', $requirement->id)
                        ->where('user_id', $unit->id)
                        ->latestForUnit()
                        ->first();

                    if ($submission?->status === SubmissionStatus::Uploaded) {
                        $uploaded++;
                    }
                }

                return [
                    'module' => $module,
                    'uploaded' => $uploaded,
                    'total' => $total,
                    'progressPercent' => $total > 0 ? round(($uploaded / $total) * 100) : 0,
                ];
            });

            $uploadedCount = (int) $moduleRows->sum('uploaded');

            return [
                'user' => $unit,
                'modules' => $moduleRows,
                'uploadedCount' => $uploadedCount,
                'totalRequirements' => $totalRequirements,
                'progressPercent' => $totalRequirements > 0 ? round(($uploadedCount / $totalRequirements) * 100) : 0,
            ];
        })->all();
    }

    private function detailedRows(?User $onlyUnit): Collection
    {
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->when($onlyUnit, fn ($q) => $q->where('id', $onlyUnit->id))
            ->orderBy('name')
            ->get();

        $modules = Module::query()->with(['requirements' => fn ($q) => $q->orderBy('sort_order')])->orderBy('sort_order')->get();

        $rows = collect();

        foreach ($units as $unit) {
            foreach ($modules as $module) {
                foreach ($module->requirements as $requirement) {
                    $submission = Submission::query()
                        ->where('requirement_id', $requirement->id)
                        ->where('user_id', $unit->id)
                        ->latestForUnit()
                        ->first();

                    $rows->push([
                        $unit->name,
                        $unit->email,
                        $module->name,
                        $requirement->title,
                        $submission?->status?->label() ?? 'Menunggu unggah',
                        $submission?->version,
                        $submission?->original_filename,
                        $submission?->updated_at?->translatedFormat('d M Y H:i'),
                    ]);
                }
            }
        }

        return $rows;
    }
}
