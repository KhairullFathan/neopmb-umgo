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
        Schema::create('pmb_wave_paths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pmb_wave_id')->constrained('pmb_waves')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('pmb_registration_path_id')->constrained('pmb_registration_paths')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('registration_fee', 15, 2)->default(0);
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['pmb_wave_id', 'pmb_registration_path_id'],
                'pmb_wave_paths_wave_path_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_wave_paths');
    }
};
