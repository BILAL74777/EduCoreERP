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
        Schema::create('disciplines', function (Blueprint $table) {

        $table->string('id')->primary();

        $table->string('faculty_id');

        $table->foreign('faculty_id')
            ->references('id')
            ->on('faculties')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->string('code', 20)->unique();

        $table->string('name');

        $table->unsignedTinyInteger('duration_years')->default(4);

        $table->boolean('is_active')->default(true);

        $table->text('description')->nullable();

        $table->softDeletes();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplines');
    }
};