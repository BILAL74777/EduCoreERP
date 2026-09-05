<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gate_attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('student_enrollment_id');
            $table->foreign('student_enrollment_id')
                ->references('id')
                ->on('student_enrollments')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('event_type', [
                'IN',
                'OUT',
            ]);

            $table->enum('method', [
                'MANUAL',
                'BARCODE',
            ]);

            $table->timestamp('event_time');

            /*
             * Gate/device information.
             */
            $table->string('gate_id')->nullable();
            $table->string('device_id')->nullable();

            /*
             * User/operator who marked the event.
             */
            $table->uuid('marked_by')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index([
                'student_enrollment_id',
                'event_time'
            ]);

            $table->index([
                'event_type',
                'event_time'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_attendances');
    }
};
