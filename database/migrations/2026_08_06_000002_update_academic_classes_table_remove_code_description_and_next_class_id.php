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
        if (Schema::hasColumn('academic_classes', 'code')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }

        if (Schema::hasColumn('academic_classes', 'name')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->renameColumn('name', 'year');
            });
        }

        if (Schema::hasColumn('academic_classes', 'description')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }

        if (Schema::hasColumn('academic_classes', 'next_class_id')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->dropColumn('next_class_id');
            });
        }

        if (! Schema::hasColumn('academic_classes', 'next_class')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->string('next_class', 32)->nullable();
            });
        }

        if (! Schema::hasColumn('academic_classes', 'previous_class')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->string('previous_class', 32)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('academic_classes', 'next_class')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->dropColumn('next_class');
            });
        }

        if (Schema::hasColumn('academic_classes', 'previous_class')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->dropColumn('previous_class');
            });
        }

        if (Schema::hasColumn('academic_classes', 'year')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->renameColumn('year', 'name');
            });
        }

        if (! Schema::hasColumn('academic_classes', 'code')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->string('code', 20)->nullable()->unique();
            });
        }

        if (! Schema::hasColumn('academic_classes', 'description')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }

        if (! Schema::hasColumn('academic_classes', 'next_class_id')) {
            Schema::table('academic_classes', function (Blueprint $table) {
                $table->string('next_class_id')->nullable();
            });
        }
    }
};
