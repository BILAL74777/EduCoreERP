<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_classes', function (Blueprint $table) {

            $table->string('id')->primary(); // CLS000001

            $table->string('discipline_id');

            $table->foreign('discipline_id')
                ->references('id')
                ->on('disciplines')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('code', 20)->unique(); // BSCS-1

            $table->string('name'); // First Year

            $table->unsignedTinyInteger('level');

            $table->string('next_class_id')->nullable();

            $table->foreign('next_class_id')
                ->references('id')
                ->on('academic_classes')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->boolean('is_active')->default(true);

            $table->text('description')->nullable();

            $table->softDeletes();

            $table->timestamps();

            $table->unique(['discipline_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_classes');
    }
};