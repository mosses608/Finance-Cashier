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
        if (!Schema::hasColumn('leave_applications', 'is_adjustment_approved')) {
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->integer('is_adjustment_approved')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('leave_applications', 'is_adjustment_approved')) {
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->dropColumn('is_adjustment_approved');
            });
        }
    }
};
