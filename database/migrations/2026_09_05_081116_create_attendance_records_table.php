<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('attendance_session_id');

            $table->foreign('attendance_session_id')
                ->references('id')
                ->on('attendance_sessions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uuid('student_enrollment_id');

            $table->foreign('student_enrollment_id')
                ->references('id')
                ->on('student_enrollments')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('status', [
                'Present',
                'Absent',
                'Late',
                'Leave',
            ])->default('Present');

            $table->text('remarks')->nullable();

            $table->timestamp('marked_at')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'attendance_session_id',
                    'student_enrollment_id',
                ],
                'attendance_record_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};