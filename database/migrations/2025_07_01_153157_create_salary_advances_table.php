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
        if (!Schema::hasTable('salary_advances')) {
            Schema::create('salary_advances', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->decimal('amount', 15, 2);

                $table->unsignedBigInteger('staff_id');
                $table->unsignedBigInteger('year');
                $table->string('project');

                $table->string('attachment')->nullable();
                $table->text('description')->nullable();

                $table->string('status')->default('pending');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->timestamp('approved_at')->nullable();
                $table->boolean('paid')->default(false);
                $table->date('payment_date')->nullable();
                $table->integer('month');
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
        if (Schema::hasTable('salary_advances')) {
            Schema::dropIfExists('salary_advances');
        }
    }
};
