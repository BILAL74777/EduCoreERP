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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Documents
            $table->foreignId('form_b')
                ->nullable()
                ->constrained('uploads')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('father_cnic', 15)->nullable();

            // Personal Information
            $table->string('name');
            $table->string('father_name');
            $table->string('father_occupation')->nullable();

            $table->enum('gender', ['Male', 'Female', 'Other']);

            $table->date('date_of_birth')->nullable();

            $table->string('religion')->nullable();
            $table->string('tribe_cast')->nullable();

            // Admission
            $table->string('admission_no')->unique();
            $table->date('date_of_admission');

            $table->foreignId('faculty_id')
                ->constrained('faculties')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('discipline_id')
                ->constrained('disciplines')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('year_id')
                ->constrained('years')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('class_admitted_in')
                ->constrained('student_classes')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('current_class')
                ->constrained('student_classes')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            // Status
            $table->string('status')->default('Active');

            $table->date('date_of_withdrawal')->nullable();

            $table->boolean('is_orphan')->default(false);

            $table->boolean('is_father_shaheed')->default(false);

            // Contact
            $table->string('contact_number', 20)->nullable();

            $table->text('address')->nullable();

            // Photo
            $table->foreignId('photo')
                ->nullable()
                ->constrained('uploads')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Remarks
            $table->text('remarks')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};