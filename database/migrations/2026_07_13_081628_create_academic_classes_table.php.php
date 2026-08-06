<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_classes', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->string('discipline_id');

            $table->foreign('discipline_id')
                ->references('id')
                ->on('disciplines')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name');

            $table->unsignedTinyInteger('level');

            $table->string('next_class', 32)->nullable();

            $table->string('previous_class', 32)->nullable();

            $table->boolean('is_active')->default(true);

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