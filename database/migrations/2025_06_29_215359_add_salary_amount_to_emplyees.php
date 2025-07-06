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
        if (!Schema::hasColumn('emplyees', 'salary_amount')) {
            Schema::table('emplyees', function (Blueprint $table) {
                $table->decimal('salary_amount', 15, 2)->default(0.00);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('emplyees', 'salary_amount')) {
            Schema::table('emplyees', function (Blueprint $table) {
                $table->dropColumn('emplyees', 'salary_amount');
            });
        }
    }
};
