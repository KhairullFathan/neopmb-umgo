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
        Schema::create('application_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnUpdate()->cascadeOnDelete();

            // formulir atau registrasi_ulang
            $table->enum('payment_type', ['formulir','registrasi_ulang']);

            // Tagihan dari sistem
            $table->decimal('amount', 12, 2)->default(0);

            // Informasi pembayaran
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('sender_account_name')->nullable();
            $table->string('sender_bank_name')->nullable();
            $table->string('proof_path')->nullable();

            $table->decimal('paid_amount', 12, 2)->nullable();

            $table->timestamp('paid_at')->nullable();

            // Status pembayaran dan verifikasi
            $table->enum('verification_status', ['pending','approved','rejected','not_required',])->default('pending');

            $table->foreignId('verified_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            $table->timestamps();

            $table->index(
                ['payment_type', 'verification_status'],
                'app_payments_type_status_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_payments');
    }
};
