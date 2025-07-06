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
        if (!Schema::hasTable('sub_budgests')) {
            Schema::create('sub_budgests', function (Blueprint $table) {
                $table->id();
                $table->string('budget_name');
                $table->string('budget_code');
                $table->string('sub_budget_code')->nullable();
                $table->string('sub_budget_description')->nullable();
                $table->decimal('unit_cost', 20, 2)->default(0.00);
                $table->integer('quantity')->nullable();
                $table->string('unit_meausre')->default('unit');
                $table->string('cost_type');
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
        if (Schema::hasTable('sub_budgests')) {
            Schema::dropIfExists('sub_budgests');
        }
    }
};
