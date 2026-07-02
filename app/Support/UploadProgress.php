<?php

namespace App\Support;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Models\Module;
use App\Models\Requirement;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Collection;

class UploadProgress
{
    public static function totalRequirements(): int
    {
        return Requirement::query()->count();
    }

    public static function modulesWithRequirements(): Collection
    {
        return Module::query()
            ->with(['requirements:id,module_id'])
            ->orderBy('sort_order')
            ->get(['id', 'name', 'sort_order']);
    }

    /**
     * @return array{uploaded: int, total: int, percent: int, modules: list<array<string, mixed>>}
     */
    public static function forUnit(User $unit): array
    {
        $totalReq = self::totalRequirements();
        $modules = self::modulesWithRequirements();

        $uploadedByRequirement = Submission::query()
            ->where('user_id', $unit->id)
            ->latestForUnit()
            ->where('status', SubmissionStatus::Uploaded)
            ->pluck('requirement_id')
            ->flip();

        $moduleRows = $modules->map(function (Module $module) use ($uploadedByRequirement) {
            $total = $module->requirements->count();
            $uploaded = $module->requirements
                ->filter(fn ($requirement) => isset($uploadedByRequirement[$requirement->id]))
                ->count();
            $percent = $total > 0 ? (int) round(($uploaded / $total) * 100) : 0;

            return [
                'module_id' => $module->id,
                'name' => $module->name,
                'short_label' => $module->shortLabel(),
                'uploaded' => $uploaded,
                'total' => $total,
                'percent' => $percent,
            ];
        })->values()->all();

        $uploadedTotal = (int) collect($moduleRows)->sum('uploaded');

        return [
            'uploaded' => $uploadedTotal,
            'total' => $totalReq,
            'percent' => $totalReq > 0 ? (int) round(($uploadedTotal / $totalReq) * 100) : 0,
            'modules' => $moduleRows,
        ];
    }

    /**
     * @return array{
     *     total_requirements: int,
     *     units: list<array<string, mixed>>,
     *     summary: array{unit_count: int, complete_count: int, in_progress_count: int, empty_count: int, average_percent: float}
     * }
     */
    public static function forAllUnits(): array
    {
        $totalReq = self::totalRequirements();
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->orderBy('name')
            ->get(['id', 'name']);

        $rows = $units->map(function (User $unit) use ($totalReq) {
            $progress = self::forUnit($unit);

            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'uploaded' => $progress['uploaded'],
                'total' => $totalReq,
                'percent' => $progress['percent'],
                'modules' => $progress['modules'],
            ];
        })->values()->all();

        $collection = collect($rows);

        return [
            'total_requirements' => $totalReq,
            'units' => $rows,
            'summary' => [
                'unit_count' => $collection->count(),
                'complete_count' => $collection->where('percent', 100)->count(),
                'in_progress_count' => $collection->where(fn (array $row) => $row['percent'] > 0 && $row['percent'] < 100)->count(),
                'empty_count' => $collection->where('percent', 0)->count(),
                'average_percent' => $collection->isEmpty() ? 0.0 : round($collection->avg('percent'), 1),
            ],
        ];
    }

    /**
     * @return array{
     *     total_requirements: int,
     *     units: list<array<string, mixed>>,
     *     summary: array{unit_count: int, complete_count: int, in_progress_count: int, empty_count: int, average_percent: float}
     * }
     */
    public static function forAllUnitsOfPerti(User $perti): array
    {
        $totalReq = self::totalRequirements();
        $units = User::query()
            ->where('role', UserRole::UnitKerja)
            ->where('perti_id', $perti->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        $rows = $units->map(function (User $unit) use ($totalReq) {
            $progress = self::forUnit($unit);

            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'uploaded' => $progress['uploaded'],
                'total' => $totalReq,
                'percent' => $progress['percent'],
                'modules' => $progress['modules'],
            ];
        })->values()->all();

        $collection = collect($rows);

        return [
            'total_requirements' => $totalReq,
            'units' => $rows,
            'summary' => [
                'unit_count' => $collection->count(),
                'complete_count' => $collection->where('percent', 100)->count(),
                'in_progress_count' => $collection->where(fn (array $row) => $row['percent'] > 0 && $row['percent'] < 100)->count(),
                'empty_count' => $collection->where('percent', 0)->count(),
                'average_percent' => $collection->isEmpty() ? 0.0 : round($collection->avg('percent'), 1),
            ],
        ];
    }
}
