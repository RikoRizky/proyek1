<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Exports\AccreditationReportExport;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Submission;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * @return list<array{user: User, modules: Collection, weightedTotal: float}>
     */
    private function summariesForExport(?User $onlyUnit): array
    {
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->when($onlyUnit, fn ($q) => $q->where('id', $onlyUnit->id))
            ->orderBy('name')
            ->get();

        $modules = Module::query()->with('requirements')->orderBy('sort_order')->get();

        return $units->map(function (User $unit) use ($modules) {
            $moduleRows = $modules->map(function (Module $module) use ($unit) {
                $scores = [];
                foreach ($module->requirements as $requirement) {
                    $submission = Submission::query()
                        ->where('requirement_id', $requirement->id)
                        ->where('user_id', $unit->id)
                        ->latestForUnit()
                        ->with('assessment')
                        ->first();

                    if ($submission?->assessment) {
                        $scores[] = (int) $submission->assessment->score;
                    }
                }

                $avg = count($scores) ? round(array_sum($scores) / count($scores), 2) : null;
                $contribution = $avg !== null ? round($avg * ((float) $module->weight / 100), 2) : null;

                return [
                    'module' => $module,
                    'average' => $avg,
                    'weight' => (float) $module->weight,
                    'contribution' => $contribution,
                ];
            });

            $weightedTotal = round(
                (float) $moduleRows->sum(fn (array $r) => $r['contribution'] ?? 0),
                2
            );

            return [
                'user' => $unit,
                'modules' => $moduleRows,
                'weightedTotal' => $weightedTotal,
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
                        ->with('assessment.asesor')
                        ->first();

                    $rows->push([
                        $unit->name,
                        $unit->email,
                        $module->name,
                        $requirement->title,
                        $submission?->status?->label() ?? 'Pending',
                        $submission?->version,
                        $submission?->assessment?->score,
                        $submission?->assessment?->asesor?->name,
                    ]);
                }
            }
        }

        return $rows;
    }
}
