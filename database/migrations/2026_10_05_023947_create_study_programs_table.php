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
        Schema::create('study_programs', function (Blueprint $table) {
            $table->string('code', 50)->primary(true);
            $table->string('faculty_code', 50);
            $table->string('name', 255);
            $table->enum('degree_level', ['DI','DII','DIII','DIV','S1', 'S2', 'S3', 'PROFESI'])->nullable();
            $table->string('accreditation')->nullable();
            $table->string('degree_title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('faculty_code')->references('code')->on('faculties')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};
