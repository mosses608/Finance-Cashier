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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('storage_item_id');
            $table->string('stock_code_combine')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('quantity_in')->nullable();
            $table->integer('quantity_out')->nullable();
            $table->integer('quantity_total')->nullable();
            $table->string('item_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
