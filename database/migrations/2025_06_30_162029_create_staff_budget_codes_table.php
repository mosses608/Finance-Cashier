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
        if (!Schema::hasTable('staff_budget_codes')) {
            Schema::create('staff_budget_codes', function (Blueprint $table) {
                $table->id();
                $table->string('project_name');
                $table->string('budget_code');
                $table->integer('budget_year');
                $table->string('budget_name');
                $table->integer('staff_id');
                $table->decimal('budget_cost', 15, 2)->default(0.00);
                $table->string('sub_budget_code');
                $table->integer('created_by');
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
        if (Schema::hasTable('staff_budget_codes')) {
            Schema::dropIfExists('staff_budget_codes');
        }
    }
};
