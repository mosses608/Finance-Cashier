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
        if (!Schema::hasTable('allowance')) {
            Schema::create('allowance', function (Blueprint $table) {
                $table->id();
                $table->string('name'); //Housing, Transport, Airtime
                $table->decimal('default_amount', 15, 2);
                $table->integer('company_id');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->integer('soft_delete')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance');
    }
};
