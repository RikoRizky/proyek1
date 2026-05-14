<?php

use App\Http\Controllers\Admin\AssessmentOverviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RequirementController;
use App\Http\Controllers\Admin\SubmissionOverviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Asesor\AllDocumentsController;
use App\Http\Controllers\Asesor\AssessmentController;
use App\Http\Controllers\Asesor\SubmissionQueueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UnitKerja\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('home');
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('submissions', [SubmissionOverviewController::class, 'index'])->name('submissions.index');
    Route::get('submissions/{submission}/view', [SubmissionOverviewController::class, 'viewer'])->name('submissions.view');
    Route::get('submissions/{submission}/inline', [SubmissionOverviewController::class, 'inline'])->name('submissions.inline');
    Route::get('submissions/{submission}/download', [SubmissionOverviewController::class, 'download'])->name('submissions.download');
    Route::get('assessments', [AssessmentOverviewController::class, 'index'])->name('assessments.index');
    Route::resource('modules', ModuleController::class);
    Route::resource('modules.requirements', RequirementController::class);
    Route::get('reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('reports/excel', [ReportController::class, 'excel'])->name('reports.excel');
});

Route::middleware(['auth', 'role:unit_kerja'])->prefix('unit')->name('unit.')->group(function () {
    Route::get('submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::post('modules/{module}/submissions/batch', [SubmissionController::class, 'batchStore'])->name('modules.submissions.batch');
    Route::post('requirements/{requirement}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('submissions/{submission}/view', [SubmissionController::class, 'viewer'])->name('submissions.view');
    Route::get('submissions/{submission}/inline', [SubmissionController::class, 'inline'])->name('submissions.inline');
    Route::get('submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('reports/excel', [ReportController::class, 'excel'])->name('reports.excel');
});

Route::middleware(['auth', 'role:asesor'])->prefix('asesor')->name('asesor.')->group(function () {
    Route::get('queue', [SubmissionQueueController::class, 'index'])->name('queue.index');
    Route::get('completed', [SubmissionQueueController::class, 'completed'])->name('completed.index');
    Route::get('documents', [AllDocumentsController::class, 'index'])->name('documents.index');
    Route::get('submissions/{submission}/view', [AssessmentController::class, 'viewer'])->name('submissions.view');
    Route::get('submissions/{submission}/inline', [AssessmentController::class, 'inline'])->name('submissions.inline');
    Route::post('submissions/{submission}/assessments', [AssessmentController::class, 'store'])->name('submissions.assessments.store');
    Route::get('submissions/{submission}/download', [AssessmentController::class, 'download'])->name('submissions.download');
    Route::get('submissions/{submission}', [AssessmentController::class, 'show'])->name('submissions.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
