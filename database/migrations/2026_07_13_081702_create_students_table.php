<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {

            $table->string('id')->primary(); // STD000001

            // Documents
            $table->foreignId('form_b')
                ->nullable()
                ->constrained('uploads')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('photo')
                ->nullable()
                ->constrained('uploads')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Admission
            $table->string('admission_no')->unique();

            $table->string('registration_no')->nullable()->unique();

            $table->date('date_of_admission');

            // Fee
            $table->decimal('annual_fee', 12, 2)->default(0);

            // Personal Information
            $table->string('name');

            $table->string('father_name');

            $table->string('father_cnic', 15)->nullable();

            $table->string('father_occupation')->nullable();

            $table->enum('gender', ['Male', 'Female', 'Other']);

            $table->date('date_of_birth')->nullable();

            $table->string('religion')->nullable();

            $table->string('tribe_cast')->nullable();

            // Contact
            $table->string('contact_number', 20)->nullable();

            $table->text('address')->nullable();

            // Special Flags
            $table->boolean('is_orphan')->default(false);

            $table->boolean('is_father_shaheed')->default(false);

            // Remarks
            $table->text('remarks')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};