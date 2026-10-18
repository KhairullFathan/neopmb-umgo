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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('pmb_period_id');
            $table->foreignId('pmb_wave_id')->constrained('pmb_waves')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('pmb_registration_path_id')->constrained('pmb_registration_paths')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('registration_number', 50)->unique();
            $table->timestamps();
            $table->foreign('pmb_period_id')->references('id')->on('pmb_periods')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(
                [
                    'applicant_id',
                    'pmb_period_id',
                    'pmb_wave_id',
                    'pmb_registration_path_id',
                ],
                'applications_applicant_period_wave_path_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
