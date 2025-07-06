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
        if (!Schema::hasTable('invoive_service_items')) {
            Schema::create('invoive_service_items', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id');
                $table->integer('service_id')->nullable();
                $table->integer('amount');
                $table->integer('quantity')->nullable();
                $table->decimal('discount')->default(0);
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
        if (Schema::hasTable('invoive_service_items')) {
            Schema::dropIfExists('invoive_service_items');
        }
    }
};
