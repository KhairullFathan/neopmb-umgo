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
        Schema::create('pmb_registration_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pmb_registration_path_id')
                ->constrained('pmb_registration_paths')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->boolean('is_required')->default(true);
            $table->json('allowed_mimes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_registration_documents');
    }
};
