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
        if (!Schema::hasColumn('budgets', 'approved_by')) {
            Schema::table('budgets', function (Blueprint $table) {
                //
                $table->integer('approved_by')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('budgets', 'approved_by')) {
            Schema::table('budgets', function (Blueprint $table) {
                $table->dropColumn('approved_by');
            });
        }
    }
};
