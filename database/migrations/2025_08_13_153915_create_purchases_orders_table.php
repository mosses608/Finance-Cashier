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
        if (!Schema::hasTable('purchases_orders')) {
            Schema::create('purchases_orders', function (Blueprint $table) {
                $table->id('po_number');
                $table->bigInteger('invoice_id');
                $table->string('budget_year')->nullable();
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->date('order_date')->nullable();
                $table->date('expected_delivery_date')->nullable();
                $table->enum('status', ['pending', 'approved', 'received', 'cancelled'])->default('pending');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('issued_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases_orders');
    }
};
