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
        if (!Schema::hasColumn('auth_user_modules', 'is_admin')) {
            Schema::table('auth_user_modules', function (Blueprint $table) {
                $table->integer('is_admin')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('auth_user_modules', 'is_admin')) {
        Schema::table('auth_user_modules', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
    }
};
