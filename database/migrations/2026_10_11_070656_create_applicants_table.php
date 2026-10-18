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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('name', 50)->nullable();
            $table->string('identity_number', 20)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('birth_place', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('school_origin', 150)->nullable();
            $table->string('school_type', 20)->nullable();
            $table->string('school_major', 150)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('mother_name', 50)->nullable();
            $table->string('parent_phone', 15)->nullable();
            $table->string('nationality', 15)->nullable();
            $table->string('country_of_origin', 50)->nullable();
            $table->string('code_village', 20)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('code_village')->references('code')->on('villages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
