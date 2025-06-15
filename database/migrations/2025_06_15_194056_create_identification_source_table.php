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
        if (!Schema::hasTable('identification_source')) {
            Schema::create('identification_source', function (Blueprint $table) {
                $table->id();
                $table->string('slug');
                $table->string('name');
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
        if (Schema::hasTable('identification_source')) {
            Schema::dropIfExists('identification_source');
        }
    }
};
