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
        if (!Schema::hasColumn('products', 'serial_no')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('serial_no')->unique()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('products', 'serial_no')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('serial_no');
            });
        }
    }
};
