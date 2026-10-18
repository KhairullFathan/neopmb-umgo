<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pmb_wave_path_study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pmb_wave_path_id')->constrained('pmb_wave_paths')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('study_program_code', 50);
            $table->timestamps();

            $table->foreign('study_program_code')->references('code')->on('study_programs')->cascadeOnUpdate()->restrictOnDelete();

            $table->unique(
                ['pmb_wave_path_id', 'study_program_code'],
                'pmb_wave_path_study_programs_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_wave_path_study_programs');
    }
};
