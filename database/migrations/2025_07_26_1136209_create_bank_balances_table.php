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
        if (!Schema::hasTable('bank_balances')) {
            Schema::create('bank_balances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade');
                $table->decimal('opening_balance', 15, 2)->default(0.00);
                $table->decimal('current_balance', 15, 2)->default(0.00);
                $table->boolean('allow_overdraft')->default(false);
                $table->decimal('overdraft_limit', 15, 2)->default(0);
                $table->date('as_of_date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_balances');
    }
};
