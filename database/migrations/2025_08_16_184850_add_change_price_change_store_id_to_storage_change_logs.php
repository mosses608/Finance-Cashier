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
        Schema::table('storage_change_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('storage_change_logs', 'change_price')) {
                $table->decimal('change_price', 15, 2);
            }

            if (!Schema::hasColumn('storage_change_logs', 'change_store_id')) {
                $table->integer('change_store_id');
            }

            if (!Schema::hasColumn('storage_change_logs', 'company_id')) {
                $table->integer('company_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_change_logs', function (Blueprint $table) {
            if (Schema::hasColumn('storage_change_logs', 'change_price')) {
                $table->dropColumn('change_price');
            }

            if (Schema::hasColumn('storage_change_logs', 'change_store_id')) {
                $table->dropColumn('change_store_id');
            }

            if (Schema::hasColumn('storage_change_logs', 'company_id')) {
                $table->dropColumn('company_id');
            }
        });
    }
};
