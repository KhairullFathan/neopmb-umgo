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
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('pmb_registration_document_id')->constrained('pmb_registration_documents')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->enum('verification_status', ['pending','approved','rejected',])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamps();
            $table->unique(
                ['application_id', 'pmb_registration_document_id'],
                'application_documents_requirement_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
