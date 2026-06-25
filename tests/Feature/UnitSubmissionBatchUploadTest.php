<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Module;
use App\Models\Requirement;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UnitSubmissionBatchUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_batch_upload_saves_valid_files_and_reports_oversized_ones(): void
    {
        Storage::fake('local');

        $user = User::factory()->create(['role' => UserRole::UnitKerja]);
        $module = Module::query()->create([
            'name' => 'Kriteria Demo',
            'weight' => 25,
            'sort_order' => 1,
        ]);
        $validRequirement = Requirement::query()->create([
            'module_id' => $module->id,
            'title' => 'Dokumen Valid',
            'sort_order' => 1,
        ]);
        $oversizedRequirement = Requirement::query()->create([
            'module_id' => $module->id,
            'title' => 'Dokumen Besar',
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('unit.modules.submissions.batch', $module), [
            'files' => [
                $validRequirement->id => UploadedFile::fake()->create('valid.pdf', 512, 'application/pdf'),
                $oversizedRequirement->id => UploadedFile::fake()->create('besar.pdf', 25000, 'application/pdf'),
            ],
        ]);

        $response
            ->assertRedirect(route('unit.submissions.index'))
            ->assertSessionHas('status')
            ->assertSessionHas('upload_partial_failure')
            ->assertSessionHasErrors('files.'.$oversizedRequirement->id);

        $this->assertSame(1, Submission::query()->count());
        $this->assertDatabaseHas('submissions', [
            'requirement_id' => $validRequirement->id,
            'user_id' => $user->id,
            'original_filename' => 'valid.pdf',
        ]);
    }
}
