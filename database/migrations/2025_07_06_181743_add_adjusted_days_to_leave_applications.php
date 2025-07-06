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
        if (!Schema::hasColumn('leave_applications', 'adjusted_days')) {
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->integer('adjusted_days')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('leave_applications', 'adjusted_days')) {
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->dropColumn('adjusted_days');
            });
        }
    }
};
