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
        if (!Schema::hasTable('auth')) {
            Schema::create('auth', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unique()->nullable();
                $table->string('username')->unique();
                $table->string('password')->nullable();
                $table->integer('role_id');
                $table->integer('login_attempts')->default(0);
                $table->string('blocked_at')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->boolean('is_online')->default(false);
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('auth')) {
            Schema::dropIfExists('auth');
        }
    }
};
