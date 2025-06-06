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
        Schema::table('invoice', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('invoice','is_profoma')){
                $table->integer('is_profoma')->default(0);
            }
            if(!Schema::hasColumn('invoice','is_acc')){
                $table->integer('is_acc')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            //
            if(Schema::hasColumn('invoice','is_profoma')){
                $table->dropColumn('is_profoma');
            }
            if(Schema::hasColumn('invoice','is_acc')){
                $table->dropColumn('is_acc');
            }
        });
    }
};
