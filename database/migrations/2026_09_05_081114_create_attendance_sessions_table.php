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
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Academic Session
            $table->uuid('session_id');

            $table->foreign('session_id')
                ->references('id')
                ->on('academic_sessions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Academic Class
            $table->uuid('class_id');

            $table->foreign('class_id')
                ->references('id')
                ->on('academic_classes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Student Section
            $table->string('section')->nullable();

            // Attendance Date
            $table->date('attendance_date');

            // User who marked attendance
            $table->uuid('marked_by')->nullable();

            // Optional remarks
            $table->text('remarks')->nullable();

            $table->softDeletes();
            $table->timestamps();

            /*
             * One classroom attendance session per
             * academic session + class + section + date.
             *
             * Explicit short index name is used because
             * MySQL has a 64-character identifier limit.
             */
            $table->unique(
                [
                    'session_id',
                    'class_id',
                    'section',
                    'attendance_date',
                ],
                'attendance_session_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
