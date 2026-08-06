<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_fee_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('student_id');
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uuid('enrollment_id');
            $table->foreign('enrollment_id')
                ->references('id')
                ->on('student_enrollments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uuid('fee_structure_id');
            $table->foreign('fee_structure_id')
                ->references('id')
                ->on('student_fee_structures')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee_payments');
    }
};
