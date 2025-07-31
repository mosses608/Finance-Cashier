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
        if (!Schema::hasColumn('banks', 'company_id')) {
            Schema::table('banks', function (Blueprint $table) {
                $table->integer('company_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('banks', 'company_id')) {
            Schema::table('banks', function (Blueprint $table) {
                $table->dropColumn('company_id');
            });
        }
    }
};
