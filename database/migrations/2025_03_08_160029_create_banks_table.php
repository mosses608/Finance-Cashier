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
        if (!Schema::hasTable('banks')) {
            Schema::create('banks', function (Blueprint $table) {
                $table->id();
                $table->string('bank_name');
                $table->string('account_name')->nullable();
                $table->string('phone')->nullable();
                $table->string('account_number')->unique()->nullable();
                $table->string('address')->nullable();
                $table->string('email')->nullable();
                $table->string('box')->nullable();
                $table->string('bank_code')->nullable();
                $table->integer('region')->nullable();
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
        if (Schema::hasTable('banks')) {
            Schema::dropIfExists('banks');
        }
    }
};
