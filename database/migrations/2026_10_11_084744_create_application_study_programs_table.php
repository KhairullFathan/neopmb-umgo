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
        Schema::create('application_study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('study_program_code', 50);
            $table->unsignedTinyInteger('choice_number');
            $table->timestamps();

            $table->foreign('study_program_code')->references('code')->on('study_programs')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(
                ['application_id', 'choice_number'],
                'application_study_programs_choice_unique'
            );

            $table->unique(
                ['application_id', 'study_program_code'],
                'application_study_programs_program_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_study_programs');
    }
};
