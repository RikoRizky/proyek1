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

class ReportController extends Controller
{
    public function pdf(Request $request)
    {
        $filters = $this->resolveTargetFilters($request);

        if (isset($filters['selectionRequired']) && $filters['selectionRequired']) {
            $pertis = User::query()
                ->where('role', UserRole::Perti)
                ->orderBy('name')
                ->get();
            return view('admin.reports.pdf-select', compact('pertis'));
        }

        $summaries = $this->summariesForExport($filters['onlyUnit'], $filters['pertiId']);

        $filename = 'laporan-ringkasan-akreditasi';
        if (!empty($filters['pertiName'])) {
            $filename .= '-' . \Illuminate\Support\Str::slug($filters['pertiName']);
        }
        $filename .= '.pdf';

        return Pdf::loadView('reports.accreditation-summary', [
            'summaries' => $summaries,
            'generatedAt' => now(),
            'pertiName' => $filters['pertiName'] ?? null,
        ])->setPaper('a4', 'portrait')->download($filename);
    }

    private function resolveTargetFilters(Request $request): array
    {
        $user = $request->user();

        if ($user->role === UserRole::Admin) {
            if ($request->filled('user_id')) {
                $unit = User::query()
                    ->where('id', $request->integer('user_id'))
                    ->where('role', UserRole::UnitKerja)
                    ->firstOrFail();
                return ['onlyUnit' => $unit, 'pertiId' => null, 'pertiName' => null];
            }

            if ($request->filled('perti_id')) {
                $perti = User::query()
                    ->where('id', $request->integer('perti_id'))
                    ->where('role', UserRole::Perti)
                    ->firstOrFail();
                return ['onlyUnit' => null, 'pertiId' => $perti->id, 'pertiName' => $perti->name];
            }

            return ['selectionRequired' => true];
        }

        if ($user->role === UserRole::Perti) {
            return ['onlyUnit' => null, 'pertiId' => $user->id, 'pertiName' => $user->name];
        }

        abort_unless($user->role === UserRole::UnitKerja, 403);

        return ['onlyUnit' => $user, 'pertiId' => null, 'pertiName' => null];
    }

    /**
     * @return list<array{user: User, modules: Collection, uploadedCount: int, totalRequirements: int, progressPercent: int}>
     */
    private function summariesForExport(?User $onlyUnit, ?int $pertiId = null): array
    {
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->when($onlyUnit, fn ($q) => $q->where('id', $onlyUnit->id))
            ->when($pertiId, fn ($q) => $q->where('perti_id', $pertiId))
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
}

