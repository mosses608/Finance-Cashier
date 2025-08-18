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
        if (!Schema::hasColumn('storage_change_logs', 'comments')) {
            Schema::table('storage_change_logs', function (Blueprint $table) {
                $table->text('comments')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('storage_change_logs', 'comments')) {
            Schema::table('storage_change_logs', function (Blueprint $table) {
                $table->dropColumn('comments');
            });
        }
    }
};
