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
        if (!Schema::hasTable('bank_branch')) {
            Schema::create('bank_branch', function (Blueprint $table) {
                $table->id();
                $table->integer('bank_name');
                $table->string('branch_name');
                $table->string('branch_code')->nullable();
                $table->integer('added_by');
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
        if (Schema::hasTable('bank_branch')) {
            Schema::dropIfExists('bank_branch');
        }
    }
};
