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
        if (!Schema::hasTable('auth_user_modules')) {
            Schema::create('auth_user_modules', function (Blueprint $table) {
                $table->id();
                $table->string('module_name');
                $table->string('module_label')->nullable();
                $table->string('module_path')->nullable();
                $table->string('module_parent_id')->nullable();
                $table->string('module_icon')->nullable();
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
        Schema::dropIfExists('auth_user_modules');
    }
};
