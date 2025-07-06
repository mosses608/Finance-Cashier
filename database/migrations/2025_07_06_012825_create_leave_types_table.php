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
        if (!Schema::hasTable('leave_types')) {
            Schema::create('leave_types', function (Blueprint $table) {
                $table->id();
                $table->string('name'); 
                $table->integer('days');
                $table->enum('leave_priority', ['Mandatory','Optional'])->default('Optional');
                $table->string('gender_specification')->nullable();
                $table->boolean('require_attachment')->default(0);
                $table->boolean('is_balance_carry_over')->default(0);
                $table->integer('created_by')->nullable();
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
        if (Schema::hasTable('leave_types')) {
            Schema::dropIfExists('leave_types');
        }
    }
};
