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
        Schema::create('pmb_wave_path_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pmb_wave_path_id')
                ->constrained('pmb_wave_paths')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('pmb_registration_stage_id')
                ->constrained('pmb_registration_stages')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedInteger('sequence');
            $table->boolean('is_required')->default(true);
            $table->enum('execution_type', [
                'flexible',
                'scheduled',
            ])->default('flexible');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['pmb_wave_path_id', 'sequence'],
                'pmb_wave_path_stages_sequence_unique'
            );
            $table->unique(
                ['pmb_wave_path_id', 'pmb_registration_stage_id'],
                'pmb_wave_path_stages_stage_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_wave_path_stages');
    }
};
