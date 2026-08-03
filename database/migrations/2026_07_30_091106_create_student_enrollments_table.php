<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_enrollments', function (Blueprint $table) {

            $table->uuid('id')->primary(); 

            $table->uuid('student_id');

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uuid('session_id');

            $table->foreign('session_id')
                ->references('id')
                ->on('academic_sessions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
 

            $table->uuid('class_id');
            $table->foreign('class_id')
                ->references('id')
                ->on('academic_classes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->uuid('faculty_id');

            $table->foreign('faculty_id')
                ->references('id')
                ->on('faculties')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

        $table->uuid('discipline_id');

            $table->foreign('discipline_id')
                ->references('id')
                ->on('disciplines')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('roll_no')->nullable();

            $table->enum('status', [
                'Active',
                'Promoted',
                'Demoted',
                'Transferred',
                'Graduated',
                'Withdrawn',
                'Struck Off'
            ])->default('Active');

            $table->boolean('is_current')->default(true);

            $table->text('remarks')->nullable();

            $table->softDeletes();

            $table->timestamps();

            $table->unique(['student_id', 'session_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};