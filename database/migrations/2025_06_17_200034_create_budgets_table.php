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
        if (!Schema::hasTable('budgets')) {
            Schema::create('budgets', function (Blueprint $table) {
                $table->id();
                $table->integer('budget_year');
                $table->string('currency')->default('TZS');
                $table->string('cost_type')->nullable();
                $table->string('budget_name')->nullable();
                $table->string('budget_code')->nullable();
                $table->string('project_name')->nullable();
                $table->integer('created_by');
                $table->integer('soft_delete')->default(0);
                $table->boolean('is_approved')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('budgets')) {
            Schema::dropIfExists('budgets');
        }
    }
};
