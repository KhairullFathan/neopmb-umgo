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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('status')
                ->default('in_progress')
                ->after('registration_number');

            $table->string('current_stage')
                ->nullable()
                ->after('status');

            $table->index('status');
            $table->index('current_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['current_stage']);

            $table->dropColumn([
                'status',
                'current_stage',
            ]);
        });
    }
};
