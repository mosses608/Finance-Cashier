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
        Schema::table('profoma_out_store', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('profoma_out_store','customer_id')){
                $table->integer('customer_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profoma_out_store', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('profoma_out_store','customer_id')){
                $table->dropColumn('customer_id');
            }
        });
    }
};
