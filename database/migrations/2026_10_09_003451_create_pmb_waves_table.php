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
        Schema::create('pmb_waves', function (Blueprint $table) {
            $table->id();
            $table->string('pmb_period_id');
            $table->string('code',50);
            $table->string('name');
            $table->dateTime('registration_start');
            $table->dateTime('registration_end');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_show')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pmb_period_id')->references('id')->on('pmb_periods')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(
                ['pmb_period_id', 'code'],
                'pmb_waves_period_code_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_waves');
    }
};
