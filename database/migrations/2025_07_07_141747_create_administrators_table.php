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
        if (!Schema::hasTable('administrators')) {
            Schema::create('administrators', function (Blueprint $table) {
                $table->id();
                $table->string('names');
                $table->integer('role_id');
                $table->string('email')->unique()->nullable();
                $table->string('phone')->unique();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('administrators')) {
            Schema::dropIfExists('administrators');
        }
    }
};
