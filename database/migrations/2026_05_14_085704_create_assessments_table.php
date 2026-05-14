<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asesor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->unique('submission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
