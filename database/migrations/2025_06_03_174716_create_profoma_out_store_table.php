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
        Schema::create('profoma_out_store', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->enum('order_status', ['Pending','Accepted','Rejected']);
            $table->integer('quantity');
            $table->decimal('amountPay', 20, 2);
            $table->decimal('discount', 20, 2);
            $table->integer('soft_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profoma_out_store');
    }
};
