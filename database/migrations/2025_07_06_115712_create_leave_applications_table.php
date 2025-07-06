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
        if (!Schema::hasTable('leave_applications')) {
            Schema::create('leave_applications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id'); // Employee applying for leave
                $table->integer('leave_type'); // e.g. 'Annual', 'Sick', 'Maternity'
                $table->date('start_date');
                $table->date('end_date');
                $table->text('reason')->nullable();
                $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
                $table->unsignedBigInteger('approved_by')->nullable(); // Admin or manager who approves
                $table->timestamp('approved_at')->nullable();
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
        if (Schema::hasTable('leave_applications')) {
            Schema::dropIfExists('leave_applications');
        }
    }
};
