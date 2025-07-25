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
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('company_reg_no')->unique();
                $table->string('company_name');
                $table->string('company_email')->nullable();
                $table->string('website')->nullable();
                $table->integer('region')->nullable();
                $table->string('address')->nullable();
                $table->string('tin')->nullable();
                $table->string('vrn')->nullable();
                $table->string('logo')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
