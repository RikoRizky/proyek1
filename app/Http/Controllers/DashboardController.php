<?php

namespace App\Http\Controllers;

use App\Enums\SubmissionStatus;
use App\Enums\UserRole;
use App\Models\Assessment;
use App\Models\Module;
use App\Models\Requirement;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $stats = match ($user->role) {
            UserRole::Admin => $this->adminStats(),
            UserRole::Asesor => $this->asesorStats(),
            UserRole::UnitKerja => $this->unitStats($user),
        };

        return view('dashboard', [
            'stats' => $stats,
        ]);
    }

    private function adminStats(): array
    {
        $modules = Module::query()
            ->withCount('requirements')
            ->orderBy('sort_order')
            ->get();

        return [
            'role' => UserRole::Admin,
            'modules' => $modules,
            'usersCount' => User::query()->count(),
            'unitCount' => User::query()->where('role', UserRole::UnitKerja)->count(),
            'asesorCount' => User::query()->where('role', UserRole::Asesor)->count(),
            'totalRequirements' => Requirement::query()->count(),
            'completedLatest' => Submission::query()
                ->latestForUnit()
                ->where('status', SubmissionStatus::Completed)
                ->count(),
            'assessmentsCount' => Assessment::query()->count(),
        ];
    }

    private function asesorStats(): array
    {
        $queue = Submission::query()
            ->latestForUnit()
            ->with(['requirement.module', 'user'])
            ->whereHas('user', fn ($q) => $q->where('role', UserRole::UnitKerja))
            ->whereIn('status', [SubmissionStatus::Uploaded, SubmissionStatus::UnderReview])
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        $pendingCount = Submission::query()
            ->latestForUnit()
            ->whereHas('user', fn ($q) => $q->where('role', UserRole::UnitKerja))
            ->whereIn('status', [SubmissionStatus::Uploaded, SubmissionStatus::UnderReview])
            ->count();

        $completedCount = Submission::query()
            ->latestForUnit()
            ->whereHas('user', fn ($q) => $q->where('role', UserRole::UnitKerja))
            ->where('status', SubmissionStatus::Completed)
            ->count();

        $totalTracked = Submission::query()
            ->latestForUnit()
            ->whereHas('user', fn ($q) => $q->where('role', UserRole::UnitKerja))
            ->count();

        return [
            'role' => UserRole::Asesor,
            'queue' => $queue,
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'totalTracked' => $totalTracked,
            'assessedCount' => Assessment::query()->where('asesor_id', auth()->id())->count(),
        ];
    }

    private function unitStats(User $user): array
    {
        $modules = Module::query()
            ->with(['requirements' => function ($q) use ($user) {
                $q->orderBy('sort_order')
                    ->with(['submissions' => function ($s) use ($user) {
                        $s->where('user_id', $user->id)->latestForUnit()->with('assessment');
                    }]);
            }])
            ->orderBy('sort_order')
            ->get();

        $totalReq = Requirement::query()->count();

        $latestSubmissions = Submission::query()
            ->where('user_id', $user->id)
            ->latestForUnit()
            ->get()
            ->keyBy('requirement_id');

        $completedCount = $latestSubmissions->filter(fn (Submission $s) => $s->status === SubmissionStatus::Completed)->count();

        $awaitingAssessment = $latestSubmissions->filter(
            fn (Submission $s) => in_array($s->status, [SubmissionStatus::Uploaded, SubmissionStatus::UnderReview], true)
        )->count();

        $notUploadedCount = max(0, $totalReq - $latestSubmissions->count());

        return [
            'role' => UserRole::UnitKerja,
            'modules' => $modules,
            'totalRequirements' => $totalReq,
            'completedCount' => $completedCount,
            'awaitingAssessment' => $awaitingAssessment,
            'notUploadedCount' => $notUploadedCount,
            'progressPercent' => $totalReq > 0 ? round(($completedCount / $totalReq) * 100) : 0,
        ];
    }
}
