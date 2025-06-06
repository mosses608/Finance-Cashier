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
        Schema::create('profoma_invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->tinyInteger('category_id');
            $table->integer('invoice_item_id');
            $table->enum('profoma_status', ['Pending','Accepted','Rejected'])->default('Pending');
            $table->integer('soft_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profoma_invoice');
    }
};
