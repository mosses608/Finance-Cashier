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
        if (!Schema::hasColumn('budgets', 'branch_name')) {
            Schema::table('budgets', function (Blueprint $table) {
                $table->string('branch_name')->default('all');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('budgets', 'branch_name')) {
            Schema::table('budgets', function (Blueprint $table) {
                $table->dropColumn('branch_name');
            });
        }
    }
};
