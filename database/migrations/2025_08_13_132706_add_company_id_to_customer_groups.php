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
        if (!Schema::hasColumn('customer_groups', 'company_id')) {
            Schema::table('customer_groups', function (Blueprint $table) {
                $table->integer('company_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('customer_groups', 'company_id')) {
            Schema::table('customer_groups', function (Blueprint $table) {
                $table->dropColumn('company_id');
            });
        }
    }
};
