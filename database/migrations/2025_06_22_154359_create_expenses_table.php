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
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->string('expense_name');
                $table->decimal('amount', 15, 2);

                $table->integer('expense_type')->nullable();
                $table->unsignedBigInteger('budget_id')->nullable();
                $table->string('reference_no')->unique()->nullable();
                $table->text('description')->nullable();

                $table->date('expense_date')->nullable();

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
        if (Schema::hasTable('expenses')) {
            Schema::dropIfExists('expenses');
        }
    }
};
