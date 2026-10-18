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
        Schema::create('user_study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('study_program_code', 50);
            $table->timestamps();

            $table->foreign('study_program_code')->references('code')->on('study_programs')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unique([
                'user_id',
                'study_program_code'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_study_programs');
    }
};
