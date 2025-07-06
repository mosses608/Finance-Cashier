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
        if (!Schema::hasTable('emplyees')) {
            Schema::create('emplyees', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('middle_name')->nullable();
                $table->string('gender')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('national_id_number')->nullable();

                // Contact Info
                $table->string('email')->unique();
                $table->string('phone_number')->nullable();
                $table->string('emergency_contact_name')->nullable();
                $table->string('emergency_contact_phone')->nullable();

                // Employment Details
                $table->string('role')->nullable();
                $table->string('department')->nullable();
                $table->string('job_title')->nullable();
                $table->date('date_hired')->nullable();
                $table->date('contract_end_date')->nullable();
                $table->enum('employment_type', ['Permanent', 'Contract', 'Intern'])->default('Permanent');
                $table->string('reporting_manager')->nullable();

                // Address Info
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('region')->nullable();
                $table->string('country')->nullable();
                $table->string('postal_code')->nullable();

                // Bank & Payment Info
                $table->string('bank_name')->nullable();
                $table->string('bank_account_number')->nullable();
                $table->string('tax_identification_number')->nullable();
                $table->string('social_security_name')->nullable();
                $table->string('social_security_number')->nullable();

                // HR Status
                $table->enum('status', ['Active', 'Inactive', 'Terminated', 'Resigned'])->default('Active');
                $table->date('termination_date')->nullable();
                $table->text('termination_reason')->nullable();

                // Metadata
                $table->unsignedBigInteger('created_by')->nullable();

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
        if (Schema::hasTable('emplyees')) {
            Schema::dropIfExists('emplyees');
        }
    }
};
