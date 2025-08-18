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
        if (!Schema::hasColumn('stock_out_transaction', 'comments')) {
            Schema::table('stock_out_transaction', function (Blueprint $table) {
                $table->text('comments')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('stock_out_transaction', 'comments')) {
            Schema::table('stock_out_transaction', function (Blueprint $table) {
                $table->dropColumn('comments');
            });
        }
    }
};
