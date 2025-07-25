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
        if (!Schema::hasColumn('leave_types', 'company_id')) {
            Schema::table('leave_types', function (Blueprint $table) {
                $table->integer('company_id')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('leave_types', 'company_id')) {
            Schema::table('leave_types', function (Blueprint $table) {
                $table->dropColumn('company_id');
            });
        }
    }
};
