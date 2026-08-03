<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_fee_structures', function (Blueprint $table) {

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

            $table->decimal('annual_fee', 12, 2);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('net_fee', 12, 2);

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
        Schema::dropIfExists('student_fee_structures');
    }
};