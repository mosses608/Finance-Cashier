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
        if (!Schema::hasTable('stakeholders')) {
            Schema::create('stakeholders', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->unique();
                $table->string('address')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('tin')->unique();
                $table->string('vrn')->unique()->nullable();
                $table->integer('region_id')->nullable();
                $table->integer('stakeholder_category')->nullable();
                $table->string('customer_type')->nullable();
                $table->string('identification_type')->nullable();
                $table->string('identification_number')->nullable();
                $table->integer('customer_group')->nullable();
                $table->string('regulator_type')->nullable();
                $table->string('supplier_type')->nullable();
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
        if (Schema::hasTable('stakeholders')) {
            Schema::dropIfExists('stakeholders');
        }
    }
};
