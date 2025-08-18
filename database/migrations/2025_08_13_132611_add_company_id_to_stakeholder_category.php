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
        if (!Schema::hasColumn('stakeholder_category', 'company_id')) {
            Schema::table('stakeholder_category', function (Blueprint $table) {
                $table->integer('company_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('stakeholder_category', 'company_id')) {
            Schema::table('stakeholder_category', function (Blueprint $table) {
                $table->dropColumn('company_id');
            });
        }
    }
};
