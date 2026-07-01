<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RequirementController;
use App\Http\Controllers\Admin\SubmissionOverviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UnitKerja\ProgressController as UnitProgressController;
use App\Http\Controllers\UnitKerja\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('home');
    Route::get('analytics', AnalyticsController::class)->name('analytics');

    Route::resource('users', UserController::class)->except(['show']);
    Route::get('submissions', [SubmissionOverviewController::class, 'index'])->name('submissions.index');
    Route::get('submissions/{submission}/view', [SubmissionOverviewController::class, 'viewer'])->name('submissions.view');
    Route::get('submissions/{submission}/inline', [SubmissionOverviewController::class, 'inline'])->name('submissions.inline');
    Route::get('submissions/{submission}/download', [SubmissionOverviewController::class, 'download'])->name('submissions.download');

    Route::resource('modules', ModuleController::class);
    Route::resource('modules.requirements', RequirementController::class);
    Route::get('reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
});

Route::middleware(['auth', 'role:perti'])->prefix('perti')->name('perti.')->group(function () {
    Route::resource('prodis', App\Http\Controllers\Perti\ProdiController::class)->except(['show']);
    Route::get('prodis/{prodi}/progress', [App\Http\Controllers\Perti\ProdiProgressController::class, 'index'])->name('prodis.progress');
    Route::get('prodis/{prodi}/modul/{module}', [App\Http\Controllers\Perti\ProdiProgressController::class, 'module'])->name('prodis.modul');
    Route::get('reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');

    Route::get('submissions/{submission}/view', [App\Http\Controllers\Perti\SubmissionController::class, 'viewer'])->name('submissions.view');
    Route::get('submissions/{submission}/inline', [App\Http\Controllers\Perti\SubmissionController::class, 'inline'])->name('submissions.inline');
    Route::get('submissions/{submission}/download', [App\Http\Controllers\Perti\SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('submissions/{submission}', [App\Http\Controllers\Perti\SubmissionController::class, 'show'])->name('submissions.show');
});

Route::middleware(['auth', 'role:unit_kerja'])->prefix('unit')->name('unit.')->group(function () {
    Route::get('progress', UnitProgressController::class)->name('progress');
    Route::get('submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('submissions/modul/{module}', [SubmissionController::class, 'module'])->name('submissions.module');
    Route::post('modules/{module}/submissions/batch', [SubmissionController::class, 'batchStore'])->name('modules.submissions.batch');
    Route::post('requirements/{requirement}/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('submissions/{submission}/view', [SubmissionController::class, 'viewer'])->name('submissions.view');
    Route::get('submissions/{submission}/inline', [SubmissionController::class, 'inline'])->name('submissions.inline');
    Route::get('submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
